<?php
    
namespace App\Http\Controllers\Extension;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\Extension\ExtensionOfficerPosition;
use App\Models\Organisations\Organisation;
use App\Models\Settings\CountryAdminUnit;
use App\Models\Settings\Location;
use App\Models\Extension\ExtensionOfficerPositionLocation;
    
class ExtensionOfficerPositionController extends Controller
{
    public $_permission    = "extension_officer_positions";
    public $_route         = "extension-officers.positions";
    public $_dir           = "extension.positions";
    public $_menu_group    = "Settings";
    public $_page_title    = 'ExtensionOfficerPositions';

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
        $organisations = Organisation::pluck('name', 'id')->all();
        $admin_levels = CountryAdminUnit::pluck('name', 'id')->all();
        $locations = Location::pluck('name', 'id')->all();
        return view($this->_dir.'.create', compact('organisations', 'admin_levels', 'locations'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {            
            request()->validate([
                'organisation_id' => 'required',
                'admin_level' => 'nullable|exists:country_admin_units,id',
                'name' => 'required',
            ]);
        
            $position = ExtensionOfficerPosition::create($request->all());

            if ($position) {
                for ($i=0; $i < count($request->locations); ++$i) {
                      ExtensionOfficerPositionLocation::create([
                        'position_id' => $position->id,
                        'location_id' => $request->locations[$i]
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
     * @param  \App\ExtensionOfficerPosition  $position
     * @return \Illuminate\Http\Response
     */
    public function show(ExtensionOfficerPosition $position)
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
     * @param  \App\ExtensionOfficerPosition  $position
     * @return \Illuminate\Http\Response
     */
    public function edit(ExtensionOfficerPosition $position)
    {
        try {  
            $organisations = Organisation::pluck('name', 'id')->all();         
            return view($this->_dir.'.edit',compact('position', 'organisations'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ExtensionOfficerPosition  $position
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExtensionOfficerPosition $position)
    {
        try {            
             request()->validate([
                'name' => 'required',
                'admin_level' => 'nullable|exists:country_admin_units,id',
                'organisation_id' => 'required',
            ]);
        
            $position->update($request->all());
        
            return redirect()->route($this->_route.'.index')
                            ->with('success','Operation was successfully');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ExtensionOfficerPosition  $position
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExtensionOfficerPosition $position)
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
            $data = ExtensionOfficerPosition::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ])->orderBy('name', 'ASC');
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
                ->addColumn('level', function($data) {
                    return is_null($data->admin_level) ? 'ALL' : ($data->administration_level->name ?? null);
                  })
                ->addColumn('locations', function($data) {
                    return count($data->locations);
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