<?php

namespace App\Http\Controllers\Elearning;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Settings\Subcounty;
use App\Models\Settings\District;
use App\Models\Settings\Parish;

use App\Models\Settings\Language;
use App\Models\Elearning\ELearningInstructor;
use App\Models\Elearning\ELearningInstructorInvitation;
use App\Helpers\LocalPhoneValidationRule;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Organisation;
use Illuminate\Support\Facades\File;
use App\Helpers\TelephoneValidateRule;

class InstructorController extends Controller
{
        // if (! Gate::allows('delete_el_instructors massDestroy
        // if (! Gate::allows('delete_el_instructors destroy
        // if (! Gate::allows('view_el_instructors show

    // use RegistersUsers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('e_learning.instructors.index');
    } 

    /**
     * Handle a registration request for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            if ($invitation   = ELearningInstructorInvitation::find($request->invitation_id)) {
              $input          = $request->all();

              $validator      = $this->validator($input);
              if($validator->fails())
              {
                  return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
              }

              $tel_validator      = $this->tel_validator($input);
              if($tel_validator->fails())
              {
                  return redirect()->back()->withErrors($tel_validator->getMessageBag())->withInput();
              }

              // event(new Registered());
              $user = $this->create($request->all());

              $invitation->update([
                'token'         => null,
                'accepted_at'   => Carbon::now()
              ]);

              // $this->guard()->login($user);

              return redirect()->route('auth.login')->with('success', 'Account successfully created');
            }
            else{
              return redirect()->back()->withErrors('Resource NOT Found')->withInput();
            }

        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        } 
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    { 
      $messages = [
        'email.required' => 'Please enter invitation email',
        'password.required' => 'Please enter password',
      ];

      $validator = Validator::make($data, [
        'accept_terms_and_conditions' => 'required',
        'token'         => 'required|exists:e_learning_instructor_invitations,token',
        'role_id'       => 'required',

        'full_name'     => 'required|string|max:150',
        'gender'        => 'required',
        'age_group'     => 'required',
        'affiliation'   => 'required',
        'qualification' => 'required',
        'phone'         => [new LocalPhoneValidationRule, 'unique:e_learning_instructors,phone_number'],
        'district_id'   => 'required|exists:districts,id',

        'username'      => 'required|unique:users,username',
        'email'         => 'required|email|unique:users,email',
        'password'      => 'required|string|min:6|confirmed'   
      ], $messages);

      return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name'      => $data['full_name'],
            'email'     => $data['email'],
            'password'  => $data['password'],
            'username'  => $data['username'],
            'telephone' => formatPhoneNumber($data['phone'], '256', '256'),
            'status'    => true,
            'confirmed' => true
        ]);

        if ($user) {
          $role = Role::findOrFail($data['role_id']);
          $user->assignRole($role->name);

          $instructor = ELearningInstructor::create([
                'full_name'     => $data['full_name'],
                'gender'        => $data['gender'],
                'age_group'     => $data['age_group'],
                'affiliation'   => $data['affiliation'],
                'qualification' => $data['qualification'],
                'country'       => $data['country'],
                'phone_number'  => $data['phone'],
                'district_id'   => $data['district_id'],
                'subcounty_id'  => $data['subcounty_id'],
                'parish_id'     => $data['parish_id'],
                'village'       => $data['village'],
                'user_id'       => $user->id
              ]);
                // 'picture' => $data[''],
                // 'email_notifications' => $data[''],
                // 'sms_notifications' => $data[''],
                // 'organisation_id' => $data[''],
        }
    }

    public function show($id)
    {
        $data = ELearningInstructor::find($id);
        return view('e_learning.instructors.show', compact('data'));
    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = ELearningInstructor::findOrFail($id);

        $gender         = ELearningInstructor::gender;
        $affiliation    = ELearningInstructor::affiliation;
        $age_group      = ELearningInstructor::age_group;
        $qualification  = ELearningInstructor::qualification;
        $countries      = ELearningInstructor::countries;
        $districts      = District::orderBy('name', 'ASC')->pluck('name', 'id')->all();
        $subcounties    = Subcounty::orderBy('name', 'ASC')->pluck('name', 'id')->all();
        $parishes       = Parish::orderBy('name', 'ASC')->pluck('name', 'id')->all();
        $languages      = Language::orderBy('name', 'ASC')->pluck('name', 'id')->all();

        $roles = Role::where('name', 'organisation')->limit(1)->first(); 
        $organisations = User::select('*')->whereIn('id',function($query) use ($roles){
                $query->select('model_id')->where('role_id', $roles->id)->from('model_has_roles');
            })->orderBy('name', 'ASC')->pluck('name', 'id')->all();

        return view('e_learning.instructors.edit', compact('data', 'gender', 'affiliation', 'age_group', 'qualification', 'languages', 'countries', 'districts', 'organisations', 'subcounties', 'parishes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            if ($data   = ELearningInstructor::find($id)) {
              $input          = $request->all();
              $validator      = $this->update_validator($input);

              if($validator->fails())
              {
                  return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
              }

              $tel_validator      = $this->tel_validator($input, $id);
              if($tel_validator->fails())
              {
                  return redirect()->back()->withErrors($tel_validator->getMessageBag())->withInput();
              }

              User::where('id', $data->user_id)->update([
                    'telephone' => formatPhoneNumber($request->phone_number, '256', '256'),
                ]);

              $data->update([
                'full_name'     => $request->full_name,
                'gender'        => $request->gender,
                'age_group'     => $request->age_group,
                'affiliation'   => $request->affiliation,
                'qualification' => $request->qualification,
                'country'       => $request->country,
                'phone_number'  => $request->phone_number,
                'district_id'   => $request->district_id,
                'subcounty_id'  => $request->subcounty_id,
                'parish_id'     => $request->parish_id,
                'village'       => $request->village,
                'email_notifications' => $request->has('email_notifications'),
                'sms_notifications'   => $request->has('sms_notifications'),
                'organisation_id'     => $request->organisation_id,
                'business'            => $request->business
              ]);

              return redirect()->route('e-learning.instructors.show', $id)->with('success', 'Profile successfully updated');
            }
            else{
              return redirect()->back()->withErrors('Resource NOT Found')->withInput();
            }

        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        } 
    }

    public function destroy($id)
    {
        if($data = ELearningInstructor::findOrFail($id)) {

            // code
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
            foreach($data_id_array as $id) {
                if($data = ELearningInstructor::find($id)) {

                    // code
                }
            }
            }
        }
    }

    public function massData(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $data = ELearningInstructor::select(['*', \DB::raw('@rownum  := @rownum  + 1 AS rownum') ]);

        $datatables = app('datatables')->of($data);

        return $datatables
        ->addColumn('check', function ($data){
            return '<input type="checkbox" value="'.$data->id.'" class="deleteRow" />';
            })
        ->addColumn('profile', function ($data){
            $picture = is_null($data->picture) ? 'uploads/profile_pics/default.png' : 'uploads/'.$data->picture; 
            return '<div class="media mt-0">
                        <img class="avatar-lg rounded-circle mr-3" src="'.asset($picture).'" alt="Img">
                            <div class="media-body">
                                <div class="d-md-flex align-items-center">
                                    <h4 class="mb-1">
                                        '.$data->full_name.'
                                    </h4>
                                </div>
                                <p class="mb-0">
                                <span class="text-muted">Gender:</span> '.$data->gender.'<br/>
                                <span class="text-muted">Age Group:</span> '.$data->age_group.'<br/>
                                </p>
                            </div>
                        </div>';
            })
        ->addColumn('contact', function ($data){
            return '<span class="text-muted">Phone:</span> '.$data->phone_number.
                    '<br/><span class="text-muted">Email:</span> '.$data->user->email.
                    '<br/><span class="text-muted">Location:</span> '.$data->district->name.', '.$data->country;
            })
        ->addColumn('other', function ($data){
            return '<span class="text-muted">Affiliation:</span> '.$data->affiliation.
                    '<br/><span class="text-muted">Qualification:</span> '.$data->qualification;
            })
        ->addColumn('actions', function ($data){
                $entity = "e-learning.instructors";
                $id = $data->id;
                $edit_rights = 'edit_el_instructors';
                $view_rights = 'view_el_instructors';
                return view('partials.actions', compact('entity', 'id','edit_rights','view_rights'))->render();
           
            })
        ->rawColumns(['check', 'actions', 'profile', 'contact', 'other'])
        ->make(true);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function update_validator(array $data)
    { 
      $validator = Validator::make($data, [
        'full_name'     => 'required|string|max:150',
        'gender'        => 'required',
        'age_group'     => 'required',
        'affiliation'   => 'required',
        'qualification' => 'required',
        'phone_number'  => [new LocalPhoneValidationRule, 'unique:e_learning_instructors,phone_number'],
        'district_id'   => 'required|exists:districts,id',  
      ]);

      return $validator;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function tel_validator(array $data, $id=null)
    { 
      $validator = Validator::make($data, [
        'phone_number'     => [new TelephoneValidateRule( $data['role_id'], formatPhoneNumber($data['phone_number'], '256', '256'), $id)],
      ]);

      return $validator;
    }

    /**
     * Change photo form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changePicture($id)
    {
        $data = ELearningInstructor::findOrFail($id);
        return view('e_learning.instructors.upload', compact('data'));
    }

    /**
     * Change picture.
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function storePicture(Request $request)
    {
    if ($request->hasFile('picture')){

        $validator = $this->photo_validator($request->all())->validate();

        $file = $request->picture;
        $instructor = ELearningInstructor::findOrFail($request->instructor_id);
        
        if(!is_null($instructor->picture)){
            File::delete(base_path() . '/public/uploads/'.$instructor->picture);
        }
                $filepath = $file->store('instructors', 'uploads');      
    }
    else{
        $filepath = null;
    }
    
    ELearningInstructor::where('id',$request->instructor_id)->update(['picture' => $filepath]); 
    return redirect()->route('e-learning.instructors.show',$request->instructor_id)->with('success','Profile picture updated successfully');
    }

    /**
     * Get a validator for an incoming change password request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function photo_validator(array $data)
    {
        return Validator::make($data, [
            'picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    }

}
