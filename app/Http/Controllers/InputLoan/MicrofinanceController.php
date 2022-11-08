<?php
    
namespace App\Http\Controllers\InputLoan;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\User;

use App\Models\Users\Role;
use App\Models\Settings\Country;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendNewUserNotification;
use App\Models\Loans\Microfinance;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
    
class MicrofinanceController extends Controller
{
    public $_permission    = "input-loans";
    public $_route         = "input-loans.microfinances";
    public $_dir           = "input_loans.microfinances";
    public $_menu_group    = "Microfinances";
    public $_page_title    = 'Microfinances';

    const LOGO_PATH = "public/uploads/microfinance_logo";

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
            $microfinance_admin = Role::ROLE_MICROFIN_ADMIN; 

            return view($this->_dir.'.create', compact('countries', 'microfinance_admin'));
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
            'password' => 'required',
            'roles' => 'required',
            'dialing_code' => 'required',
            'phone_number' => 'required',
            'status' => 'required'
        ]);

        try {            
            $microfinance = [
                'name' => $request->microfinance,
                'address' => $request->address,
                'services' => $request->services
            ];

            $microfinance = Microfinance::create($microfinance);

            if ($microfinance) {

                $country = Country::where('dialing_code', $request->dialing_code)->first();

                $user = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->dialing_code.$request->phone_number,
                    'microfinance_id' => $microfinance->id,
                    'password' => $request->password, 
                    'status' => $request->status,
                    'country_id' => $country->id,
                    'created_by' => auth()->user()->id
                ];

                $user = User::create($user);
                $user->assignRole($request->input('roles'));

                Notification::route('mail', $request->email)->notify(new SendNewUserNotification($request));
            }

            return redirect()->route($this->_route.'.index')->with('success','Operation successfully');

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
            $microfinance = Microfinance::findOrFail($id);
            return view($this->_dir.'.edit', compact('microfinance'));
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
            'address' => 'required',
            'services' => 'required'
        ]);
    
        try { 
            $microfinance = Microfinance::findOrFail($id);
            $microfinance->update($request->all());          
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
            $data = Microfinance::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ])->orderBy('name', 'ASC');
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('microfinance', function($data) {

                    $src = is_null($data->logo) ? 'assets/images/logo-dummy-img.jpg' : $this->logoUrl($data->logo);

                    $logo = '<div class="d-flex align-items-center">
                        <div class="avatar-md bg-light rounded p-1 me-2">
                            <img src="'.asset($src).'" alt="" class="img-fluid d-block">
                        </div>
                        <div>
                            <h5 class="fs-14 my-1"><a href="#" class="text-reset">'.$data->name.'</a></h5>
                            <span class="text-muted">'.$data->address.'</span>
                        </div>
                    </div>';
                    return $logo;
                  })
                ->addColumn('admin', function($data) {
                    $admin = '';
                    if (isset($data->administrator()->name)) {
                        $admin .= $data->administrator()->name.'<br>';
                        $admin .= $data->administrator()->email.'<br>';
                        $admin .= $data->administrator()->phone;
                    }
                    return $admin;
                  })
                ->addColumn('action', function($data){
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = 'manage_'.$this->_permission;
                    // $delete  = 'delete_'.$this->_permission;
                    // $view    = 'view_'.$this->_permission;
                    return view('partials.actions', compact('route','id','manage'))->render();
                })
                ->rawColumns(['action', 'admin', 'microfinance'])
                ->make(true);
        }
    }

    /**
     * Upload file and return file name or false.
     * @param string $file_key The key in the request
     * @return false|string
     */
    public static function uploadLogo(string $file_key)
    {
        $path = request()->file($file_key)->store(self::LOGO_PATH);

        if (! Storage::exists($path)) {
            return false;
        }

        return File::basename($path);
    }

    /**Get resolved file url
     * @param string $file_name File name on disk.
     * @return string
     */
    public static function logoUrl(string $file_name): string
    {
        return 'storage/uploads/microfinance_logo/'.$file_name;
    }

    /**
     * Delete file
     * @param string $file_name
     * @return bool
     */
    public static function deleteLogo(string $file_name): bool
    {
        return Storage::delete(self::LOGO_PATH . '/' . $file_name);
    }
}