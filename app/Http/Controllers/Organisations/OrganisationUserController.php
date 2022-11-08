<?php
    
namespace App\Http\Controllers\Organisations;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\Organisations\OrganisationPosition;
use App\Models\Organisations\Organisation;
use App\Models\Organisations\OrganisationUserPosition;
use App\Models\Users\Role;
use App\Models\User;
use App\Models\Settings\Country;
    
class OrganisationUserController extends Controller
{ 
    public $_permission    = "organisation_users";
    public $_route         = "organisations.users";
    public $_dir           = "organisations.users";
    public $_menu_group    = "Organisations";
    public $_page_title    = 'Organisation Users';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:manage_'.$this->_permission, ['only' => ['create','store', 'edit', 'update']]);
         $this->middleware('permission:list_'.$this->_permission, ['only' => ['index','show']]);
         $this->middleware('permission:delete_'.$this->_permission, ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
            $positions = OrganisationPosition::pluck('name', 'id')->all();
            $dialing_codes = Country::pluck('dialing_code', 'dialing_code')->all();
            $organisation_user = Role::ROLE_ORG_ADMIN; 

            return view($this->_dir.'.create', compact('organisations', 'positions', 'organisation_user', 'dialing_codes'));
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
        request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'roles' => 'required',
            'dialing_code' => 'required',
            'telephone' => 'required',
            'status' => 'required',
            'organisation_id' => 'required|exists:organisations,id',
            'position_id' => 'required|exists:organisation_positions,id'
        ]);

        try {  


            $organisation = Organisation::find($request->organisation_id);
        
            $user = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->dialing_code.$request->telephone,
                    'organisation_id' => $request->organisation_id,
                    'password' => $request->password, 
                    'status' => $request->status == "1" ? "Active" : "Suspended",
                    'country_id' => $organisation->administrator()->country_id,
                    'created_by' => auth()->user()->id
                ];

                if ($user = User::create($user)) {
                    $user->assignRole(Role::ROLE_ORG_USER);

                    OrganisationUserPosition::create([
                        'position_id' => $request->position_id,
                        'user_id' => $user->id
                    ]);
                }
        
            return redirect()->route($this->_route.'.index')
                            ->with('success','Operation successfully.');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\User  $organisation_user
     * @return \Illuminate\Http\Response
     */
    public function show(User $organisation_user)
    {
        try {            
            return view($this->_dir.'.show',compact('position'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $organisation_user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $organisation_user)
    {
        try {            
            $organisations = Organisation::pluck('name', 'id')->all();
            $areas = CountryAdminUnit::pluck('name', 'id')->all();

            return view($this->_dir.'.edit', compact('position', 'organisations', 'areas', 'permissions'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $organisation_user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $organisation_user)
    {
         request()->validate([
            'name' => 'required',
            'organisation_id' => 'required',
            'permissions' => 'required'
        ]);

        try {            
        
            $organisation_user->update([
                'organisation_id' => $request->organisation_id,
                'name' => $request->name
            ]);

            return redirect()->route($this->_route.'.index')
                            ->with('success','Operation successfully');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $organisation_user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $organisation_user)
    {
        try {            
            $organisation_user->delete();
        
            return redirect()->route($this->_route.'.index')
                            ->with('success','Operation successfully');
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
            $data = User::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ])->orderBy('name', 'ASC');
            $datatables = app('datatables')->of($data);

            $role = Role::whereName(Role::ROLE_ORG_USER)->first();            
            $data->whereIn('id',function($query) use ($role){
                $query->select('model_id')->where('role_id', $role->id)->from('model_has_roles');
            });

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('id', function($data) {
                    return $data->id;
                  })
                ->addColumn('organisation', function($data) {
                    return $data->organisation->name;
                  })
                ->addColumn('position', function($data) {
                    return $data->position->position->name ?? null;
                  })
                ->addColumn('action', function($data){
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = 'manage_'.$this->_permission;
                    // $delete  = 'delete_'.$this->_permission;
                    // $view    = 'list_'.$this->_permission;

                    return view('partials.actions', compact('route','id','manage'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}