<?php
    
namespace App\Http\Controllers\InputLoan;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\Settings\Setting;
use App\Models\Settings\Enterprise;
use App\Models\Settings\EnterpriseVariety;
use App\Models\Loans\YieldEstimation;
use App\Models\Settings\MeasureUnit;
    
class YieldEstimationController extends Controller
{
    public $_permission    = "input-loans";
    public $_route         = "input-loans.yield-estimations";
    public $_dir           = "input_loans.yield_estimates";
    public $_menu_group    = "Input Loans";
    public $_page_title    = 'Yield Estimates';

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
            $enterprises = Enterprise::pluck('name', 'id')->all();
            $enterprise_varieties = EnterpriseVariety::pluck('name', 'id')->all();  
            $units = MeasureUnit::pluck('name','id')->all();       
            return view($this->_dir.'.create', compact('enterprises', 'enterprise_varieties', 'units'));
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
            'enterprise_variety_id' => 'required|exists:enterprise_varieties,id',
            'farm_size' => 'required',
            'farm_size_unit_id' => 'required',
            'input_estimate' => 'required|numeric',
            'input_unit_id' => 'required',
            'output_min_estimate' => 'required|numeric',
            'output_max_estimate' => 'required|numeric',
            'output_unit_id' => 'required',
        ]);

        try { 
            YieldEstimation::create($request->all());          
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
    public function edit($id)
    {    
        try {            
            $yield = YieldEstimation::findOrFail($id);
            $enterprises = Enterprise::pluck('name', 'id')->all();
            $enterprise_varieties = EnterpriseVariety::pluck('name', 'id')->all();  
            $units = MeasureUnit::pluck('name','id')->all(); 
            return view($this->_dir.'.edit', compact('yield', 'enterprise_varieties', 'enterprises', 'units'));
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
            $data = YieldEstimation::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ]);
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('enterprise', function($data) {
                    return $data->variety->name.' : '.$data->variety->enterprise->name;
                  })
                ->addColumn('farm', function($data) {
                    return $data->farm_size.' '.$data->farm_unit->name;
                  })
                ->addColumn('input', function($data) {
                    return $data->input_estimate.' '.$data->input_unit->name;
                  })
                ->addColumn('output', function($data) {
                    return $data->output_min_estimate.'-'.$data->output_max_estimate.' '.$data->input_unit->name;
                  })
                ->addColumn('action', function($data){
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = 'manage_'.$this->_permission;
                    $delete  = 'delete_'.$this->_permission;
                    $view    = 'view_'.$this->_permission;
                    return view('partials.actions', compact('route','id','manage','view','delete'))->render();
                })
                ->rawColumns(['action', 'enterprise', 'farm', 'input', 'output'])
                ->make(true);
        }
    }
}