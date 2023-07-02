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
use App\Models\Loans\Buyer;
use App\Models\Settings\Location;
use App\Models\Settings\Enterprise;
use App\Models\Loans\BuyerEnterprise;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
    
class BuyerController extends Controller
{
    public $_permission    = "input-loans";
    public $_route         = "input-loans.buyers";
    public $_dir           = "input_loans.buyers";
    public $_menu_group    = "Buyers";
    public $_page_title    = 'Buyers';

    const LOGO_PATH = "public/uploads/buyer_logo";

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
            $dialing_codes = Country::pluck('dialing_code', 'dialing_code')->all();
            $buyer_admin = Role::ROLE_BUYER_ADMIN; 
            $locations = Location::pluck('name', 'id')->all();
            $enterprises = Enterprise::get();
            $countries = Country::pluck('name', 'id')->all();

            return view($this->_dir.'.create', compact('dialing_codes', 'buyer_admin', 'locations', 'enterprises', 'countries'));
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
            'buyer_name' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'roles' => 'required',
            'dialing_code' => 'required',
            'phone_number' => 'required',
            'status' => 'required',
            'contact_person_name' => 'required',
            'contact_person_phone_number' => 'required',
            'location_id' => 'required',
            'file' => 'nullable|mimes:png,jpg,jpeg|max:5120',
            'enterprises' => 'required'
        ]);

        try { 

            if ($request->hasFile('file')) {
                $image = $this->uploadLogo('file');
                if (! $image) {
                    return redirect()->back()->withErrors(trans('strings.file_upload_failed'));
                }
            }

            $buyer = Buyer::create($request->all() + [
                'logo' => ($image ?? null), 
                'contact_person_phone' => $request->dialing_code.$request->contact_person_phone_number
            ]);

            if ($buyer && count($request->enterprises) > 0) {
                for ($i=0; $i < count($request->enterprises); ++$i) {
                   BuyerEnterprise::create([
                        'buyer_id' => $buyer->id,
                        'enterprise_id' => $request->enterprises[$i]
                    ]);
                  }                
            }

            if ($buyer) {

                $user = User::create($request->all() + [
                    'buyer_id' => $buyer->id, 
                    'phone' => $request->dialing_code.$request->phone_number
                ]);
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
            $buyer = Buyer::findOrFail($id);
            $locations = Location::pluck('name', 'id')->all();
            $enterprises = Enterprise::pluck('name', 'id')->all();
            $countries = Country::pluck('name', 'id')->all();

            return view($this->_dir.'.edit', compact('buyer', 'locations', 'enterprises', 'countries'));
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
            // 'enterprises' => 'required',
            'contact_person' => 'required',

        ]);
    
        try { 
            $buyer = Buyer::findOrFail($id);
            $buyer->update($request->all());          
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
            $data = Buyer::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ]);
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            $data->orderBy('created_at', 'DESC');

            return $datatables
                ->addIndexColumn()
                ->addColumn('buyer', function($data) {

                    $src = is_null($data->logo) ? 'assets/images/logo-dummy-img.jpg' : $this->logoUrl($data->logo);

                    $logo = '<div class="d-flex align-items-center">
                        <div class="avatar-md bg-light rounded p-1 me-2">
                            <img src="'.asset($src).'" alt="" class="img-fluid d-block">
                        </div>
                        <div>
                            <h5 class="fs-14 my-1"><a href="#" class="text-reset">'.$data->buyer_name.'</a></h5>
                            <span class="text-muted">'.$data->contact_person_name.'</span>
                            <span class="text-muted">'.$data->contact_person_phone.'</span>
                        </div>
                    </div>';
                    return $logo;
                  })
                ->addColumn('address', function($data) {
                    $buyer = '';
                    if(!is_null($data->address)) $buyer .= '<span class="text-muted">Address:</span> '.$data->address.'<br>';
                    $buyer .= '<span class="text-muted">Location:</span> '.$data->location->name.'<br>';
                    $buyer .= '<span class="text-muted">Country:</span> '.$data->location->country->name;
                    return $buyer;
                  })
                ->addColumn('admin', function($data) {
                    $buyer = $data->administrator()->name.'<br>';
                    $buyer .= $data->administrator()->email.'<br>';
                    $buyer .= $data->administrator()->phone;
                    return $buyer;
                  })
                ->addColumn('enterprises', function($data) {
                    $list = '';
                    if (isset($data->enterprises) && count($data->enterprises) > 0) {
                        foreach ($data->enterprises as $enterprise) {
                            $list .= '-'.$enterprise->enterprise->name.'<br>';
                        }
                    }
                    return $list;
                  })
                ->addColumn('action', function($data){
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = 'manage_'.$this->_permission;
                    // $delete  = 'delete_'.$this->_permission;
                    // $view    = 'view_'.$this->_permission;
                    return view('partials.actions', compact('route','id','manage'))->render();
                })
                ->rawColumns(['action', 'admin', 'buyer', 'address', 'enterprises'])
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
        return 'storage/uploads/buyer_logo/'.$file_name;
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