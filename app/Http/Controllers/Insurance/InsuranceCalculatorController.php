<?php
    
namespace App\Http\Controllers\Insurance;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\User;
use App\Models\Insurance\InsuranceCalculatorValue;
use App\Models\Settings\Country;
use App\Models\Settings\CountryAdminUnit;
use App\Models\Settings\Enterprise;
use App\Models\Settings\Season;
use App\Models\Settings\Location;
use App\Models\Insurance\InsuredLocation;
use App\Models\Insurance\InsuredEnterprise;
use App\Models\Insurance\InsuredAnnualEnterprise;
use App\Models\Insurance\InsuranceCommission;
use App\Models\Settings\AgentCommissionRanking;
use App\Models\Settings\Setting;
    
class InsuranceCalculatorController extends Controller
{
    public $_permission    = "farmers";
    public $_route         = "insurance.calculator";
    public $_dir           = "insurance.calculator";
    public $_menu_group    = "Insurance";
    public $_page_title    = 'calculator';

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
            $countries = Country::pluck('name','id')->all();
            $admin_levels = CountryAdminUnit::pluck('name','id')->all();
            $enterprises = Enterprise::pluck('name','id')->all();
            $locations = Location::pluck('name','id')->all();             
            $seasons = Season::pluck('name', 'id')->all();
            $commission_rankings = AgentCommissionRanking::orderBy('order', 'ASC')->get(); 
            $computation_types = Setting::COMPUTATION_TYPE;
            if (($key = array_search('interest', $computation_types)) !== false) {
                unset($computation_types[$key]);
            }
            return view($this->_dir.'.create', compact('countries', 'admin_levels', 'enterprises', 'locations', 'seasons', 'commission_rankings', 'computation_types'));
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
            'sum_insured' => 'required',
            'sum_insured_special' => 'required',
            'govt_subsidy_none' => 'required',
            'govt_subsidy_small_scale' => 'required',
            'govt_subsidy_large_scale' => 'required',
            'location_level_id' => 'required',
            'govt_subsidy_locations' => 'required',
            'scale_limit' => 'required',
            'ira_levy' => 'required',
            'commission' => 'required',
            'types' => 'required',
            'commission_rankings' => 'required',
        ]);
        
        try { 
            $data = [
                'country_id' => $request->country_id,
                'season_id' => $request->season_id,
                'sum_insured' => $request->sum_insured,
                'sum_insured_special' => $request->sum_insured_special,
                'govt_subsidy_none' => $request->govt_subsidy_none,
                'govt_subsidy_small_scale' => $request->govt_subsidy_small_scale,
                'govt_subsidy_large_scale' => $request->govt_subsidy_large_scale,
                'govt_subsidy_locations' => $request->govt_subsidy_locations,
                'location_level_id' => $request->location_level_id,
                'scale_limit' => $request->scale_limit,
                'ira_levy' => $request->ira_levy,
            ];

            $calc = InsuranceCalculatorValue::create($data);

            if ($calc && count($request->commission_rankings) > 0) {
                for ($i=0; $i < count($request->commission_rankings); ++$i) {
                      InsuranceCommission::create([
                        'calculator_id' => $calc->id,
                        'commission_ranking_id' => $request->commission_rankings[$i],
                        'commission' => $request->commission[$i],
                        'type' => $request->types[$i],
                      ]);
                  }                
            }

            if ($calc && count($request->locations) > 0) {
                for ($i=0; $i < count($request->locations); ++$i) {
                      InsuredLocation::create([
                        'calculator_id' => $calc->id,
                        'location_id' => $request->locations[$i]
                      ]);
                  }                
            }

            if ($calc && count($request->enterprises) > 0) {
                for ($i=0; $i < count($request->enterprises); ++$i) {
                      InsuredEnterprise::create([
                        'calculator_id' => $calc->id,
                        'enterprise_id' => $request->enterprises[$i]
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
    public function edit(InsuranceCalculatorValue $calculator)
    {    
        try {
            $calc = InsuranceCalculatorValue::orderBy('created_at', 'DESC')->first();
            $countries = Country::pluck('name','id')->all();
            $admin_levels = CountryAdminUnit::pluck('name','id')->all();
            $seasons = Season::pluck('name','id')->all();
            $enterprises = Enterprise::get();
            $locations = Location::get();   
            $commission_rankings = AgentCommissionRanking::orderBy('order', 'ASC')->get();     
            return view($this->_dir.'.edit', compact('calculator', 'countries', 'admin_levels', 'enterprises', 'locations', 'seasons', 'commission_rankings'));
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
            'sum_insured' => 'required',
            'sum_insured_special' => 'required',
            'govt_subsidy_none' => 'required',
            'govt_subsidy_small_scale' => 'required',
            'govt_subsidy_large_scale' => 'required',
            'location_level_id' => 'required',
            'govt_subsidy_locations' => 'required',
            'scale_limit' => 'required',
            'ira_levy' => 'required',
            'commission' => 'required',
        ]);
        
        try { 
            $data = [
                'country_id' => $request->country_id,
                'season_id' => $request->season_id,
                'sum_insured' => $request->sum_insured,
                'sum_insured_special' => $request->sum_insured_special,
                'govt_subsidy_none' => $request->govt_subsidy_none,
                'govt_subsidy_small_scale' => $request->govt_subsidy_small_scale,
                'govt_subsidy_large_scale' => $request->govt_subsidy_large_scale,
                'govt_subsidy_locations' => $request->govt_subsidy_locations,
                'location_level_id' => $request->location_level_id,
                'scale_limit' => $request->scale_limit,
                'ira_levy' => $request->ira_levy,
            ];

            $calc = InsuranceCalculatorValue::findOrFail($id);
            $calculator = $calc->update($data);

            if ($calculator) {
                InsuredLocation::where('calculator_id', $id)->delete();
                InsuredEnterprise::where('calculator_id', $id)->delete();
            }

            if ($calculator && count($request->locations) > 0) {
                for ($i=0; $i < count($request->locations); ++$i) {
                      InsuredLocation::create([
                        'calculator_id' => $id,
                        'location_id' => $request->locations[$i]
                      ]);
                  }                
            }

            if ($calculator && count($request->enterprises) > 0) {
                for ($i=0; $i < count($request->enterprises); ++$i) {
                      InsuredEnterprise::create([
                        'calculator_id' => $id,
                        'enterprise_id' => $request->enterprises[$i]
                      ]);
                  }                
            }

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
            $data = InsuranceCalculatorValue::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ]);
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('season', function($data) {
                    return $data->season->name;
                  })
                ->addColumn('location', function($data) {
                    $list = '';
                    if (count($data->locations) > 0) {
                        foreach ($data->locations as $location) {
                            $list .= '-'.$location->location->name.'<br>';
                        }
                    }
                    return $list;
                  })
                ->addColumn('enterprises', function($data) {
                    $list = '';
                    if (count($data->enterprises) > 0) {
                        foreach ($data->enterprises as $enterprise) {
                            $list .= '-'.$enterprise->enterprise->name.'<br>';
                        }
                    }
                    return $list;
                  })
                ->addColumn('values', function($data) {
                    $commission = '';
                    if (count($data->commission_rankings) > 0) {
                        foreach ($data->commission_rankings as $ranking) {
                            $commission .= $ranking->commission.' ('.$ranking->ranking->name.'), ';
                        }
                    }

                    $values = '<span class="text-muted">For locations without subsidy: </span> '.$data->sum_insured.'%<br>'
                                .'<span class="text-muted">For locations with subsidy: </span> '.$data->sum_insured_special.'%<br>'
                                .'<span class="text-muted">No Government subsidy: </span> '.$data->govt_subsidy_none.'%<br>'
                                .'<span class="text-muted">For specific districtsFor specific districts: </span> '.$data->govt_subsidy_locations.'%<br>'
                                .'<span class="text-muted">For specific districtsFor small scale: </span> '.$data->govt_subsidy_small_scale.'%<br>'
                                .'<span class="text-muted">For specific districtsFor large scale: </span> '.$data->govt_subsidy_large_scale.'%<br>'
                                .'<span class="text-muted">IRA Levy: </span> '.$data->ira_levy.'%<br>'
                                .'<span class="text-muted">Agent Commission: </span> '.$commission.'<br>'
                                .'<span class="text-muted">Farming scale determinant: </span> '.$data->scale_limit;
                    return $values;
                  })
                ->addColumn('action', function($data){
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = 'manage_'.$this->_permission;
                    $delete  = 'delete_'.$this->_permission;
                    $view    = 'view_'.$this->_permission;
                    return view('partials.actions', compact('route','id','manage','view','delete'))->render();
                })
                ->rawColumns(['action', 'location', 'enterprises', 'values'])
                ->make(true);
        }
    }
}