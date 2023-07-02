<?php
    
namespace App\Http\Controllers\Extension;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\User;
use App\Models\Users\Role;

use App\Models\Organisations\Organisation;
use App\Models\Settings\Language;
use App\Models\Settings\Country;
use App\Models\Settings\Enterprise;
use App\Models\Settings\Location;
use App\Models\Settings\Setting;
use App\Models\Extension\ExtensionOfficer;
use App\Models\Extension\ExtensionOfficerPosition;
use App\Models\Extension\ExtensionOfficerLanguage;
    
class ExtensionOfficerController extends Controller
{
    public $_permission    = "extension_officers";
    public $_route         = "extension-officers.officers";
    public $_dir           = "extension.officers";
    public $_menu_group    = "Extension";
    public $_page_title    = 'Extenstion Officers';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {            
            return view($this->_dir.'.index');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {   
            $extension_officers = ExtensionOfficer::pluck('name', 'id')->all();
            $positions = ExtensionOfficerPosition::pluck('name', 'id')->all();
            $organisations = Organisation::pluck('name', 'id')->all();
            $languages = Language::get();

            $locations = Location::pluck('name', 'id')->all();
            $countries = Country::pluck('name', 'id')->all();
            $education_levels = Setting::EDUCATION; 

            return view($this->_dir.'.create', compact('extension_officers', 'positions', 'organisations', 'languages', 'locations', 'countries', 'education_levels'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'nullable|email|unique:extension_officers,email',
            'password' => 'required',
            'country_id' => 'required',
            'gender'=>'required',
            'education_level' => 'required',
            'position_id' => 'required'
        ]);

        try {  

            $data = [
                "organisation_id" => $request->organisation_id,
                "extension_officer_id" => $request->extension_officer_id,
                "position_id" => $request->position_id,
                'name' => $request->first_name.' '.$request->last_name,
                "gender" => $request->gender,
                'phone' => $request->phone,
                'email' => $request->email,
                "category" => $request->category,
                "education_level" => $request->education_level,
                "location_id" => $request->location_id,
                'country_id' => $request->country_id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'created_by' => auth()->user()->id,
                'password' => $request->password, 
                'status' => $request->status,
            ]; 

            $officer = ExtensionOfficer::create($data);

            if ($officer && $request->has('languages') && count($request->languages) > 0) {
                for ($i=0; $i < count($request->languages); ++$i) {
                      ExtensionOfficerLanguage::create([
                        'extension_officer_id' => $officer->id,
                        'language_id' => $request->languages[$i]
                      ]);
                  }                
            }

            return redirect()->route($this->_route.'.index')
                            ->with('success','Operation successfully');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        }    
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {        
            $data = [];
            return view($this->_dir.'.show', compact('data', 'id'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ExtensionOfficer $officer)
    {    
        try { $extension_officers = ExtensionOfficer::pluck('name', 'id')->all();
            $positions = ExtensionOfficerPosition::pluck('name', 'id')->all();
            $organisations = Organisation::pluck('name', 'id')->all();
            $languages = Language::get();

            $locations = Location::pluck('name', 'id')->all();
            $countries = Country::pluck('name', 'id')->all();
            $education_levels = Setting::EDUCATION; 

            return view($this->_dir.'.edit', compact('extension_officers', 'positions', 'organisations', 'languages', 'locations', 'countries', 'education_levels', 'officer'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);
    
        try {            
            return redirect()->route($this->_route.'.index')
                            ->with('success','Operation was successful');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {            
            return redirect()->route($this->_route.'.index')
                            ->with('success','Operation was successful');                        
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Load records into a DataTable.
     *
     * @param  DataTable Obj
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {

            DB::statement(DB::raw('set @DT_RowIndex=0'));
            $data = ExtensionOfficer::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ])->orderBy('name', 'ASC');
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('id', function($data) {
                    return $data->id;
                  })
                ->addColumn('bio', function($data) {
                    $bio = '<span class="text-muted">Name: </span>'.$data->name.'<br>';
                    $bio .=  '<span class="text-muted">Genger: </span>'.$data->gender;
                    return $bio;
                  })
                ->addColumn('address', function($data) {
                    $address = '<span class="text-muted">: </span>'.$data->location->name.'<br>';
                    $address .= '<span class="text-muted">: </span>'.$data->country->name; 
                    return $address;
                  })
                ->addColumn('profile', function($data) {
                    $profile = '<span class="text-muted">Category: </span>'.$data->category.'<br>'; 
                    if(isset($data->position_id)) $profile .= '<span class="text-muted">Position: </span>'.$data->position->name.'<br>'; 
                    $profile .= '<span class="text-muted">Education: </span>'.$data->education_level;
                    return $profile;
                  })
                ->addColumn('languages', function($data) {
                    $list = '';
                    if (isset($data->languages) && count($data->languages) > 0) {
                        foreach ($data->languages as $language) {
                            $list .= $language->language->name.'<br>';
                        }
                    }
                    return $list;
                  })
                ->addColumn('contact', function($data) {
                    $contact = '<span class="text-muted">Phone: </span>'.$data->phone.'<br>';
                    $contact .= '<span class="text-muted">Email: </span>'.$data->email;
                    return $contact;
                  })
                ->addColumn('grouping', function($data) {
                    $group = '';
                    if(isset($data->organisation)) $group .= '<span class="text-muted">Organisation: </span>'.$data->organisation->name.'<br>';
                    if(isset($data->extension_officer_id)) $group .= '<span class="text-muted">Supervisor: </span>'.$data->supervisor->name;
                    return $group;
                  })
                ->addColumn('created', function($data) {
                    return $data->created_at;
                  })
                ->addColumn('action', function($data){
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = 'manage_'.$this->_permission;
                    // $delete  = 'delete_'.$this->_permission;
                    // $view    = 'view_'.$this->_permission;
                    return view('partials.actions', compact('route','id','manage'))->render();
                })
                ->rawColumns(['action', 'bio', 'address', 'profile', 'contact', 'grouping', 'languages'])
                ->make(true);
        }
    }
}