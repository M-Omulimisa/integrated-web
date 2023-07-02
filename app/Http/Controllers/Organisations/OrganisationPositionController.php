<?php
    
namespace App\Http\Controllers\Organisations;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\Organisations\OrganisationPosition;
use App\Models\Organisations\OrganisationPermission;
use App\Models\Organisations\Organisation;
use App\Models\Settings\CountryAdminUnit;
use App\Models\Organisations\OrganisationPositionPermission;
    
class OrganisationPositionController extends Controller
{ 
    public $_permission    = "organisation_settings";
    public $_route         = "organisations.positions";
    public $_dir           = "organisations.positions";
    public $_menu_group    = "Organisations";
    public $_page_title    = 'Organisation Positions';

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
            $areas = CountryAdminUnit::pluck('name', 'id')->all();
            $permissions = OrganisationPermission::pluck('name', 'id')->all();

            return view($this->_dir.'.create', compact('organisations', 'areas', 'permissions'));
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
            'organisation_id' => 'required',
            'permissions' => 'required'

        ]);

        try {  
        
            $position = OrganisationPosition::create([
                'organisation_id' => $request->organisation_id,
                'name' => $request->name
            ]);

            if ($position) {
                for ($i=0; $i < count($request->permissions); ++$i) {
                      OrganisationPositionPermission::create([
                        'position_id' => $position->id,
                        'permission_id' => $request->permissions[$i]
                      ]);
                  }                
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
     * @param  \App\OrganisationPosition  $position
     * @return \Illuminate\Http\Response
     */
    public function show(OrganisationPosition $position)
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
     * @param  \App\OrganisationPosition  $position
     * @return \Illuminate\Http\Response
     */
    public function edit(OrganisationPosition $position)
    {
        try {            
            $organisations = Organisation::pluck('name', 'id')->all();
            $areas = CountryAdminUnit::pluck('name', 'id')->all();
            $permissions = OrganisationPermission::pluck('name', 'id')->all();

            return view($this->_dir.'.edit', compact('position', 'organisations', 'areas', 'permissions'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrganisationPosition  $position
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrganisationPosition $position)
    {
         request()->validate([
            'name' => 'required',
            'organisation_id' => 'required',
            'permissions' => 'required'
        ]);

        try {            
        
            $position->update([
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
     * @param  \App\OrganisationPosition  $position
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrganisationPosition $position)
    {
        try {            
            $position->delete();
        
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
            $data = OrganisationPosition::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ])->orderBy('name', 'ASC');
            $datatables = app('datatables')->of($data);

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
                ->addColumn('permissions', function($data) {
                    return count($data->permissions());
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