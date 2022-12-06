<?php
    
namespace App\Http\Controllers\Insurance;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\User;
use App\Models\Agents\Agent;
use App\Models\Settings\Country;
use App\Models\Settings\Season;
use App\Models\Settings\Enterprise;
use App\Models\Settings\Location;
use App\Models\Farmers\Farmer;
    
class InsuranceSubscriptionController extends Controller
{
    public $_permission    = "farmers";
    public $_route         = "insurance.subscriptions";
    public $_dir           = "insurance.subscriptions";
    public $_menu_group    = "Insurance";
    public $_page_title    = 'Farmers';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {            
            return view($this->_dir.'.index'); // any_errors
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_crops()
    {
        try {  
            $agents = Agent::pluck('name','id')->all(); 
            $countries = Country::pluck('name','id')->all();
            $seasons = Season::pluck('name','id')->all();          
            $enterprises = Enterprise::where('category', 'Crop')->pluck('name','id')->all(); 
            $locations = Location::pluck('name','id')->all(); 
            $farmers = Farmer::get(); // pluck('first_name','id')->all();          
            return view($this->_dir.'.create_crops', compact('agents', 'countries', 'seasons', 'enterprises', 'locations', 'farmers'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_livestock()
    {
        try { 
            $agents = Agent::pluck('name','id')->all(); 
            $countries = Country::pluck('name','id')->all();
            $seasons = Season::pluck('name','id')->all();          
            $enterprises = Enterprise::where('category', 'Livestock')->pluck('name','id')->all(); 
            $locations = Location::pluck('name','id')->all(); 
            $farmers = Farmer::get(); // pluck('first_name','id')->all();          
            return view($this->_dir.'.create_livestock', compact('agents', 'countries', 'seasons', 'enterprises', 'locations', 'farmers'));
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
        return redirect()->back()->withErrors('Operation failed')->withInput();
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        // "category" => "crop"
        // "session_id" => "MOWEB_80K73DvrwuLlJfSS9PoGeZGOPBtAGh"
        // "phone" => "System"
        // "user_type" => "679d4499-d070-4d39-82be-30353a0dcbb1"
        // "tool" => "web"
        // "main_action" => "2"
        // "subscription" => "crop"
        // "country_id" => "c719562f-c19e-436b-8a19-c8647cb04e91"
        // "agent_id" => null
        // "farmer_id" => "14e5ad91-0f93-4a14-ad41-4bdf174a2f14"
        // "season_id" => "2e19e0f8-ed46-4806-a5ad-f9dc202bcf16"
        // "item_category" => "1"
        // "item_crops" => "b23bd5c3-9278-46fd-9700-6846c2e1612b"
        // "crops_acrage" => "10"
        // "crops_yield_per_acrage" => "10"
        // "crops_price_per_unit" => "2000"
        // "net_premium" => "0"

        try {            
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
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
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
            $data = User::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ])->orderBy('name', 'ASC');
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('id', function($data) {
                    return $data->id;
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