<?php
    
namespace App\Http\Controllers\Insurance;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\User;
use App\Models\Insurance\InsuranceWindow;
use App\Models\Settings\Season;
    
class InsurancePeriodController extends Controller
{
    public $_permission    = "insurance-settings";
    public $_route         = "insurance.insurance-periods";
    public $_dir           = "insurance.insurance_periods";
    public $_menu_group    = "Insurance";
    public $_page_title    = 'Insurance period settings';

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
            $seasons = Season::pluck('name', 'id')->all();     
            return view($this->_dir.'.create', compact('seasons'));
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
            'season_id' => 'required|exists:seasons,id',
            'opening_date' => 'required',
            'closing_date' => 'required'
        ]);

        try {
            $data = [
                'season_id' => $request->season_id,
                'opening_date' => $request->opening_date,
                'closing_date' => $request->closing_date
            ];
            InsuranceWindow::create($data);            
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
    public function edit(InsuranceWindow $period)
    {    
        try {     
            $seasons = Season::pluck('name', 'id')->all();
            return view($this->_dir.'.edit', compact('period', 'seasons'));
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
            'season_id' => 'required',
            'opening_date' => 'required',
            'closing_date' => 'required'
        ]);
    
        try { 
            $data = [
                'season_id' => $request->season_id,
                'opening_date' => $request->opening_date,
                'closing_date' => $request->closing_date
            ];
            $period = InsuranceWindow::findOrFail($id);    
            $period->update($data);        
            return redirect()->route($this->_route.'.index')
                            ->with('success','Operation was successful');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
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
            $data = InsuranceWindow::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ]);
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('season', function($data) {
                    return $data->season->name;
                  })
                ->addColumn('opening', function($data) {
                    return $data->opening_date;
                  })
                ->addColumn('closing', function($data) {
                    return $data->closing_date;
                  })
                ->addColumn('status', function($data) {
                    return '';
                  })
                ->addColumn('action', function($data){
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = 'manage_'.$this->_permission;
                    // $delete  = 'delete_'.$this->_permission;
                    // $view    = 'view_'.$this->_permission;
                    return view('partials.actions', compact('route','id','manage'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}