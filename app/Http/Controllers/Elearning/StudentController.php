<?php

namespace App\Http\Controllers\Elearning;

use AppHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Settings\Subcounty;
use App\Models\Settings\District;
use App\Models\Settings\Parish;

use App\Models\Settings\Language;
use App\Models\Elearning\ELearningStudent;
use App\Models\Elearning\ELearningInstructor;
use App\Helpers\PhoneValidationRule;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Organisation;
use Illuminate\Support\Facades\File;

class StudentController extends Controller
{
    // if (!Gate::allows('add_el_students create
    // if (! Gate::allows('view_el_students show
    // if (! Gate::allows('delete_el_students destroy
    // if (! Gate::allows('delete_el_students massData
    // if (!Gate::allows('add_el_students upload

    // use RegistersUsers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('e_learning.students.index');
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gender         = ELearningInstructor::gender;
        $affiliation    = ELearningInstructor::affiliation;
        $age_group      = ELearningInstructor::age_group;
        $qualification  = ELearningInstructor::qualification;
        $countries      = ELearningInstructor::countries;
        $districts      = District::orderBy('name', 'ASC')->pluck('name', 'id')->all();
        $languages      = Language::orderBy('name', 'ASC')->pluck('name', 'id')->all();
        $subcounties    = Subcounty::orderBy('name', 'ASC')->pluck('name', 'id')->all();
        $parishes       = Parish::orderBy('name', 'ASC')->pluck('name', 'id')->all();

        $roles = Role::where('name', 'organisation')->limit(1)->first(); 
        $organisations = User::select('*')->whereIn('id',function($query) use ($roles){
                $query->select('model_id')->where('role_id', $roles->id)->from('model_has_roles');
            })->orderBy('name', 'ASC')->pluck('name', 'id')->all();

        return view('e_learning.students.create', compact('gender', 'affiliation', 'age_group', 'qualification', 'languages', 'countries', 'districts', 'organisations', 'subcounties', 'parishes'));
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
            $input          = $request->all();
            $validator      = $this->validator($input);

            if($validator->fails())
            {
              return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }

            // event(new Registered());
            $user = $this->create_student($request->all());

