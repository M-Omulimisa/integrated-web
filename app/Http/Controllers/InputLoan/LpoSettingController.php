<?php
    
namespace App\Http\Controllers\InputLoan;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\User;
use App\Models\Loans\LpoSetting;
use App\Models\Settings\Country;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
    
class LpoSettingController extends Controller
{
    public $_permission    = "input-loans";
    public $_route         = "input-loans.lpo-settings";
    public $_dir           = "input_loans.lpo_settings";
    public $_menu_group    = "Input Loans";
    public $_page_title    = 'LPO Settings';

    const LPO_SIGNATURE_PATH = "public/uploads/lpo_signature";

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
            return view($this->_dir.'.create', compact('countries'));
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
            'name' => 'required',
            'file' => 'required|mimes:png,jpg,jpeg|max:5120',
            'title' => 'required',
            'notes' => 'required'
        ]);

        try {  

            $image = $this->uploadSignature('file');
            if (! $image) {
                return redirect()->back()->withErrors(trans('strings.file_upload_failed'));
            }

            LpoSetting::create($request->all() + ['signature' => $image]);

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
            $data = LpoSetting::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ]);
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('lpo_details', function($data) {
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = 'manage_'.$this->_permission;
                    $delete  = 'delete_'.$this->_permission;
                    $view    = 'view_'.$this->_permission;

                    $lpo = '<span class="text-muted">Purchaser:</span> '.$data->name.'<br>';
                    $lpo .= '<span class="text-muted">Title:</span> '.$data->title.'<br>';
                    $lpo .= '<span class="text-muted">Notes/Comments:<br></span> '.$data->notes.'<br>';
                    $lpo .= '<span class="text-muted">Approver\'s Signature:</span><img width="100px" src="'.asset($this->signatureUrl($data->signature)).'" /><br><br>';
                    $lpo .= view('partials.actions', compact('route','id','manage','view','delete'))->render();
                    return $lpo;
                  })
                ->rawColumns(['lpo_details'])
                ->make(true);
        }
    }

    /**
     * Upload file and return file name or false.
     * @param string $file_key The key in the request
     * @return false|string
     */
    public static function uploadSignature(string $file_key)
    {
        $path = request()->file($file_key)->store(self::LPO_SIGNATURE_PATH);

        if (! Storage::exists($path)) {
            return false;
        }

        return File::basename($path);
    }

    /**Get resolved file url
     * @param string $file_name File name on disk.
     * @return string
     */
    public static function signatureUrl(string $file_name): string
    {
        return 'storage/uploads/lpo_signature/'.$file_name;
    }

    /**
     * Delete file
     * @param string $file_name
     * @return bool
     */
    public static function deleteSignature(string $file_name): bool
    {
        return Storage::delete(self::LPO_SIGNATURE_PATH . '/' . $file_name);
    }
}