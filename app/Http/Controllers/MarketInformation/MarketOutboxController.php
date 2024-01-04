<?php
    
namespace App\Http\Controllers\MarketInformation;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\Market\MarketOutbox;
    
class MarketOutboxController extends Controller
{
    public $_permission    = "market-subscriptions";
    public $_route         = "market.outbox";
    public $_dir           = "market.outbox";
    public $_menu_group    = "Market Information";
    public $_page_title    = 'Outbox';

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
    { }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { }
    
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
    { }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { }
    
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
            $data = MarketOutbox::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ])->orderBy('created_at', 'DESC');
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
                ->addColumn('action_date', function ($data){
                    if($data->status == 'SENT') return $data->sent_at;
                    if($data->status == 'FAILED') return $data->failed_at;
                    if($data->status == 'PENDING') return $data->created_at;
                    if($data->status == 'PROCESSING') return $data->processsed_at;
                    })
                ->addColumn('message_count', function ($data){
                    return strlen(str_replace('|', '', $data->message));
                    })
                ->addColumn('message_status', function ($data){
                    if($data->status == 'SENT') return '<span class="text-success"><strong>SENT</strong></span>';
                    if($data->status == 'FAILED') return '<span class="text-danger"><strong>FAILED</strong></span>';
                    if($data->status == 'PENDING') return '<span class="text-warning"><strong>PENDING</strong></span>';
                    if($data->status == 'PROCESSING') return '<span class="text-primary"><strong>PROCESSING</strong></span>';
                    })
                ->addColumn('actions', function($data){
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = 'manage_'.$this->_permission;
                    $delete  = 'delete_'.$this->_permission;
                    $view    = 'view_'.$this->_permission;
                    return view('partials.actions', compact('route','id','manage','view','delete'))->render();
                })
                ->rawColumns(['actions','message_status'])
                ->make(true);
        }
    }
}