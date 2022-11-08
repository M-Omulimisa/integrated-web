<?php
    
namespace App\Http\Controllers\Settings;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\Settings\CountryModule;
use App\Models\Settings\Country;
use App\Models\Settings\SystemModule;
    
class CountryModuleController extends Controller
{ 
    public $_permission    = "settings";
    public $_route         = "settings.country-modules";
    public $_dir           = "settings.country_modules";
    public $_menu_group    = "Settings";
    public $_page_title    = 'Country Modules';

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
        try {   
            $countries = Country::whereNotIn('id',function($query) {
                $query->select('country_id')->from(with(new CountryModule)->getTable());
            })->pluck('name', 'id')->all();
            $modules = SystemModule::pluck('name', 'id')->all();

            return view($this->_dir.'.create', compact('countries', 'modules'));
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
        request()->validate([
            'country_id' => 'required',
            'modules' => 'required'
        ]);

        try { 
            for ($i=0; $i < count($request->modules); ++$i) {
                  CountryModule::create([
                    'country_id' => $request->country_id,
                    'module_id' => $request->modules[$i]
                  ]);
              } 
        
            return redirect()->route($this->_route.'.index')
                            ->with('success','Operation successfully.');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\CountryModule  $country_module
     * @return \Illuminate\Http\Response
     */
    public function show(CountryModule $country_module)
    {
        try {            
            return view($this->_dir.'.show',compact('country_module'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CountryModule  $country_module
     * @return \Illuminate\Http\Response
     */
    public function edit($country_id)
    {
        try {        
            $country = Country::whereId($country_id)->first();
            $countries = Country::whereId($country_id)->pluck('name', 'id')->all();
            $modules = SystemModule::pluck('name', 'id')->all();

            return view($this->_dir.'.edit', compact('country', 'modules', 'countries'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CountryModule  $country_module
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CountryModule $country_module)
    {
         request()->validate([
            'name' => 'required',
            'organisation_id' => 'required',
            'location_level' => 'nullable|exists:country_admin_units,id',
            'permissions' => 'required'
        ]);

        try {            
        
            $country_module->update([
                'organisation_id' => $request->organisation_id,
                'location_level' => $request->location_level,
                'name' => $request->name
            ]);

            return redirect()->route($this->_route.'.index')
                            ->with('success','Operation successfully');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CountryModule  $country_module
     * @return \Illuminate\Http\Response
     */
    public function destroy(CountryModule $country_module)
    {
        try {            
            $country_module->delete();
        
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
            $data = CountryModule::select(['country_id', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ])->groupBy('country_id');
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('country', function($data) {
                    return $data->country_obj()->name;
                  })
                ->addColumn('modules', function($data) {
                    $list = '';
                    foreach ($data->country_obj()->modules as $module) {
                        $list .= $module->module->name.'<br>';
                    }
                    return $list;
                  })
                ->addColumn('action', function($data){
                    $route   = $this->_route;
                    $id      = $data->country_id;
                    $manage  = 'manage_'.$this->_permission;
                    // $delete  = 'delete_'.$this->_permission;
                    // $view    = 'list_'.$this->_permission;
                    return view('partials.actions', compact('route','id','manage'))->render();
                })
                ->rawColumns(['action', 'modules'])
                ->make(true);
        }
    }
}