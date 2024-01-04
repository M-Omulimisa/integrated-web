<?php
    
namespace App\Http\Controllers\MarketInformation;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\Market\MarketSubscription;
use Carbon\Carbon;
use App\Models\Users\Role;
    
class MarketSubscriptionController extends Controller
{
    public $_permission    = "market-subscriptions";
    public $_route         = "market.subscriptions";
    public $_dir           = "market.subscriptions";
    public $_menu_group    = "Market Information";
    public $_page_title    = 'Market Info Subscriptions';

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
            return view($this->_dir.'.create');
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

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
            $data = MarketSubscription::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ])->orderBy('created_at', 'DESC');
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('id', function($data) {
                    return $data->id;
                  })
                ->addColumn('created', function($data) {
                    return $data->created_at;
                  })
                ->addColumn('name', function ($data){
                    return $data->first_name.' '.$data->last_name;
                    }, 0)
                ->addColumn('language', function ($data){
                    return $data->language->name ?? null;
                    }, 0)
                ->addColumn('amount', function ($data){
                    return isset($data->payment) ? number_format($data->payment->amount) : null;
                    }, 0)
                ->addColumn('period', function ($data) {
                    $frequency = str_replace('ly', '(s)', $data->frequency);
                    $frequency = $frequency == 'Trial' ? 'Week (Trial)' : $frequency;
                    return $data->period_paid.' '.$frequency;
                    }, 0)
                ->addColumn('subscription_status', function ($data){
                        if (!$data->status || Carbon::now() > $data->end_date || count($data->messages) > 0) {

                            if(Carbon::now() > $data->end_date) $data->update(['status' => false]);

                            if(Carbon::now() > $data->end_date && count($data->messages) == 0) $notset = "*";

                            return '<span class="text-danger"><strong>Expired'.($notset ?? '').'</strong></span>';
                        }
                        else{
                            return '<span class="text-success"><strong>Active</strong></span>';
                        }
                    }, 0)
                ->addColumn('seen_by_admin', function ($data){
                    foreach (auth()->user()->roles as $role){
                      if (! $data->seen_by_admin && $role->name == Role::ROLE_ADMIN) { 
                        $data->update(['seen_by_admin' => true]);
                      }
                    }
                    }, 0)
                ->addColumn('action', function($data){
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = 'manage_'.$this->_permission;
                    $delete  = 'delete_'.$this->_permission;
                    $view    = 'view_'.$this->_permission;
                    return view('partials.actions', compact('route','id','manage','view','delete'))->render();
                })
                ->rawColumns(['action','subscription_status'])
                ->make(true);
        }
    }

        // 'start_date',
        // 'end_date',
        // 'package_id',
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        try {            
            return view($this->_dir.'.upload');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
}