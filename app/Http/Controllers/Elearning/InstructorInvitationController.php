<?php

namespace App\Http\Controllers\Elearning;

use AppHelper;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Spatie\Permission\Models\Role;
use App\Models\Elearning\ELearningInstructor;
use App\Models\Elearning\ELearningInstructorInvitation;
use App\Models\Settings\District;
use App\Models\Settings\Language;

class InstructorInvitationController extends Controller
{
    // if (! Gate::allows('list_el_instructor_invitations index
    // if (! Gate::allows('add_el_instructor_invitations create

    // if (! Gate::allows('add_el_instructor_invitations store
    // if (! Gate::allows('add_el_instructor_invitations edit
    // if (! Gate::allows('add_el_instructor_invitations destroy
    // if (! Gate::allows('add_el_instructor_invitations massDestroy

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('e_learning.instructor_invitations.index');
    } 

       /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('name', 'instructor')->pluck('name', 'id')->all();
        return view('e_learning.instructor_invitations.create',compact('roles'));
    }


   /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * model_id - user_id
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'full_name' => 'required|min:3',
            'email'     => 'required|unique:e_learning_instructor_invitations',
            'role_id'   => 'required|exists:roles,id'
        ]);

        try {
            
            $token = $this->generateToken();

            $data = [
                'full_name' => $request->full_name,
                'email'     => $request->email,
                'token'     => $token,
                'role_id'   => $request->role_id,
                'user_id'    => auth()->user()->id,
                'expires_at' => Carbon::now()->addDays(2)
            ];        

            $invite = ELearningInstructorInvitation::create($data);

            $body = $this->invitation_message($request->full_name, 'e-learning/invite', $token, $invite->id);
            AppHelper::instance()->sendEmail($request->email, 'M-OMULIMISA Instructor', $body);
            
            return redirect()->route('e-learning.instructor-invitations.index')->with('success','Operation was successful');

        }
        catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();            
        }

    }

    public function show($id)
    {
        # code...
    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invite = ELearningInstructorInvitation::findOrFail($id);

        $token = $this->generateToken();

        $body = $this->invitation_message($invite->full_name, 'e-learning/invite', $token, $invite->id);
        AppHelper::instance()->sendEmail($invite->email, 'M-OMULIMISA Instructor', $body);

        $invite->update([
            'token'         => $token, 
            'expires_at'    => Carbon::now()->addDays(2),
            'cancelled_at'  => null,
            'rejected_at'   => null
        ]);

        return redirect()->back()->with('success', 'Operation was successful');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {  }

    public function destroy($id)
    {
        if($data = ELearningInstructorInvitation::findOrFail($id)) {
            $data->update([
                'token'         => null, 
                'cancelled_at'  => Carbon::now(),
            ]); 

            return redirect()->back()->with('success', 'Operation was successful');           
        } 
        else {
            return redirect()->back()->withErrors('Operation was NOT successful');
        }
    }

    /**
     * Delete all selected resources at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {        
        if ($request->input('data_ids')) {
            $data_id_array = explode(",", $request->input('data_ids')); 
            if(!empty($data_id_array)) {
            foreach($data_id_array as $id) { }
            }
        }
    }

    public function massData(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $data = ELearningInstructorInvitation::select(['*', \DB::raw('@rownum  := @rownum  + 1 AS rownum') ]);

        $datatables = app('datatables')->of($data);

        return $datatables
        ->addColumn('check', function ($data){
            return '<input type="checkbox" value="'.$data->id.'" class="deleteRow" />';
            })
        ->addColumn('role', function ($data){
            return $data->role->name;
            })
        ->addColumn('invited_by', function ($data){
            return $data->user->name;
            })
        ->addColumn('status', function ($data){
            return $this->invitation_status($data);
            })
        ->addColumn('actions', function ($data){
                $entity = "e-learning.instructor-invitations";
                $id = $data->id;
                $edit_rights = is_null($data->accepted_at) || $data->expires_at < Carbon::now() || !is_null($data->cancelled_at) || !is_null($data->rejected_at) ? 'add_el_instructor_invitations' : null;
                $edit_rename = 'Resend';

                $delete_rights = is_null($data->accepted_at) ? 'add_el_instructor_invitations' : null;
                $delete_rename = 'Cancel';
                return view('partials.actions', compact('entity', 'id','edit_rights','delete_rights', 'edit_rename', 'delete_rename'))->render();           
            })
        ->rawColumns(['check', 'actions'])
        ->make(true);
    }

    /**
     * Generating an invitation token
     * @param 
     * @return string token
     */
    private static function generateToken()
    {
        do {
            //generate a random string
            $token = generateRandomString(0, 9, 30);
        } //check if the token already exists and if it does, try again
        while (ELearningInstructorInvitation::where('token', $token)->first());

        return $token;
    }

    public function invitation_message($full_name, $url, $token, $id)
    {
        $body = "Dear <b>".$full_name.",</b><br/>";
        $body .= "<p>You have been invited to ".config('app.name')." as an e-Leaning instructor.<br/>";
        $body .= '<a href="'.url($url."/accept/".$id."/".$token).'">Accept</a> or <a href="'.url($url."/reject/".$id."/".$token).'">Reject</a></p>';
        $body .= 'This invitation expires in 48 Hours!<br/>';
        $body .= '<hr/><br/>';
        $body .= 'If you’re having trouble clicking the buttons, copy and paste the URL below into your web browser:<br/>';
        $body .= 'Accept Invitation: '.url($url."/accept/".$id."/".$token).'<br/>';
        $body .= 'Reject Invitation: '.url($url."/reject/".$id."/".$token).'<br><br/>';
        $body .= 'Regards<br/>';
        $body .= config('app.name');

        return $body;
    }

    public function invitation($action, $id, $token)
    {        
        try {
            if ($invitation = ELearningInstructorInvitation::whereId($id)->whereToken($token)->first()) {
                if ($invitation->expires_at < Carbon::now()) { 
                    $errorMessage = 'Invitation exipred';
                }
                elseif ($this->invitation_status($invitation) == 'Pending') {

                    if ($action == 'accept') {
                        $gender         = ELearningInstructor::gender;
                        $affiliation    = ELearningInstructor::affiliation;
                        $age_group      = ELearningInstructor::age_group;
                        $qualification  = ELearningInstructor::qualification;
                        $countries      = ELearningInstructor::countries;
                        $districts = District::orderBy('name', 'ASC')->pluck('name', 'id')->all();
                        $languages = Language::orderBy('name', 'ASC')->pluck('name', 'id')->all();
                        return view('landing.e_learning.instructor_register', compact('invitation', 'gender', 'affiliation', 'age_group', 'qualification', 'languages', 'countries', 'districts'));
                    }
                    elseif ($action == 'reject') {
                        if ($invitation->update(['rejected_at' => Carbon::now(), 'token' => null])) {
                            return redirect()->route('auth.login')->with('success', 'Invitation has been successfully rejected');
                        }
                    }
                    else{
                       $errorMessage = 'Unknown action'; 
                    }
                }
                else{
                    $errorMessage = 'Invitation was '.$invitation->status->name;
                }
            }
            else{
                $errorMessage = 'Invitation does not exist';
            }

            return redirect()->route('auth.login')->withErrors($errorMessage.', contact Administrator');
        } catch (Throwable $throwable) {

            return redirect()->route('auth.login')->withErrors($throwable->getMessage());
        }
    }

    public function invitation_status($data)
    {
        if (!is_null($data->accepted_at)) {
            $status = 'Accepted';
        }
        elseif (!is_null($data->rejected_at)) {
            $status = 'Rejected';
        }
        elseif (!is_null($data->cancelled_at)) {
            $status = 'Cancelled';
        }
        elseif ($data->expires_at < Carbon::now()) {
            $status = 'Expired';
        }
        else {
            $status = 'Pending';
        }
        return $status;
    }

}
