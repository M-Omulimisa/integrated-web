<?php
    
namespace App\Http\Controllers\Farmers;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\User;
use Spatie\Permission\Models\Users\Role;

use App\Models\Organisations\Organisation;
use App\Models\Settings\Language;
use App\Models\Settings\Country;
use App\Models\Settings\Enterprise;
use App\Models\Settings\Location;
use App\Models\Settings\Setting;

use App\Models\Farmers\Farmer;
use App\Models\Farmers\FarmerGroup;
use App\Models\Farmers\FarmerEnterprise;
use App\Models\Agents\Agent;
    
class FarmerController extends Controller
{
    public $_permission    = "farmers";
    public $_route         = "farmers.farmers";
    public $_dir           = "farmers.farmers";
    public $_menu_group    = "Farmers";
    public $_page_title    = 'Farmers';

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
            $organisations = Organisation::select('id', 'name')->get();
            $languages = Language::select('id', 'name') ->get();
            $enterprises = Enterprise::get();
            $locations = Location::pluck('name', 'id')->all();
            $countries = Country::select('id', 'name')->get();
            $agents = Agent::pluck('name', 'id')->all();
            $groups = FarmerGroup::select('id', 'name')->get();
            $education_levels = Setting::EDUCATION;

            return view($this->_dir.'.create', compact('organisations', 'languages', 'enterprises', 'locations', 'education_levels', 'countries', 'agents', 'groups'));
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
            'language_id' => 'required',
            'gender' => 'required',
            'year_of_birth' => 'required',
            'password' => 'required',
        ]);

        try {            
        $data = [
            "country_id" => $request->country_id,
            "organisation_id" => $request->organisation_id,
            "language_id" => $request->language_id,
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "email" => $request->email,
            "gender" => $request->gender,
            "year_of_birth" => $request->year_of_birth,
            "national_id_number" => $request->national_id_number,
            "education_level" => $request->education_level, 
            "phone" => $request->phone,        
            "is_your_phone" => $request->is_your_phone,
            "is_mm_registered" => $request->is_mm_registered,
            "farming_scale" => $request->farming_scale,
            "other_economic_activity" => $request->other_economic_activity,
            "land_holding_in_acres" => $request->land_holding_in_acres,
            "land_under_farming_in_acres" => $request->land_under_farming_in_acres,
            "ever_bought_insurance" => $request->ever_bought_insurance,
            "ever_received_credit" => $request->ever_received_credit,
            "location_id" => $request->location_id,
            "longitude" => $request->longitude,
            "latitude" => $request->latitude,
            "status" => $request->status,
            "password" => $request->password,
            "farmer_group_id" => $request->farmer_group_id,
            'created_by_user_id' => auth()->user()->id,
            'agent_id' => $request->agent_id
        ];



        $farmer = Farmer::create($data);

            if ($farmer && $request->has('activities') && count($request->activities) > 0) {
                for ($i=0; $i < count($request->activities); ++$i) {
                      FarmerEnterprise::create([
                        'farmer_id' => $farmer->id,
                        'enterprise_id' => $request->activities[$i]
                      ]);
                  }                
            }

            return redirect()->route($this->_route.'.index')
                            ->with('success','Operation was successfully');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
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
    public function edit(Farmer $farmer)
    {    
        try {            
            $organisations = Organisation::pluck('name', 'id')->all();
            $languages = Language::pluck('name', 'id')->all();
            $enterprises = Enterprise::get();
            $locations = Location::pluck('name', 'id')->all();
            $countries = Country::pluck('name', 'id')->all();
            $education_levels = Setting::EDUCATION;

            return view($this->_dir.'.edit', compact('organisations', 'languages', 'enterprises', 'locations', 'education_levels', 'countries', 'farmer'));
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
            $data = Farmer::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ]);
            $datatables = app('datatables')->of($data);

            \Log::error(['xxxxxxxxxxxxxxxxxxxxxxxxx' => $request->all()]);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('id', function($data) {
                    return $data->id;
                  })
                ->addColumn('bio', function($data) {
                    $bio = '<span class="text-muted">Name: </span>'.$data->first_name.' '.$data->last_name.'<br>';
                    $bio .=  '<span class="text-muted">Genger: </span>'.$data->gender.'<br>';
                    $bio .=  '<span class="text-muted">YOB: </span>'.$data->year_of_birth;
                    return $bio;
                  })
                ->addColumn('address', function($data) {
                    $address = '<span class="text-muted">: </span>'.$data->location->name.'<br>';
                    $address .= '<span class="text-muted">: </span>'.$data->country->name; 
                    return $address;
                  })
                ->addColumn('profile', function($data) {
                    $profile = '<span class="text-muted">Language: </span>'.$data->language->name.'<br>';
                    $profile .= '<span class="text-muted">NIN: </span>'.$data->national_id_number.'<br>'; 
                    $profile .= '<span class="text-muted">Education: </span>'.$data->education_level; 
                    return $profile;
                  })
                ->addColumn('activities', function($data) {
                    $list = '';
                    if (isset($data->enterprises) && count($data->enterprises) > 0) {
                        foreach ($data->enterprises as $enterprise) {
                            $list .= $enterprise->enterprise->name.'<br>';
                        }
                    }
                    return $list;
                  })
                ->addColumn('contact', function($data) {
                    $contact = '<span class="text-muted">Phone: </span>'.$data->phone.'<br>';
                    $contact .= '<span class="text-muted">Owner?: </span>'.($data->is_your_phone ? 'Yes' : 'No').'<br>'; 
                    $contact .= '<span class="text-muted">MM: </span>'.($data->is_mm_registered ? 'Yes' : 'No'); 
                    return $contact;
                  })
                ->addColumn('grouping', function($data) {
                    $group = '';
                    if(isset($data->organisation)) $group .= '<span class="text-muted">Organisation: </span>'.$data->organisation->name.'<br>';
                    if(isset($data->group)) $group .= '<span class="text-muted">Group: </span>'.$data->group->name;
                    if(isset($data->agent_id)) $group .= '<span class="text-muted">Agent: </span>'.$data->managed_by->name;
                    return $group;
                  })
                ->addColumn('created', function($data) {
                    $by = '';
                    if (!is_null($data->created_by_user_id)) {
                        $by = $data->added_by_user->name ?? '';
                    }
                    if (!is_null($data->created_by_agent_id)) {
                        $by = $data->added_by_agent->name ?? '';
                    }
                    return $data->created_at.'<br>'.$by;
                  })
                ->addColumn('action', function($data){
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = 'manage_'.$this->_permission;
                    // $delete  = 'delete_'.$this->_permission;
                    // $view    = 'view_'.$this->_permission;
                    return view('partials.actions', compact('route','id','manage'))->render();
                })
                ->rawColumns(['action', 'bio', 'address', 'profile', 'activities', 'contact', 'grouping', 'created'])
                ->make(true);
        }
    }

}