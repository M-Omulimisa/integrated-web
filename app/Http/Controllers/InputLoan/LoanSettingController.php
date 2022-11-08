<?php
    
namespace App\Http\Controllers\InputLoan;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\User;
use App\Models\Loans\LoanProject;
use App\Models\Loans\LoanLimit;
    
class LoanSettingController extends Controller
{
    public $_permission    = "input-loans";
    public $_route         = "input-loans.loan-settings";
    public $_dir           = "input_loans.loan_settings";
    public $_menu_group    = "Input Loans";
    public $_page_title    = 'Loan Limitations';

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
            $projects = LoanProject::pluck('name', 'id')->all();         
            return view($this->_dir.'.create', compact('projects'));
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
            'project_id' => 'nullable|unique:loan_limits,project_id',
            'min_group_members' => 'nullable|numeric',
            'max_group_members' => 'nullable|numeric',
            'min_group_loan_amount' => 'nullable|numeric',
            'max_group_loan_amount' => 'nullable|numeric',
            'min_amount_per_farmer' => 'nullable|numeric',
            'max_amount_per_farmer' => 'nullable|numeric',
        ]);

        if (is_null($request->min_group_members) && is_null($request->max_group_members) && is_null($request->min_group_loan_amount) && is_null($request->max_group_loan_amount) && is_null($request->min_amount_per_farmer) && is_null($request->max_amount_per_farmer)) {
            return redirect()->back()->withErrors('At least one field id required')->withInput();
        }

        try {  
            LoanLimit::create($request->all());          
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
    public function edit($id)
    {    
        try {            
            $data = [];
            return view($this->_dir.'.edit', compact('data', 'id'));
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
            $data = LoanLimit::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ]);
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('id', function($data) {
                    return $data->id;
                  })
                ->addColumn('group', function($data) {
                    return 'Min: '.($data->min_group_members ?? '-').'<br>Max: '.($data->max_group_members ?? '-');
                  })
                ->addColumn('amount', function($data) {
                    return 'Min: '.($data->min_group_loan_amount ?? '-').'<br>Max: '.($data->max_group_loan_amount ?? '-');
                  })
                ->addColumn('farmer', function($data) {
                    return 'Min: '.($data->min_amount_per_farmer ?? '-').'<br>Max: '.($data->max_amount_per_farmer ?? '-');
                  })
                ->addColumn('action', function($data){
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = 'manage_'.$this->_permission;
                    $delete  = 'delete_'.$this->_permission;
                    $view    = 'view_'.$this->_permission;
                    return view('partials.actions', compact('route','id','manage','view','delete'))->render();
                })
                ->rawColumns(['action', 'group', 'amount', 'farmer'])
                ->make(true);
        }
    }
}