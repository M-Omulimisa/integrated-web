<?php
    
namespace App\Http\Controllers\Weather;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\Weather\WeatherSubscription;
use App\Models\Settings\Country;
use App\Models\Settings\Location;
use App\Models\Organisations\Organisation;
use App\Models\Settings\Language;
use App\Validators\PhoneValidator;
use Carbon\Carbon;
use App\Models\Users\Role;
    
class WeatherSubscriptionController extends Controller
{
    public $_permission    = "weather-subscriptions";
    public $_route         = "weather-info.subscriptions";
    public $_dir           = "weather.subscriptions";
    public $_menu_group    = "Weather Information";
    public $_page_title    = 'Weather Info Subscriptions';

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

            $countries = Country::orderBy('name', 'ASC')->pluck('name', 'id')->all();
            $locations = Location::orderBy('name', 'ASC')->pluck('name', 'id')->all();
            $languages = Language::orderBy('name', 'ASC')->pluck('name', 'id')->all();
            $frequencies = [
                'Trial'     => 'Trial',
                'Weekly'    => 'Weekly', 
                'Monthly'   => 'Monthly', 
                'Yearly'    => 'Yearly'
            ];
            $methods = [
                'Mobile Money' => 'Mobile Money',
                'Cash'    => 'Cash',
                // 'Bank'    => 'Bank', 
            ];

            $package = null;

            $organisations = Organisation::orderBy('name', 'ASC')->pluck('name', 'id')->all();

            return view($this->_dir.'.create', compact('locations', 'languages', 'frequencies', 'methods', 'package', 'organisations', 'countries'));
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
        dd($request->all());
        $this->validate($request,[
            'location_id'     => 'required',
            'first_name'    => 'required',
            'last_name'     => 'required',
            // 'email'         => 'nullable|email',
            'frequency'     => 'required',
            'period_paid'   => 'required|numeric',
            'phonenumber'   => ['required', new PhoneValidator($request->country_id)],
            'payment_confirmation' => 'required',
            'language_id'   => 'required',
            'user_id' => 'required',
            'organisation_id' => 'nullable',
            'payment_method' => 'required'
        ]);

