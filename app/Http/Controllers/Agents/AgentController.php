<?php
    
namespace App\Http\Controllers\Agents;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

use App\Models\Organisations\Organisation;
use App\Models\Settings\Language;
use App\Models\Settings\Country;
use App\Models\Settings\Enterprise;
use App\Models\Settings\Location;
use App\Models\Agents\Agent;
    
class AgentController extends Controller
{
    public $_permission    = "village_agents";
    public $_route         = "village-agents.agents";
    public $_dir           = "agents.agents";
    public $_menu_group    = "Agents";
    public $_page_title    = 'Agents';

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
            $agents = Agent::pluck('name', 'id')->all();
            $organisations = Organisation::pluck('name', 'id')->all();
            $locations = Location::pluck('name', 'id')->all();
            $countries = Country::pluck('name', 'id')->all();

            return view($this->_dir.'.create', compact('organisations', 'locations', 'countries', 'agents'));
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
            'email' => 'nullable|email|unique:agents,email',
            'password' => 'required',
            'country_id' => 'required',
            'telephone' => 'required',
            'organisation_id' => 'required_if:category,==,village',
            'is_mm_phone' => 'required',
            'mm_telephone' => 'required_if:is_mm_phone,==,No',            
        ]);

        try {    

            $country = Country::find($request->country_id);

            $data =[
                "country_id" => $request->country_id,
                "organisation_id" => $request->organisation_id,
                "agent_id" => $request->agent_id,
                "name" => $request->first_name.' '.$request->last_name,
                "gender" => $request->gender,
                "national_id_number" => $request->national_id_number,
                "location_id" => $request->location_id,
                "status" => $request->status,
                "email" => $request->email,
                "password" => $request->password,
                'phone' => $country->dialing_code.$request->telephone,            
                'mm_phone' => !is_null($request->mm_telephone) ? $country->dialing_code.$request->mm_telephone : null,
                'user_id' => auth()->user()->id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'category' => $request->category
            ];

            $village = Agent::create($data);

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
    public function edit(Agent $agent)
    {    
        try {       
            $agents = Agent::pluck('name', 'id')->all();
            $organisations = Organisation::pluck('name', 'id')->all();
            $locations = Location::pluck('name', 'id')->all();
            $countries = Country::pluck('name', 'id')->all();

            return view($this->_dir.'.edit', compact('organisations', 'locations', 'countries', 'agents', 'agent'));
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
            $data = Agent::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ]);
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('bio', function($data) {
                    $bio = '<span class="text-muted">Name: </span>'.$data->name;
                    $bio .=  '<br><span class="text-muted">Gender: </span>'.$data->gender;
                    $bio .=  '<br><span class="badge rounded-pill badge-outline-primary">'.$data->category.'</span>';
                    return $bio;
                  })
                ->addColumn('address', function($data) {
                    $address = '<span class="text-muted">Location: </span>'.$data->location->name.'<br>';
                    $address .= '<span class="text-muted">Country: </span>'.$data->country->name; 
                    return $address;
                  })
                ->addColumn('profile', function($data) {
                    $profile = '<span class="text-muted">NIN: </span>'.$data->national_id_number;
                    return $profile;
                  })
                ->addColumn('contact', function($data) {
                    $contact = '<span class="text-muted">Phone: </span>'.$data->phone.'<br>';
                    if(!is_null($data->mm_phone)) $contact .= '<span class="text-muted">MM Phone: </span>'.$data->mm_phone;
                    if(is_null($data->mm_phone)) $contact .= '(MM Registered)';
                    return $contact;
                  })
                ->addColumn('supervision', function($data) {
                    $group = '';
                    if(isset($data->organisation)) $group .= '<span class="text-muted">Organisation: </span>'.$data->organisation->name.'<br>';
                    if(isset($data->group)) $group .= '<span class="text-muted">Supervisor: </span>'.$data->agent_id;
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
                ->rawColumns(['action','bio','address', 'profile','contact','supervision'])
                ->make(true);
        }
    }


    public function getAgentsByOrganisation($organisation_id){

        $agents = Agent::select('id', 'name')->where('organisation_id', $organisation_id)->get();

        return response()->json(['items' => $agents]);

    }
}