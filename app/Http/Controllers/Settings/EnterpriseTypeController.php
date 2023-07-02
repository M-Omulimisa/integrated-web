<?php
    
namespace App\Http\Controllers\Settings;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\Settings\Enterprise;
use App\Models\Settings\EnterpriseType;
use App\Models\Settings\EnterpriseVariety;
    
class EnterpriseTypeController extends Controller
{ 
    public $_permission    = "settings";
    public $_route         = "settings.enterprise-types";
    public $_dir           = "settings.enterprise_types";
    public $_menu_group    = "Settings";
    public $_page_title    = 'Enterprise Types';

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
        $enterprise_varieties = EnterpriseVariety::pluck('name', 'id')->all();
        $enterprises = Enterprise::pluck('name', 'id')->all();
        return view($this->_dir.'.create', compact('enterprises', 'enterprise_varieties'));
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
                'name' => 'required',
                'enterprise_id' => 'required',
            ]);
        
            EnterpriseType::create($request->all());
        
            return redirect()->route($this->_route.'.index')
                            ->with('success','Operation was successfully.');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\EnterpriseType  $variety
     * @return \Illuminate\Http\Response
     */
    public function show(EnterpriseType $variety)
    {
        try {            
            return view($this->_dir.'.show',compact('variety'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EnterpriseType  $variety
     * @return \Illuminate\Http\Response
     */
    public function edit(EnterpriseType $variety)
    {
        try {  
            $enterprise_varieties = EnterpriseVariety::pluck('name', 'id')->all();
            $enterprises = Enterprise::pluck('name', 'id')->all();         
            return view($this->_dir.'.edit',compact('variety', 'enterprises', 'enterprise_varieties'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EnterpriseType  $variety
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EnterpriseType $variety)
    {
        try {            
             request()->validate([
                'name' => 'required',
                'enterprise_id' => 'required',
            ]);
        
            $variety->update($request->all());
        
            return redirect()->route($this->_route.'.index')
                            ->with('success','Operation was successfully');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EnterpriseType  $variety
     * @return \Illuminate\Http\Response
     */
    public function destroy(EnterpriseType $variety)
    {
        try {            
            $variety->delete();
        
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
            $data = EnterpriseType::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ]);
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('id', function($data) {
                    return $data->id;
                  })
                ->addColumn('variety', function($data) {
                    return $data->variety->name;
                  })
                ->addColumn('enterprise', function($data) {
                    return $data->variety->enterprise->name;
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