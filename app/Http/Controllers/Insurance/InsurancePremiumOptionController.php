<?php
    
namespace App\Http\Controllers\Insurance;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\User;
use App\Models\Insurance\InsurancePremiumOption;
use App\Models\Settings\Enterprise;
use App\Models\Settings\Country;
use App\Models\Settings\Season;
    
class InsurancePremiumOptionController extends Controller
{
    public $_permission    = "insurance-settings";
    public $_route         = "insurance.premium-options";
    public $_dir           = "insurance.premium_options";
    public $_menu_group    = "Insurance";
    public $_page_title    = 'Insurance premium settings';

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
            $countries = Country::pluck('name', 'id')->all();  
            $seasons = Season::pluck('name', 'id')->all();       
            return view($this->_dir.'.create', compact('enterprises', 'countries', 'seasons'));
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
            'country_id' => 'required',
            'season_id' => 'required',
            'enterprise_id' => 'required',
            'menu' => 'required',
            'sum_insured_per_acre' => 'required',
            'premium_per_acre' => 'required'
        ]);
    
        try {           
            $data = [
                'country_id' => $request->country_id,
                'enterprise_id' => $request->enterprise_id, 
                'season_id' => $request->season_id, 
                'menu' => $request->menu,
                'sum_insured_per_acre' => $request->sum_insured_per_acre,
                'premium_per_acre' => $request->premium_per_acre
            ];  
            InsurancePremiumOption::create($data);        
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
    public function show(InsurancePremiumOption $premium)
    {
        try {    
            return view($this->_dir.'.show', compact('premium'));
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
    public function edit(InsurancePremiumOption $premium)
    {    
        try {     
            $enterprises = Enterprise::pluck('name', 'id')->all();
            $countries = Country::pluck('name', 'id')->all();  
            $seasons = Season::pluck('name', 'id')->all();      
            return view($this->_dir.'.edit', compact('premium', 'enterprises', 'countries', 'seasons'));
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
            'country_id' => 'required',
            'season_id' => 'required',
            'enterprise_id' => 'required',
            'menu' => 'required',
            'status' => 'required',
            'sum_insured_per_acre' => 'required',
            'premium_per_acre' => 'required'
        ]);
    
        try {           
            $data = [
                'country_id' => $request->country_id,
                'enterprise_id' => $request->enterprise_id, 
                'season_id' => $request->season_id, 
                'menu' => $request->menu,
                'status' => $request->status,
                'sum_insured_per_acre' => $request->sum_insured_per_acre,
                'premium_per_acre' => $request->premium_per_acre
            ];       
            $option = InsurancePremiumOption::findOrFail($id);
            $option->update($data);
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
            $data = InsurancePremiumOption::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ]);
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
                ->addColumn('enterprise', function($data) {
                    return $data->enterprise->name;
                  })
                ->addColumn('season', function($data) {
                    return $data->season->name;
                  })
                ->addColumn('display', function($data) {
                    return $data->status ? 'On' : 'Off';
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