        try {     

            $request->phonenumber = formatPhoneNumber($request->phonenumber, "256", "256");
            if (WeatherSubscription::where('paying_account', $request->phonenumber)->whereDate('end_date', '>', Carbon::now())->where('payment_status', '!=', 'FAILED')->first()) {
                return redirect()->back()->withErrors('Phone number has an active subscription or pending payment')->withInput();
            }
            elseif (WeatherSubscription::where('paying_account', $request->phonenumber)->where('payment_status', 'SUCCESSFUL')->where('frequency', 'Trial')->first() && $request->frequency == 'Trial') {
                return redirect()->back()->withErrors('Phone number already subscribed for trial package')->withInput();
            }

            $start_date = date("Y-m-d"); 
            $subscription = getDerivedSubscrition($request->frequency, $start_date, $request->period_paid, 'weather'); 

            if ($subscription['amount'] == 0 && $request->frequency != 'Trial') {
                return redirect()->back()->withErrors('Amount is invalid! Contact us for help')->withInput();
            }           

            $data = [         
                "language_id"       => $request->language_id,
                "location_id"       => $request->location_id,
                "first_name"        => $request->first_name,
                "last_name"         => $request->last_name,
                'frequency'         => $request->frequency,
                'period_paid'       => $request->period_paid,
                'start_date'        => $start_date,
                'end_date'          => $subscription['end_date'],
                'paying_account'    => $request->phonenumber,
                'payment_amount'    => ceil($subscription['amount'] * $request->period_paid),
                'payment_confirmation' => $request->payment_confirmation == 'on' ? true : false,
                'reference_id'      => $this->generateTxnId(),
                'payment_status'    => $request->payment_method == "Cash" ? 'SUCCESSFUL' : $subscription['status'],
                'payment_provider'  => getProviderCode($request->phonenumber), //AIRTEL, MTN, 
                'payment_method'    => $request->payment_method,
                'status'            => true,
                'user_id'           => $request->user_id,
                'organisation_id'   => $request->organisation_id
            ];

            $subscribe = WeatherSubscription::create($data);

            if ($request->frequency == 'Trial') {
                $body   = "Hello ".$subscribe->first_name." ".$subscribe->last_name.", You have subscribed for ".strtolower($subscribe->frequency)." weather information for ".$subscribe->period_paid."week from ".date('d-m-Y', strtotime($subscribe->start_date))." to ".date('d-m-Y', strtotime($subscribe->end_date));
                $result = AppHelper::instance()->sendTextMessage($subscribe->paying_account, $body);

                if (smsStatus($result) == "Error") {
                    return redirect()->route('weather-information.subscriptions.index')->withErrors('Operation was successful. SMS was not sent ('.$result[0]->id.')');
                }
                return redirect()->route('weather-information.subscriptions.index')->with('success','Operation was successful');

            }
            elseif ($request->payment_method == 'Cash') {
                $body   = "Hello ".$subscribe->first_name." ".$subscribe->last_name.", You have subscribed for weather information for ".$subscribe->period_paid." ".str_replace('ly', '(s)', $subscribe->frequency)." till ".$subscribe->end_date; 
                $result = AppHelper::instance()->sendTextMessage($subscribe->paying_account, $body);

                if (smsStatus($result) == "Error") {
                    return redirect()->route('weather-information.subscriptions.index')->withErrors('Operation was successful. SMS was not sent ('.$result[0]->id.')');
                }
                return redirect()->route('weather-information.subscriptions.index')->with('success','Operation was successful');
            }

            return redirect()->route('weather-information.transactions.index')->with('success','Operation was successful. Please check your phone to approve payment');

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
            $data = WeatherSubscription::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ])->orderBy('created_at', 'DESC');
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('created', function ($data){
                    return $data->created_at;
                    })
                ->addColumn('name', function ($data){
                    return $data->first_name.' '.$data->last_name;
                    })
                ->addColumn('language', function ($data){
                    return $data->language->name ?? null;
                    })
                ->addColumn('amount', function ($data){
                    return isset($data->payment) ? number_format($data->payment->amount) : null;
                    })
                ->addColumn('period', function ($data){
                    $frequency = str_replace('ly', '(s)', $data->frequency);
                    $frequency = $frequency == 'Trial' ? 'Week (Trial)' : $frequency;
                    return $data->period_paid.' '.$frequency;
                    })
                ->addColumn('location', function ($data){
                    return ($data->parish->name ?? "").", ".($data->subcounty->name ?? "").", ".($data->district->name ?? "");
                    })
                ->addColumn('subscription_status', function ($data){
                        if (Carbon::now() > $data->end_date) {
                            $data->update(['status' => false]);
                            return '<span class="text-danger"><strong>Expired</strong></span>';
                        }
                        else{
                            return '<span class="text-success"><strong>Active</strong></span>';
                        }
                    })
                ->addColumn('seen_by_admin', function ($data){
                    foreach (auth()->user()->roles as $role){
                      if (! $data->seen_by_admin && $role->name == Role::ROLE_ADMIN) { 
                        $data->update(['seen_by_admin' => true]);
                      }
                    }
                    })
                ->addColumn('action', function($data){
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = 'manage_'.$this->_permission;
                    $delete  = 'delete_'.$this->_permission;
                    $view    = 'view_'.$this->_permission;

                    $edit_rights    = !$this->subscriptionIsRenewed($data->id) && Carbon::now() > $data->end_date ? 'edit_weather_information_subscriptions' : null;
                    $edit_rename    = 'Renew';

                    return view('partials.actions', compact('route','id','manage','view','delete'))->render();
                })
                ->rawColumns(['action','subscription_status'])
                ->make(true);
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        try {        
            $organisations = Organisation::orderBy('name', 'ASC')->pluck('name', 'id')->all();    
            return view($this->_dir.'.upload', compact('organisations'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }

    public function subscriptionIsRenewed($id)
    {
        return WeatherSubscription::where('renewal_id', $id)->orderBy('id','DESC')->limit(1)->first();
    }
}