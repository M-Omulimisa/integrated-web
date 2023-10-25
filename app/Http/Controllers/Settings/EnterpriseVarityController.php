<?php
    
namespace App\Http\Controllers\Settings;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\Settings\EnterpriseVariety;
use App\Models\Settings\Enterprise;
    
class EnterpriseVarityController extends Controller
{ 
    public $_permission    = "settings";
    public $_route         = "settings.enterprise-varieties";
    public $_dir           = "settings.enterprise_varieties";
    public $_menu_group    = "Settings";
    public $_page_title    = 'Enterprise Varieties';

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
        $enterprises = Enterprise::pluck('name', 'id')->all();
        return view($this->_dir.'.create', compact('enterprises'));
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
        
            EnterpriseVariety::create($request->all());
        
            return redirect()->route($this->_route.'.index')
                            ->with('success','Operation was successfully.');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\EnterpriseVariety  $variety
     * @return \Illuminate\Http\Response
     */
    public function show(EnterpriseVariety $variety)
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
     * @param  \App\EnterpriseVariety  $variety
     * @return \Illuminate\Http\Response
     */
    public function edit(EnterpriseVariety $variety)
    {
        try {  
            $enterprises = Enterprise::pluck('name', 'id')->all();          
            return view($this->_dir.'.edit',compact('variety', 'enterprises'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EnterpriseVariety  $variety
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EnterpriseVariety $variety)
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
     * @param  \App\EnterpriseVariety  $variety
     * @return \Illuminate\Http\Response
     */
    public function destroy(EnterpriseVariety $variety)
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
            $data = EnterpriseVariety::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ]);
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('id', function($data) {
                    return $data->id;
                  })
                ->addColumn('enterprise', function($data) {
                    return $data->enterprise->name;
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