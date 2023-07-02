<?php
    
namespace App\Http\Controllers\Alerts;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\User;
use App\Models\Users\Role;
use App\Models\Settings\Country;
use App\Models\Settings\Enterprise;
use App\Models\Settings\Location;
use App\Models\Settings\Language;
use App\Models\Settings\Setting;
use App\Models\Alerts\Alert;
use App\Models\Alerts\AlertRecipient;
use App\Models\Alerts\AlertEnterprise;
use App\Models\Alerts\AlertLocation;
use App\Models\Alerts\AlertLanguage;
use App\Models\Farmers\FarmerGroup;
    
class AlertController extends Controller
{
    public $_permission    = "alerts";
    public $_route         = "alerts.alerts";
    public $_dir           = "alerts.alerts";
    public $_menu_group    = "Alerts";
    public $_page_title    = 'Alerts';

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
    public function create_single()
    {
        try {  
            $time_intervals = Setting::ALETS_TIME;
            $countries = Country::pluck('name', 'id')->all();          
            return view($this->_dir.'.create_single', compact('countries', 'time_intervals'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_bulk()
    {
        try {   
            $time_intervals = Setting::ALETS_TIME; 
            $countries = Country::pluck('name', 'id')->all();        
            return view($this->_dir.'.create_bulk', compact('countries', 'time_intervals'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_area()
    {
        try {   
            $time_intervals = Setting::ALETS_TIME;
            $countries = Country::pluck('name', 'id')->all();
            $locations = Location::pluck('name', 'id')->all();         
            return view($this->_dir.'.create_area', compact('countries', 'locations', 'time_intervals'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_enterprise()
    {
        try {     
            $time_intervals = Setting::ALETS_TIME; 
            $countries = Country::pluck('name', 'id')->all(); 
            $enterprises = Enterprise::pluck('name', 'id')->all();     
            return view($this->_dir.'.create_enterprise', compact('countries', 'enterprises', 'time_intervals'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_user_group()
    {
        try {   
            $time_intervals = Setting::ALETS_TIME; 
            $countries = Country::pluck('name', 'id')->all(); 
            $user_groups = [
                'farmer' => 'Farmers',
                'agent' => 'Village Agents',
                'extension' => 'Extenstion Officers'
            ];       
            return view($this->_dir.'.create_user_group', compact('countries', 'user_groups', 'time_intervals'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_keyword()
    {
        try {     
            $time_intervals = Setting::ALETS_TIME;
            $countries = Country::pluck('name', 'id')->all();
            $languages = Language::pluck('name', 'id')->all();       
            return view($this->_dir.'.create_keyword', compact('countries', 'languages', 'time_intervals'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_farmer_group()
    {
        try {     
            $time_intervals = Setting::ALETS_TIME;
            $countries = Country::pluck('name', 'id')->all();
            $groups = FarmerGroup::pluck('name', 'id')->all();       
            return view($this->_dir.'.create_farmer_group', compact('countries', 'groups', 'time_intervals'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_group_member()
    {
        try {     
            $time_intervals = Setting::ALETS_TIME;
            $countries = Country::pluck('name', 'id')->all();
            $groups = FarmerGroup::pluck('name', 'id')->all();       
            return view($this->_dir.'.create_group_member', compact('countries', 'groups', 'time_intervals'));
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
            'message' => 'required|max:160',
            'is_scheduled' => 'required',
            'type' => 'required',
            $request->type => 'required',
        ]);

        try {            
            $alert = Alert::create($request->all());

            if ($alert) {
                if ($request->type=='recipient') {
                    for ($i=0; $i < count($request->recipient); ++$i) {
                          AlertRecipient::create([
                            'alert_id' => $alert->id,
                            'phone' => $request->recipient[$i]
                          ]);
                      }
                }
                elseif ($request->type=='languages') {
                    for ($i=0; $i < count($request->languages); ++$i) {
                          AlertLanguage::create([
                            'alert_id' => $alert->id,
                            'language_id' => $request->languages[$i]
                          ]);
                      }
                }
                elseif ($request->type=='enterprises') {
                    for ($i=0; $i < count($request->enterprises); ++$i) {
                          AlertEnterprise::create([
                            'alert_id' => $alert->id,
                            'enterprise_id' => $request->enterprises[$i]
                          ]);
                      }
                }
                elseif ($request->type=='locations') {
                    for ($i=0; $i < count($request->locations); ++$i) {
                          AlertLocation::create([
                            'alert_id' => $alert->id,
                            'location_id' => $request->locations[$i]
                          ]);
                      }
                }
                else{
                    return redirect()->back()->withErrors('Operation failed')->withInput();
                }
            }

            // 'message', 
            // 'is_to_users', 
            // 'is_to_farmers', 
            // 'is_village_agents', 
            // 'is_extension_officers', 
            // 'is_scheduled', 
            // 'date', 
            // 'time', 
            // 'status', 
            // 'user_id'
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
            $data = Alert::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ])->orderBy('created_at', 'DESC');
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('timestamp', function($data) {
                    if (count($data->enterprises) > 0) { $to = 'To Enterprises ('.count($data->enterprises).')<br>'; }
                    if (count($data->languages) > 0) { $to = 'By Language ('.count($data->languages).')<br>'; }
                    if (count($data->locations) > 0) { $to = 'By Location ('.count($data->locations).')<br>'; }
                    if (count($data->enterprises) == 0 && count($data->languages) == 0 && count($data->locations) == 0) { $to = ''; }

                    $time = $to;
                    $time .= '<span class="text-muted">Created at:</span> '.$data->created_at;
                    if($data->is_scheduled) $time .= '<br><span class="text-muted">Scheduled at at:</span> '.$data->date.' '.$data->time;
                    if($data->status == 'sent') $time .= '<br><span class="text-muted">Sent at:</span> '.$data->updated_at;
                    return $time;
                  })
                ->addColumn('recipient', function($data) {
                    return count($data->recipients);
                  })
                ->addColumn('statuses', function($data) {
                    $statuses = '<span class="text-muted">Status:</span> '.$data->status;
                    $statuses .= '<br><span class="text-muted">Pending:</span> 0';
                    $statuses .= '<br><span class="text-muted">Sent:</span> 0';
                    $statuses .= '<br><span class="text-muted">Failed:</span> 0';
                    return $statuses;
                  })
                ->addColumn('action', function($data){
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = 'manage_'.$this->_permission;
                    $delete  = 'delete_'.$this->_permission;
                    $view    = 'view_'.$this->_permission;
                    return view('partials.actions', compact('route','id','manage','view','delete'))->render();
                })
                ->rawColumns(['action', 'timestamp', 'statuses'])
                ->make(true);
        }
    }
}