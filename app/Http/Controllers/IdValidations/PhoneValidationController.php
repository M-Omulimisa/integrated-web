<?php
    
namespace App\Http\Controllers\IdValidations;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Response;
use DB;
use App\Models\IdValidations\PhoneValidation;
use App\Facades\IdValidation\PhoneValidationServiceFacade;
use App\Validators\PhoneValidator;
use App\Models\Settings\Country;
    
class PhoneValidationController extends Controller
{ 
    public $_permission    = "validations";
    public $_route         = "validations.phones";
    public $_dir           = "id_validations.phones";
    public $_menu_group    = "ID Validations";
    public $_page_title    = 'Phone Validation';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         // $this->middleware('permission:manage_'.$this->_permission, ['only' => ['create','store', 'edit', 'update']]);
         // $this->middleware('permission:list_'.$this->_permission, ['only' => ['index','show']]);
         // $this->middleware('permission:delete_'.$this->_permission, ['only' => ['destroy']]);
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
        return view($this->_dir.'.create');
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
            'phonenumber'         => ['required', new PhoneValidator(Country::whereName('Uganda')->first()->id)],         
        ]);

        try {

            $request->request->add(['source' => 'web']);

            DB::beginTransaction();            
            if ($record = PhoneValidation::create($request->all())) {

                $validation = PhoneValidationServiceFacade::initiate($request->phonenumber);

                PhoneValidationServiceFacade::saveResults($record, $validation);

                if ($validation->status == PhoneValidation::SUCCESS) {
                    $billing = PhoneValidationServiceFacade::bill($record, auth()->user()); 
                }                

            }else{
                return redirect()->back()->withErrors('Validation request was not submitted');
            }

            DB::commit();
            return redirect()->route($this->_route.'.index')->with('success', 'Validation request was submitted successfully');
        } catch (\Throwable $r) {
            DB::rollback();
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\PhoneValidation  $phone
     * @return \Illuminate\Http\Response
     */
    public function show(PhoneValidation $phone)
    {
        try {            
            return view($this->_dir.'.show',compact('phone'));
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PhoneValidation  $phone
     * @return \Illuminate\Http\Response
     */
    public function edit(PhoneValidation $phone)
    { }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PhoneValidation  $phone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PhoneValidation $phone)
    { }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PhoneValidation  $phone
     * @return \Illuminate\Http\Response
     */
    public function destroy(PhoneValidation $phone)
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
            $data = PhoneValidation::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ])->orderBy('created_at', 'DESC');
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('record', function($data){
                    $user = '<br><span class="text-muted">By:</span> '.$data->user->name;
                    if(isset($data->organisation)) $organisation = '<br><span class="text-muted">Organisation:</span> '.$data->organisation->name;

                  return '<span class="text-muted">Reference:</span> '.$data->reference
                        .'<br><span class="text-muted">Timestamp:</span> '.$data->created_at
                        .($user ?? '')
                        .($organisation ?? '');
              })
              ->addColumn('details', function($data){
                  return  '<span class="text-muted">Phone number:</span> '.$data->phonenumber;
              })
              ->addColumn('results', function($data){
                  if ($data->status == PhoneValidation::SUCCESS) {
                    $detail = '<span class="text-muted">Surname:</span> '.$data->phone_surname
                      .'<br><span class="text-muted">First name:</span> '.$data->phone_firstname
                      .'<br><span class="text-muted">Middle name:</span> '.$data->phone_middlename
                      .'<br><span class="text-muted">Status:</span> '.$data->phone_status;
                  }
                  elseif ($data->status == PhoneValidation::FAIL) {
                    $detail = 'NO RESULTS';
                  } 
                  else {
                    $detail = '---';
                  }                 

                  return $detail;
              })
              ->addColumn('_status', function($data){
                if ($data->status == PhoneValidation::PEND) {
                  return '<span class="text-warning process-status">'.PhoneValidation::PEND.'</span>';
                }
                elseif ($data->status == PhoneValidation::FAIL) {
                  return '<span class="text-danger process-status">'.PhoneValidation::FAIL.'</span><br><span class="text-muted">'.$data->error_message.'</span>';
                }
                elseif($data->status == PhoneValidation::SUCCESS){
                  return '<span class="text-success process-status">'.PhoneValidation::SUCCESS.'</span>';
                }
                else{
                  return '---';
                }
              })
              ->addColumn('action', function($data) {
                  $id             = $data->id;
                  $url_view_rights= 'view_'.$this->_permission;

                  if ($data->status == PhoneValidation::SUCCESS)
                  {
                    return '<a href="'.route('validations.phones.show',$id).'">DETAILS</a>';
                  }
              })
              ->rawColumns(['action', 'record', 'details', 'results', '_status'])
              ->make(true);
        }
    }
}