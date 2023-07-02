<?php
    
namespace App\Http\Controllers\InputLoan;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\User;
use App\Models\Loans\LoanProject;
use App\Models\Loans\Buyer;
use App\Models\Settings\Season;
use App\Models\Settings\Enterprise;
use App\Models\Settings\EnterpriseVariety;
use App\Models\Settings\EnterpriseType;
use App\Models\Settings\Currency;
use App\Models\Settings\MeasureUnit;
use App\Models\Loans\BuyerOutputPrice;
    
class OutputPriceController extends Controller
{
    public $_permission    = "input-loans";
    public $_route         = "input-loans.output-prices";
    public $_dir           = "input_loans.output_prices";
    public $_menu_group    = "Input Loans";
    public $_page_title    = 'Output Prices';

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
            $seasons = Season::pluck('name', 'id')->all();
            $buyers = Buyer::pluck('buyer_name', 'id')->all();
            $enterprises = Enterprise::pluck('name', 'id')->all();
            $enterprise_varieties = EnterpriseVariety::pluck('name', 'id')->all();
            $enterprise_types = EnterpriseType::pluck('name', 'id')->all();
            $currencies = Currency::pluck('name', 'id')->all();
            $units = MeasureUnit::pluck('name', 'id')->all();

            return view($this->_dir.'.create', compact('projects', 'buyers', 'seasons', 'enterprises', 'enterprise_varieties', 'enterprise_types', 'currencies', 'units'));
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
            'season_id' => 'required',
            'buyer_id' => 'required',
            'enterprise_id' => 'required',
            'price' => 'required',
            'currency_id' => 'required',
            'unit_id' => 'required',
        ]);

        try { 
            BuyerOutputPrice::create($request->all());           
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
            $price = BuyerOutputPrice::find($id);           
            $projects = LoanProject::pluck('name', 'id')->all();
            $seasons = Season::pluck('name', 'id')->all();
            $buyers = Buyer::pluck('buyer_name', 'id')->all();
            $enterprises = Enterprise::pluck('name', 'id')->all();
            $enterprise_varieties = EnterpriseVariety::pluck('name', 'id')->all();
            $enterprise_types = EnterpriseType::pluck('name', 'id')->all();
            $currencies = Currency::pluck('name', 'id')->all();
            $units = MeasureUnit::pluck('name', 'id')->all();

            return view($this->_dir.'.edit', compact('price', 'projects', 'buyers', 'seasons', 'enterprises', 'enterprise_varieties', 'enterprise_types', 'currencies', 'units'));
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
            'buyer_id' => 'required',
            'enterprise_id' => 'required',
            'price' => 'required',
            'currency_id' => 'required',
            'unit_id' => 'required',
        ]);
    
        try {   
            $price = BuyerOutputPrice::find($id);
            $price->update($request->all());         
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
            $data = BuyerOutputPrice::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ]);
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('buyer', function($data) {
                    return $data->buyer->buyer_name ?? null;
                  })
                ->addColumn('season', function($data) {
                    return $data->season->name ?? null;
                  })
                ->addColumn('enterprise', function($data) {
                    return $data->enterprise->name ?? null;
                  })
                ->addColumn('variety', function($data) {
                    return $data->variety->name ?? null;
                  })
                ->addColumn('type', function($data) {
                    return $data->type->name ?? null;
                  })
                ->addColumn('price', function($data) {
                    return ($data->currency->code ?? null).$data->price.' per '.($data->unit->slug ?? null);
                  })
                ->addColumn('action', function($data){
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = 'manage_'.$this->_permission;
                    $delete  = 'delete_'.$this->_permission;
                    $view    = 'view_'.$this->_permission;
                    return view('partials.actions', compact('route','id','manage','view','delete'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}