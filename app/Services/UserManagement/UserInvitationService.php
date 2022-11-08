<?php


namespace App\Services\UserManagement;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserManagement\UserInvitation;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendNewUserInvitationNotification;
use App\Models\Dictionary\DsmParticipatingInstitution;
use App\Models\Dictionary\DsmPiBranch;

class UserInvitationService
{
    public function __construct() { }

    /**
     * About: 
     * @param 
     * @return 
     */
    public static function sendInstitutionInvitation(Request $request)
    {
        $request->request->add(['status' => UserInvitation::STATUS_PENDING]);

        $institution = DsmParticipatingInstitution::whereId($request->institution)->first();
    
        $invitation = UserInvitation::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'token'         => static::generateToken(),
            'role_id'       => $request->role_id,
            'billing_plan_id'   => $request->billing_plan_id,
            'institution_name'  => $institution->name,
            'institution_code'  => $institution->code,
            'institution_type_id' => $institution->institution_type_id
        ]);

        Notification::route('mail', $request->email)->notify(new SendNewUserInvitationNotification($invitation));

        return $invitation;
    }

    /**
     * About: 
     * @param 
     * @return 
     */
    public static function sendInstitutionUserInvitation(Request $request)
    {
        $request->request->add(['status' => UserInvitation::STATUS_PENDING]);
    
        $invitation = UserInvitation::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'token'         => static::generateToken(),
            'role_id'       => $request->role_id,
            'institution_id' => auth()->user()->institution->id
        ]);

        Notification::route('mail', $request->email)->notify(new SendNewUserInvitationNotification($invitation));

        return $invitation;
    }

    /**
     * About: 
     * @param 
     * @return 
     */
    public static function sendBranchInvitation(Request $request)
    {
        $request->request->add(['status' => UserInvitation::STATUS_PENDING]);

        $branch = DsmPiBranch::whereId($request->branch_name)->first();
    
        $invitation = UserInvitation::create([
            'branch_name'   => $branch->name,
            'branch_id'     => $branch->id,
            'institution_id'=> auth()->user()->institution->id,
            'name'          => $request->name,
            'email'         => $request->email,
            'token'         => static::generateToken(),
            'role_id'       => $request->role_id,
        ]);

        Notification::route('mail', $request->email)->notify(new SendNewUserInvitationNotification($invitation));

        return $invitation;
    }

    /**
     * About: 
     * @param 
     * @return 
     */
    public static function sendBranchUserInvitation(Request $request)
    {
        $request->request->add(['status' => UserInvitation::STATUS_PENDING]);
    
        $invitation = UserInvitation::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'token'         => static::generateToken(),
            'role_id'       => $request->role_id,
            'institution_id' => auth()->user()->institution->id,
            'branch_id'     => auth()->user()->branch->id
        ]);

        Notification::route('mail', $request->email)->notify(new SendNewUserInvitationNotification($invitation));

        return $invitation;
    }

    /**
     * About: 
     * @param 
     * @return 
     */
    public static function invitationData(Request $request, $permission, $route, $role_id=null)
    {
        DB::statement(DB::raw('set @DT_RowIndex=0'));
        $data = UserInvitation::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ]);
        $datatables = app('datatables')->of($data);

        /*Data of a single role*/
        if (!is_null($role_id)) {
            $data->whereRoleId($role_id);
        }

        /*Data of a single user*/
        if ($institution_id = $datatables->request->get('institution_id')) {
            $data->whereInstitutionId($institution_id);
        }

        /*Data of a single branch*/
        if ($branch_id = $datatables->request->get('branch_id')) {
            $data->whereBranchId($branch_id);
        }

        $data->orderBy('created_at', 'DESC');
        
        return $datatables
            ->addIndexColumn()
            ->addColumn('_institution', function($data){
                return !is_null($data->institution_id) ? $data->institution->participating_institution_id : $data->institution_name;
            })
            ->addColumn('_institution_type', function($data){
                return !is_null($data->institution_type) ? $data->institution_type->value : $data->institution->institution_type->value;
            })
            ->addColumn('_branch', function($data){
                return !is_null($data->branch_id) ? $data->branch->name : $data->branch_name;
            })
            ->addColumn('_status', function($data){
                $data->expireInvite();
                return '<label class="badge badge-'.$data->status.'">'.$data->status.'</label>';
            })
            ->addColumn('_role', function($data){
                return '<label class="badge badge-default">'.$data->role->name.'</label>';
            })
            ->addColumn('_action_at', function($data){
                return $data->actionTime();
            })
            ->addColumn('created', function($data){
                return $data->created_at;
            })
            ->addColumn('updated', function($data){
                return $data->updated_at;
            })
            ->addColumn('action', function($data) use ($permission, $route){
                $route          = $route.'.invitations';
                $permission     = $permission;
                $id             = $data->id;
                $manage         = 'manage_'.$permission;
                $resend         = $data->status != UserInvitation::STATUS_ACCEPTED ? true : false;
                $cancel         = $data->status == UserInvitation::STATUS_PENDING ? true : false;
                return view('user_management.invite_actions', compact('route', 'id','manage', 'resend', 'cancel'))->render();
            })
            ->rawColumns(['action', '_status', '_role'])
            ->make(true);
    }

    /**
     * About: 
     * @param 
     * @return 
     */
    public static function resendInvitation($inviteId)
    {
        if ($invitation = UserInvitation::whereId($inviteId)->where('status', '!=', UserInvitation::STATUS_ACCEPTED)->first()) {
            if (User::whereEmail($invitation->email)->first()) {
                \Session::flash('invite_failed', 'Email is already taken');
                return false;
            }

            do {
                //generate a random string
                $token = generate_random_str(0, 9, 30);
            } //check if the token already exists and if it does, try again
            while (UserInvitation::where('token', $token)->first());
        
            $invitation->update([
                'token'         => $token,
                'status'        => UserInvitation::STATUS_PENDING,
                'cancelled_at'  => null,
                'rejected_at'   => null,
                'accepted_at'   => null,
                'expires_at'    => Carbon::now()->addDays(2),
            ]);

            Notification::route('mail', $invitation->email)->notify(new SendNewUserInvitationNotification($invitation));

            return true;
        }
        return false;
    }

    /**
     * About: 
     * @param 
     * @return 
     */
    public static function cancelInvitation($inviteId)
    {
        if ($invitation = UserInvitation::whereId($inviteId)->whereStatus(UserInvitation::STATUS_PENDING)->first()) {
            if ($invitation->cancelInvite()) {
                return true;
            }
        }

        return false;
    }

    /**
     * About: 
     * @param 
     * @return 
     */
    public static function deleteInvitation($inviteId)
    {
        if ($invitation = UserInvitation::whereId($inviteId)->whereStatus(UserInvitation::STATUS_PENDING)->first()) {
            if ($invitation->deleteInvite()) {
                return true;
            }
        }
        \Session::flash('invite_failed', 'Invitation is already accepted');
        return false;
    }

    /**
     * About: 
     * @param 
     * @return 
     */
    private static function generateToken()
    {
        do {
            //generate a random string
            $token = generate_random_str(0, 9, 30);
        } //check if the token already exists and if it does, try again
        while (UserInvitation::where('token', $token)->first());

        return $token;
    }


}
