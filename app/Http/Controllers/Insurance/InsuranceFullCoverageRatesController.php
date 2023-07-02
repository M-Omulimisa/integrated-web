<?php
    
namespace App\Http\Controllers\Insurance;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\User;
use App\Models\Insurance\InsuranceFullCoverRate;
use App\Models\Settings\Location;
use App\Models\Settings\Country;
use App\Models\Settings\Season;
    
class InsuranceFullCoverageRatesController extends Controller
{
    public $_permission    = "insurance-settings";
    public $_route         = "insurance.full-coverage-rates";
    public $_dir           = "insurance.full_coverage_rates";
    public $_menu_group    = "Insurance";
    public $_page_title    = 'Insurance full_cover settings';

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
            $countries = Country::pluck('name', 'id')->all();       
            $locations = Location::pluck('name', 'id')->all();  
            $seasons = Season::pluck('name', 'id')->all();
            return view($this->_dir.'.create', compact('locations', 'countries', 'seasons'));
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
            'location_id' => 'required',
            'season_id' => 'required',
            'rate' => 'required'
        ]);

        try {      
            $data = [
                'country_id' => $request->country_id, 
                'location_id' => $request->location_id,
                'season_id' => $request->season_id,
                'rate' => $request->rate,
            ];  
            InsuranceFullCoverRate::create($data);        
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
    public function show(InsuranceFullCoverRate $full_cover)
    {
        try {    
            return view($this->_dir.'.show', compact('full_cover'));
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
    public function edit(InsuranceFullCoverRate $full_cover)
    {    
        try {     
            $enterprises = Enterprise::pluck('name', 'id')->all();
            $countries = Country::pluck('name', 'id')->all();       
            return view($this->_dir.'.edit', compact('full_cover', 'enterprises', 'countries'));
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
            'enterprise_id' => 'required',
            'menu' => 'required',
            'amount' => 'required',
            'status' => 'required',
            'country_id' => 'required'
        ]);
    
        try {           
            $data = [
                'enterprise_id' => $request->enterprise_id, 
                'menu' => $request->menu,
                'amount' => $request->amount,
                'status' => $request->status,
                'country_id' => $request->country_id,
            ];       
            $option = InsuranceFullCoverRate::findOrFail($id);
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
            $data = InsuranceFullCoverRate::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ]);
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('id', function($data) {
                    return $data->id;
                  })
                ->addColumn('location', function($data) {                    
                    $values = '<span class="text-muted">Location: </span> '.$data->location->name.'<br>'
                                .'<span class="text-muted">Contry </span> '.$data->country->name;
                    return $values;
                  })
                ->addColumn('season', function($data) {
                    return $data->season->name;
                  })
                ->addColumn('rates', function($data) {
                    return $data->rate.'%';
                  })
                ->addColumn('action', function($data){
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = 'manage_'.$this->_permission;
                    $delete  = 'delete_'.$this->_permission;
                    $view    = 'view_'.$this->_permission;
                    return view('partials.actions', compact('route','id','manage','view','delete'))->render();
                })
                ->rawColumns(['action', 'location'])
                ->make(true);
        }
    }
}