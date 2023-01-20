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
use App\Models\Settings\FarmingPractice;
use App\Models\Settings\FarmingChallenge;
use App\Models\Settings\Location;
use App\Models\Settings\Setting;
use App\Models\Farmers\FarmerGroup;
use App\Models\Farmers\FarmerGroupEnterprise;
use App\Models\Farmers\Farmer;
use App\Models\Agents\Agent;
    
class FarmerGroupController extends Controller
{
    public $_permission    = "farmer_groups";
    public $_route         = "farmers.groups";
    public $_dir           = "farmers.groups";
    public $_menu_group    = "Farmers";
    public $_page_title    = 'Farmer groups';

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
            $organisations = Organisation::pluck('name', 'id')->all();
            $enterprises = Enterprise::get();
            $locations = Location::pluck('name', 'id')->all();
            $countries = Country::pluck('name', 'id')->all();
            $meeting_days = Setting::MEETING_DAYS;
            $groupCode = $this->generateCode();
            $meeting_frequencies = Setting::MEETING_FREQUENCY;
            $agents = Agent::pluck('name', 'id')->all();

            return view($this->_dir.'.create', compact('organisations', 'meeting_days', 'enterprises', 'groupCode', 'locations', 'meeting_frequencies', 'countries', 'agents'));
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
            'name' => 'required',
            'code' => 'required',
            'organisation_id' => 'nullable|exists:organisations,id',
            'group_leader_contact' => 'required',
            'establishment_year' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'location_id' => 'required',
            'country_id' => 'required',
            'enterprises' => 'required',
            'last_cycle_savings' => 'required'
        ]);

        try {            
            $meeting_days = '';

            if ($request->has('meeting_days')) {
                for ($i=0; $i < count($request->meeting_days); ++$i) {
                      $meeting_days .= $request->meeting_days[$i];
                  }                
            }

            $group = FarmerGroup::create([
                'name' => $request->name,
                'country_id' => $request->country_id,
                'organisation_id' => $request->organisation_id,
                'code' => $request->code,
                'group_leader' => $request->first_name.' '.$request->last_name,
                'group_leader_contact' => $request->group_leader_contact,
                'establishment_year' => $request->establishment_year,            
                'meeting_venue' => $request->meeting_venue,            
                'meeting_days' => strlen($meeting_days)!=0 ? $meeting_days : null,
                'meeting_time' => $request->meeting_time,
                'meeting_frequency' => $request->meeting_frequency,
                'location_id' => $request->location_id,
                'last_cycle_savings' => $request->last_cycle_savings,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'created_by_user_id' => auth()->user()->id,
                'agent_id' => $request->agent_id
            ]);

            if ($group && $request->has('enterprises') && count($request->enterprises) > 0) {
                for ($i=0; $i < count($request->enterprises); ++$i) {
                      FarmerGroupEnterprise::create([
                        'farmer_group_id' => $group->id,
                        'enterprise_id' => $request->enterprises[$i]
                      ]);
                  }                
            }
            return redirect()->route($this->_route.'.index')
                            ->with('success','Operation was successfully');
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
            $group = FarmerGroup::find($id);
            return view($this->_dir.'.show', compact('group'));
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
    public function edit($id)
    {    
        try {  
            $group = FarmerGroup::findOrFail($id);
            $organisations = Organisation::pluck('name', 'id')->all();
            $enterprises = Enterprise::get();
            $locations = Location::pluck('name', 'id')->all();
            $countries = Country::pluck('name', 'id')->all();
            $meeting_days = Setting::MEETING_DAYS;
            $groupCode = $this->generateCode();
            $meeting_frequencies = Setting::MEETING_FREQUENCY;

            return view($this->_dir.'.edit', compact('group', 'organisations', 'meeting_days', 'enterprises', 'groupCode', 'locations', 'meeting_frequencies', 'countries', 'group'));
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
            $data = FarmerGroup::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ])->orderBy('name', 'ASC');
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('id', function($data) {
                    return $data->id;
                  })
                ->addColumn('profile', function($data) {
                    $bio = '<span class="text-muted">Name: </span>'.$data->name.'<br>';
                    $bio .=  '<span class="text-muted">Code: </span>'.$data->code.'<br>';
                    $bio .=  '<span class="text-muted">Year: </span>'.$data->establishment_year;
                    return $bio;
                  })
                ->addColumn('address', function($data) {
                    $address = '<span class="text-muted">: </span>'.$data->location->name.'<br>';
                    $address .= '<span class="text-muted">Country: </span>'.$data->country->name; 
                    return $address;
                  })
                ->addColumn('leadership', function($data) {
                    $address = '<span class="text-muted">Leader: </span>'.$data->group_leader.'<br>';
                    $address .= '<span class="text-muted">Contact: </span>'.$data->group_leader_contact; 
                    return $address;
                  })
                ->addColumn('membership', function($data) {
                    $address = '<span class="text-muted">Males: '.count($data->farmers->where('gender', 'Male')).'</span><br>';
                    $address .= '<span class="text-muted">Females: '.count($data->farmers->where('gender', 'Female')).'</span><br>';
                    $address .= '<span class="text-muted">Total: '.count($data->farmers).' </span>'; 
                    return $address;
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
                ->addColumn('created', function($data) {
                    $by = '';
                    if (!is_null($data->user_id)) {
                        $by = $data->added_by_user->name ?? '';
                    }
                    if (!is_null($data->agent_id)) {
                        $by = $data->added_by_agent->name ?? '';
                    }
                    return $data->created_at.'<br>'.$by;
                  })
                ->addColumn('action', function($data){
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = 'manage_'.$this->_permission;
                    // $delete  = 'delete_'.$this->_permission;
                    $view    = 'list_'.$this->_permission;
                    return view('partials.actions', compact('route','id','manage', 'view'))->render();
                })
                ->rawColumns(['action', 'profile', 'address', 'leadership', 'membership','activities','created' ])
                ->make(true);
        }
    }

    public function generateCode()
    {
        $excodes = new FarmerGroup;
        do
          {
            $code = sprintf('%05d', mt_rand(10000, 99999));
          }

        while (!is_null($excodes->where('code', '=', $code)->first()));
        return $code;
    }

    public function addFarmers()
    {
        try { 
            $groups = FarmerGroup::pluck('name', 'id')->all();
            $farmers = Farmer::whereNull('farmer_group_id')->get();           
            return view($this->_dir.'.create_multiple', compact('farmers', 'groups'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }

    public function newGroupFarmer($group_id)
    {
        try { 
            $organisations = Organisation::pluck('name', 'id')->all();
            $languages = Language::pluck('name', 'id')->all();
            $enterprises = Enterprise::get();
            $locations = Location::pluck('name', 'id')->all();
            $countries = Country::pluck('name', 'id')->all();
            $agents = Agent::pluck('name', 'id')->all();
            $education_levels = Setting::EDUCATION;

            $group = FarmerGroup::findOrFail($group_id);

            return view('farmers.group_farmers.create', compact('organisations', 'languages', 'enterprises', 'locations', 'education_levels', 'countries', 'agents', 'group'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }

    public function getGroupsByOrganisaton($organisation_id){

        $farmer_groups =  FarmerGroup::select('id', 'name')->where('organisation_id', $organisation_id)->get();

        return response()->json(['items' => $farmer_groups]);

    }
}