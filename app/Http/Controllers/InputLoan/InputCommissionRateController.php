<?php
    
namespace App\Http\Controllers\InputLoan;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\User;
use App\Models\Loans\LoanInputCommission;
use App\Models\Loans\LoanInputCommissionRate;
use App\Models\Settings\Setting;
use App\Models\Settings\Enterprise;
use App\Models\Settings\EnterpriseVariety;
use App\Models\Settings\EnterpriseType;
use App\Models\Settings\AgentCommissionRanking;
use App\Models\Settings\Country;
    
class InputCommissionRateController extends Controller
{
    public $_permission    = "input-loans";
    public $_route         = "input-loans.input-commission-rates";
    public $_dir           = "input_loans.input_commission_rates";
    public $_menu_group    = "Input Loans";
    public $_page_title    = 'Input Commission Rates';

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
            $enterprise_types = EnterpriseType::pluck('name', 'id')->all();

            $commission_rankings = AgentCommissionRanking::orderBy('order', 'ASC')->get();
            $countries = Country::pluck('name', 'id')->all();

            $computation_types = Setting::COMPUTATION_TYPE;
            if (($key = array_search('interest', $computation_types)) !== false) {
                unset($computation_types[$key]);
            }

            return view($this->_dir.'.create', compact('enterprises', 'enterprise_varieties', 'enterprise_types', 'computation_types', 'commission_rankings', 'countries'));
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
            'enterprise_id' => 'required|exists:enterprises,id',
            'country_id' => 'required',
            'commission_rankings' => 'required',
            'types' => 'required',
            'rates' => 'required',
        ]);

        try { 

            if (!is_null($request->enterprise_type_id)) {
                 if (! EnterpriseType::whereId($request->enterprise_type_id)->whereEnterpriseVarietyId($request->enterprise_variety_id)->first()) {
                     return redirect()->back()->withErrors('Invalid variety selected')->withInput();
                 }
             } 

             if (!is_null($request->enterprise_variety_id)) {
                 if (! EnterpriseVariety::whereId($request->enterprise_variety_id)->whereEnterpriseId($request->enterprise_id)->first()) {
                     return redirect()->back()->withErrors('Invalid enterprise selected')->withInput();
                 }
             } 

             $commission = LoanInputCommission::create($request->all());

            if ($commission && count($request->commission_rankings) > 0) {
                for ($i=0; $i < count($request->commission_rankings); ++$i) {
                      LoanInputCommissionRate::create([
                        'loan_input_commission_id' => $commission->id,
                        'commission_ranking_id' => $request->commission_rankings[$i],
                        'rate' => $request->rates[$i],
                        'type' => $request->types[$i],
                      ]);
                  }                
            }

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
            $data = LoanInputCommission::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ]);
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('enterprise', function($data) {
                    return $data->enterprise->name ?? null;
                  })
                ->addColumn('variety', function($data) {
                    return $data->variety->name ?? null;
                  })
                ->addColumn('type', function($data) {
                    return $data->type->name ?? null;
                  })
                ->addColumn('commission', function($data) {
                    $list = '';
                    if (isset($data->commissions) && count($data->commissions) > 0) {
                        foreach ($data->commissions as $commission) {
                            $type = str_replace('total', '', $commission->type);
                            $type = str_replace('percent', '%', $type);
                            $list .= '-'.$commission->rate.$type.' ('.$commission->ranking->name.')<br>';
                        }
                    }
                    return $list;
                  })
                ->addColumn('action', function($data){
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = 'manage_'.$this->_permission;
                    $delete  = 'delete_'.$this->_permission;
                    $view    = 'view_'.$this->_permission;
                    return view('partials.actions', compact('route','id','manage','view','delete'))->render();
                })
                ->rawColumns(['action', 'commission'])
                ->make(true);
        }
    }
}