            return redirect()->route('e-learning.students.index')->with('success', 'Account successfully created');

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
        'full_name'     => 'required|string|max:150',
        'gender'        => 'required',
        'age_group'     => 'required',
        'affiliation'   => 'required',
        'qualification' => 'required',
        'email'         => 'nullable',
        // 'phone_number'  => [new LocalPhoneValidationRule, 'unique:e_learning_students,phone_number'],
        'phone_number'  => [new PhoneValidationRule, 'unique:e_learning_students,phone_number'],
        'district_id'   => 'required|exists:districts,id',   
      ], $messages);

      return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create_student(array $data)
    {
      $student = ELearningStudent::create([
            'full_name'     => $data['full_name'],
            'gender'        => $data['gender'],
            'age_group'     => $data['age_group'],
            'affiliation'   => $data['affiliation'],
            'qualification' => $data['qualification'],
            'country'       => $data['country'],
            'phone_number'  => $data['phone_number'],
            'email'         => $data['email'],
            'district_id'   => $data['district_id'],

            'subcounty_id'  => $data['subcounty_id'] ?? null,
            'parish_id'     => $data['parish_id'] ?? null,
            'village'       => $data['village'] ?? null,
            'email_notifications' => isset($data['email_notifications']) && $data['email_notifications'] == "on" ? true : false,
            'sms_notifications'   => isset($data['sms_notifications']) && $data['sms_notifications'] == "on" ? true : false,
            'organisation_id'     => $data['organisation_id'] ?? null,
            'business'            => $data['business'] ?? null,
            'added_by'            => $data['added_by'],
            'user_id'             => 0,
          ]);
    }

    public function show($id)
    {
        $data = ELearningStudent::find($id);
        return view('e_learning.students.show', compact('data'));
    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = ELearningStudent::findOrFail($id);

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

        return view('e_learning.students.edit', compact('data', 'gender', 'affiliation', 'age_group', 'qualification', 'languages', 'countries', 'districts', 'organisations', 'subcounties', 'parishes'));
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
            if ($data   = ELearningStudent::find($id)) {
              $input          = $request->all();

              $validator      = $this->update_validator($input, $id);
              if($validator->fails())
              {
                  return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
              }

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

              return redirect()->route('e-learning.students.show', $id)->with('success', 'Profile successfully updated');
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
        if($data = ELearningStudent::findOrFail($id)) {

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
                if($data = ELearningStudent::find($id)) {

                    // code
                }
            }
            }
        }
    }

    public function massData(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $data = ELearningStudent::select(['*', \DB::raw('@rownum  := @rownum  + 1 AS rownum') ]);

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
                    '<br/><span class="text-muted">Email:</span> '.$data->email.
                    '<br/><span class="text-muted">Location:</span> '.$data->district->name.', '.$data->country;
            })
        ->addColumn('other', function ($data){
            return '<span class="text-muted">Affiliation:</span> '.$data->affiliation.
                    '<br/><span class="text-muted">Qualification:</span> '.$data->qualification;
            })
        ->addColumn('actions', function ($data){
                $entity = "e-learning.students";
                $id = $data->id;
                $edit_rights = 'edit_el_students';
                $view_rights = 'view_el_students';
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
    protected function update_validator(array $data, $id)
    { 
      $validator = Validator::make($data, [
        'full_name'     => 'required|string|max:150',
        'gender'        => 'required',
        'age_group'     => 'required',
        'affiliation'   => 'required',
        'qualification' => 'required',
        'email'         => 'nullable',
        // 'phone_number'  => [new LocalPhoneValidationRule, 'unique:e_learning_students,phone_number,'.$id],
        'phone_number'  => [new PhoneValidationRule, 'unique:e_learning_students,phone_number,'.$id],
        'district_id'   => 'required|exists:districts,id',  
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
        $data = ELearningStudent::findOrFail($id);
        return view('e_learning.students.upload', compact('data'));
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
        $student = ELearningStudent::findOrFail($request->student_id);
        
        if(!is_null($student->picture)){
            File::delete(base_path() . '/public/uploads/'.$student->picture);
        }
                $filepath = $file->store('students', 'uploads');      
    }
    else{
        $filepath = null;
    }
    
    ELearningStudent::where('id',$request->student_id)->update(['picture' => $filepath]); 
    return redirect()->route('users.show',$request->student_id)->with('success','Profile picture updated successfully');
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        $role = Role::where('name', 'student')->limit(1)->first();

        return view('e_learning.students.upload', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * model_id - user_id
     */
    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,txt',
        ]);

        try{
            $directory = 'uploads/temp/students/';

            if( !File::isDirectory($directory) )
            {
                File::makeDirectory($directory, 0777, true, true);
                $file_to_write      = 'temp-students-uploads.txt';
                $content_to_write   = "Temp students uploads \nCreated on " . date('d-m-Y');
                $file               = fopen($directory . '/' . $file_to_write,"w");
                fwrite($file, $content_to_write);
                fclose($file);
            }

            $header = NULL;
            $datas = array();

                                              


            if($file = $request->file('file')){
                $file_name = $file->getClientOriginalName();
                $file->move($directory,$file_name);

                if ( $directory . $file_name ) {
                    if (( $handle = fopen($directory . $file_name, 'r' )) !== FALSE)
                    {
                        while (($row = fgetcsv($handle, 1000, ',')) !== FALSE)
                        {
                            if(!$header) {
                                $header = $row;
                            }
                            else {
                                $record = array_combine($header, $row);
                                // Check if headers are correct
                                if (! array_key_exists('full_name', $record) || 
                                    ! array_key_exists('email', $record) || 
                                    ! array_key_exists('phone', $record) ||
                                    ! array_key_exists('age_group', $record) ||
                                    ! array_key_exists('gender', $record) || 
                                    ! array_key_exists('affiliation', $record) ||
                                    ! array_key_exists('qualification', $record) ||
                                    ! array_key_exists('country', $record) ||
                                    ! array_key_exists('district', $record) || 
                                    ! array_key_exists('subcounty', $record) || 
                                    ! array_key_exists('parish', $record) || 
                                    ! array_key_exists('village', $record) || 
                                    ! array_key_exists('business', $record)
                                ){
                                    File::delete($directory . $file_name);
                                    return redirect()->back()->withErrors("Invalid File contents. Refer to correct upload template");
                                }

                                $record['full_name']    = $record['full_name'];
                                $record['email']        = $record['email'];
                                $record['phone']        = $record['phone'];
                                $record['age_group']    = $record['age_group'];
                                $record['gender']       = $record['gender'];
                                $record['affiliation']  = $record['affiliation'];
                                $record['qualification']= $record['qualification'];
                                $record['country']      = $record['country'];
                                $record['district']     = $record['district'];

                                $record['subcounty']    = $record['subcounty'] ?? null;
                                $record['parish']       = $record['parish'] ?? null;
                                $record['village']      = $record['village'] ?? null;
                                $record['business']     = $record['business'] ?? null;

                                $datas[] = $record;
                            }
                        }
                        fclose($handle);
                    }

                    // check if the file has content or if the major columns are not empty
                    if(count($datas) == 0) {  
                        File::delete($directory . $file_name);                       
                        return redirect()->back()->withErrors("File might be empty"); //most likely empty file
                    }

                    $insert = array();
                    foreach( $datas as $data ){
                        if (strlen($data['full_name']) < 3) {
                            File::delete($directory . $file_name);                       
                            return redirect()->back()->withErrors('Full name is required. Please fill it and upload again');
                        }
                        if (ELearningStudent::where('email', $data['email'])->first()) {
                            File::delete($directory . $file_name);                       
                            return redirect()->back()->withErrors('"'.$data['email'].'" already taken. Please change it and upload again');
                        }
                        
                        if (strlen($data['phone']) != 12 || !checkPhoneValidity($data['phone'])) {
                            File::delete($directory . $file_name);                       
                            return redirect()->back()->withErrors('"'.$data['phone'].'" must be 12 digits starting with 256. Please update and upload again');
                        }

                        if (ELearningStudent::where('phone_number', $data['phone'])->first()) {
                            File::delete($directory . $file_name);                       
                            return redirect()->back()->withErrors('"'.$data['phone'].'" already taken by another student. Please change it and upload again');
                        }

                        if (!in_array($data['country'], ELearningInstructor::countries, TRUE)) {
                            File::delete($directory . $file_name);                       
                            return redirect()->back()->withErrors('"'.$data['country'].'" does not exist in countries. Please add it to the system and upload again');
                        }
                        if (!in_array($data['age_group'], ELearningInstructor::age_group, TRUE)) {
                            File::delete($directory . $file_name);                       
                            return redirect()->back()->withErrors('"'.$data['age_group'].'" does not exist in age group. Please add it to the system and upload again');
                        }
                        if (!in_array($data['gender'], ELearningInstructor::gender, TRUE)) {
                            File::delete($directory . $file_name);                       
                            return redirect()->back()->withErrors('"'.$data['gender'].'" does not exist in gender. Please add it to the system and upload again');
                        }
                        if (!in_array($data['affiliation'], ELearningInstructor::affiliation, TRUE)) {
                            File::delete($directory . $file_name);                       
                            return redirect()->back()->withErrors('"'.$data['affiliation'].'" does not exist in affiliation. Please add it to the system and upload again');
                        }
                        if (!in_array($data['qualification'], ELearningInstructor::qualification, TRUE)) {
                            File::delete($directory . $file_name);                       
                            return redirect()->back()->withErrors('"'.$data['qualification'].'" does not exist in qualification. Please add it to the system and upload again');
                        }


                        if (! $district = District::where('name', trim($data['district']))->first()) {
                            File::delete($directory . $file_name);                       
                            return redirect()->back()->withErrors('"'.$data['district'].'" does not exist in districts. Please add it to the system and upload again');
                        }

                        if (isset($data['subcounty']) && !is_null($data['subcounty']) && strlen($data['subcounty']) > 0) {
                            if (! $subcounty = Subcounty::where('name', trim($data['subcounty']))->first()) {
                                File::delete($directory . $file_name);                       
                                return redirect()->back()->withErrors('"'.$data['subcounty'].'" does not exist in subcounties. Please add it to the system and upload again');
                            }
                            elseif (! $subcounty = Subcounty::where('name', trim($data['subcounty']))->where('district_id', $district->id)->first()) {
                                File::delete($directory . $file_name);                       
                                return redirect()->back()->withErrors($district->name.' doesnt have such subcounty "'.$data['subcounty'].'". Please add it to the system and upload again');
                            }
                        }
                        if (isset($data['parish']) && !is_null($data['parish']) && strlen($data['parish']) > 0) {
                            if (!isset($data['subcounty']) || is_null($data['subcounty']) || strlen($data['subcounty']) == 0) {
                                File::delete($directory . $file_name);                       
                                return redirect()->back()->withErrors('Subcounty id required for "'.$data['parish'].' parish". Please add it  and upload again');
                            }
                            elseif (! $parish = Parish::where('name', trim($data['parish']))->first()) {
                                File::delete($directory . $file_name);                       
                                return redirect()->back()->withErrors('"'.$data['parish'].'" does not exist in parishes. Please add it to the system and upload again');
                            }
                            elseif (! $parish = Parish::where('name', trim($data['parish']))->where('subcounty_id', $subcounty->id)->first()) {
                                File::delete($directory . $file_name);                       
                                return redirect()->back()->withErrors($subcounty->name.' doesnt have such parish "'.$data['parish'].'". Please add it to the system and upload again');
                            }
                        }

                        $insert[] = [
                            'name'      => $data['full_name'],
                            'email'     => $data['email'],
                            
                            'full_name'     => $data['full_name'],
                            'gender'        => $data['gender'],
                            'age_group'     => $data['age_group'],
                            'affiliation'   => $data['affiliation'],
                            'qualification' => $data['qualification'],
                            'phone_number'  => $data['phone'],
                            'country'       => $data['country'],
                            'district_id'   => $district->id,

                            'subcounty_id'  => $subcounty->id ?? null,
                            'parish_id'     => $parish->id ?? null,
                            'village'       => $data['village'] ?? null,
                            'business'      => $data['business'] ?? null,
                            'added_by'      => $request->added_by
                        ];
                    }
                    
                    foreach ($insert as $data) {
                        $this->create_student($data);
                    }

                    File::delete($directory . $file_name);
                    return redirect()->route('e-learning.students.index')->with('success', 'Students have been uploaded successfully');
                }
            }

            return back()->withErrors('File not found, upload failed');          
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors("Operation failed: ".$e->getMessage());
        }
    }

}
