<?php
    
namespace App\Http\Controllers\Settings;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\Settings\AgentCommissionRanking;
use App\Models\Settings\Country;
    
class CommissionRankingController extends Controller
{ 
    public $_permission    = "settings";
    public $_route         = "settings.commission-rankings";
    public $_dir           = "settings.commission_rankings";
    public $_menu_group    = "Settings";
    public $_page_title    = 'Agent Commission Rankings';

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
        $countries = Country::pluck('name', 'id')->all();
        return view($this->_dir.'.create', compact('countries'));
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
            'country_id' => 'required',
            'order' => 'required|numeric',
        ]);

        try {            
            AgentCommissionRanking::create($request->all());
            return redirect()->route($this->_route.'.index')
                            ->with('success','Operation successfully.');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\AgentCommissionRanking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function show(AgentCommissionRanking $ranking)
    {
        try {            
            return view($this->_dir.'.show',compact('ranking'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AgentCommissionRanking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {  
            $ranking = AgentCommissionRanking::findOrFail($id);
            $countries = Country::pluck('name', 'id')->all();          
            return view($this->_dir.'.edit',compact('ranking', 'countries'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AgentCommissionRanking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AgentCommissionRanking $ranking)
    {
        try {            
             request()->validate([
                'name' => 'required',
                'country_id' => 'required',
                'order' => 'required',
            ]);
        
            $ranking->update($request->all());
        
            return redirect()->route($this->_route.'.index')
                            ->with('success','AgentCommissionRanking updated successfully');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AgentCommissionRanking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function destroy(AgentCommissionRanking $ranking)
    {
        try {            
            $ranking->delete();
        
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
            $data = AgentCommissionRanking::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ])->orderBy('name', 'ASC');
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('id', function($data) {
                    return $data->id;
                  })
                ->addColumn('country', function($data) {
                    return $data->country->name;
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