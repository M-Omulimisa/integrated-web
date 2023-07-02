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
use App\Models\Loans\Distributor;
use App\Models\Settings\Location;
use App\Models\Settings\Enterprise;
use App\Models\Settings\AgroProduct;
use App\Models\Loans\DistributorEnterprise;
use App\Models\Loans\DistributorAgroProduct;
use App\Models\Loans\DistributorEnterpriseVariety;
use App\Models\Loans\DistributorEnterpriseType;
use App\Models\Settings\EnterpriseVariety;
use App\Models\Settings\EnterpriseType;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
    
class DistributorController extends Controller
{
    public $_permission    = "input-loans";
    public $_route         = "input-loans.distributors";
    public $_dir           = "input_loans.distributors";
    public $_menu_group    = "Distributors";
    public $_page_title    = 'Distributors';

    const LOGO_PATH = "public/uploads/distributor_logo";

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
            $distributor_admin = Role::ROLE_DISTR_ADMIN; 
            $locations = Location::pluck('name', 'id')->all();
            $enterprises = Enterprise::get();
            $products = AgroProduct::get();
            $countries = Country::pluck('name', 'id')->all();

            return view($this->_dir.'.create', compact('dialing_codes', 'distributor_admin', 'locations', 'enterprises', 'products', 'countries'));
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
            'file' => 'nullable|mimes:png,jpg,jpeg|max:5120',
            "country_id" => "required",
            "distributor_name" => "required",
            "enterprises" => "required",
            "varieties" => "required",
            "location_id" => "required",
            "contact_person_name" => "required",
            "contact_person_phone_number" => "required",
            "name" => "required",
            "email" => "required",
            "phone_number" => "required",
            "password" => "required",
            "roles" => "required",
            "dialing_code" => "required|exists:countries,dialing_code",
        ]);

        try {  

            if ($request->hasFile('file')) {
                $image = $this->uploadLogo('file');
                if (! $image) {
                    return redirect()->back()->withErrors(trans('strings.file_upload_failed'));
                }
            }

            $distributor = Distributor::create($request->all() + [
                'logo' => ($image ?? null), 
                'contact_person_phone' => $request->dialing_code.$request->contact_person_phone_number
            ]);

            if ($distributor && count($request->enterprises) > 0) {
                for ($i=0; $i < count($request->enterprises); ++$i) {
                      DistributorEnterprise::create([
                        'distributor_id' => $distributor->id,
                        'enterprise_id' => $request->enterprises[$i]
                      ]);
                  }                
            }

            if ($distributor && count($request->varieties) > 0) {
                for ($i=0; $i < count($request->varieties); ++$i) {
                      DistributorEnterpriseVariety::create([
                        'distributor_id' => $distributor->id,
                        'enterprise_variety_id' => $request->varieties[$i]
                      ]);
                      $this->fillEnterprise($distributor->id, $request->varieties[$i]);
                  }                
            }

            if ($distributor && count($request->types) > 0) {
                for ($i=0; $i < count($request->types); ++$i) {
                      DistributorEnterpriseType::create([
                        'distributor_id' => $distributor->id,
                        'enterprise_type_id' => $request->types[$i]
                      ]);
                      $this->fillVariety($distributor->id, $request->types[$i]);
                  }                
            }

            if ($distributor && count($request->products) > 0) {
                for ($i=0; $i < count($request->products); ++$i) {
                      DistributorAgroProduct::create([
                        'distributor_id' => $distributor->id,
                        'agro_product_id' => $request->products[$i]
                      ]);
                  }                
            }

            if ($distributor) {

                $user = User::create($request->all() + [
                    'distributor_id' => $distributor->id, 
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
            $distributor = Distributor::findOrFail($id);
            $locations = Location::pluck('name', 'id')->all();
            $enterprises = Enterprise::pluck('name', 'id')->all();
            $products = AgroProduct::pluck('name', 'id')->all();
            $countries = Country::pluck('name', 'id')->all();

            return view($this->_dir.'.edit', compact('distributor', 'locations', 'enterprises', 'products', 'countries'));
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
            $distributor = Distributor::findOrFail($id);
            $distributor->update($request->all());          
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
            $data = Distributor::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ]);
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            $data->orderBy('created_at', 'DESC');

            return $datatables
                ->addIndexColumn()
                ->addColumn('distributor', function($data) {

                    $src = is_null($data->logo) ? 'assets/images/logo-dummy-img.jpg' : $this->logoUrl($data->logo);

                    $logo = '<div class="d-flex align-items-center">
                        <div class="avatar-md bg-light rounded p-1 me-2">
                            <img src="'.asset($src).'" alt="" class="img-fluid d-block">
                        </div>
                        <div>
                            <h5 class="fs-14 my-1"><a href="#" class="text-reset">'.$data->distributor_name.'</a></h5>
                            <span class="text-muted">'.$data->contact_person_name.'</span>
                            <span class="text-muted">'.$data->contact_person_phone.'</span>
                        </div>
                    </div>';
                    return $logo;
                  })
                ->addColumn('address', function($data) {
                    $distributor = '';
                    if(!is_null($data->address)) $distributor .= '<span class="text-muted">Address:</span> '.$data->address.'<br>';
                    $distributor .= '<span class="text-muted">Location:</span> '.$data->location->name.'<br>';
                    $distributor .= '<span class="text-muted">Country:</span> '.$data->location->country->name;
                    return $distributor;
                  })
                ->addColumn('admin', function($data) {
                    $distributor = '';
                    if (isset($data->administrator()->name)) {
                        $distributor .= $data->administrator()->name.'<br>';
                        $distributor .= $data->administrator()->email.'<br>';
                        $distributor .= $data->administrator()->phone;
                    }
                    return $distributor;
                  })
                ->addColumn('enterprises', function($data) {
                    $list = '';
                    if (isset($data->enterprises) && count($data->enterprises) > 0) {
                        foreach ($data->enterprises as $enterprise) {
                            $list .= '-'.$enterprise->enterprise->name.'<br>';

                            $varieties = $data->enterprise_distributor_variety($enterprise->enterprise_id);
                            if (isset($varieties) && count($varieties) > 0) {
                                foreach ($varieties as $variety) {
                                    $list .= '<span style="margin-left:20px"></span>+'.$variety->name.'<br>';

                                    $types = $data->variety_distributor_type($variety->id);
                                    if (isset($types) && count($types) > 0) {
                                        foreach ($types as $type) {
                                            $list .= '<span style="margin-left:30px"></span>#'.$type->name.'<br>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                    return $list;
                  })
                ->addColumn('products', function($data) {
                    $list = '';
                    if (isset($data->products) && count($data->products) > 0) {
                        foreach ($data->products as $product) {
                            $list .= '-'.$product->agro_product->name.'<br>';
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
                ->rawColumns(['action', 'admin', 'distributor', 'address', 'enterprises', 'products'])
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
        return 'storage/uploads/distributor_logo/'.$file_name;
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

    public function fillEnterprise($distributor_id, $variety_id)
    {
        $variety = EnterpriseVariety::whereId($variety_id)->first();

        if (! DistributorEnterprise::whereDistributorId($distributor_id)->whereEnterpriseId($variety->enterprise_id)->first()) {
            DistributorEnterprise::create([
                'distributor_id' => $distributor_id,
                'enterprise_id' => $variety->enterprise_id
            ]);
        }
    }

    public function fillVariety($distributor_id, $type_id)
    {
        $type = EnterpriseType::whereId($type_id)->first();

        if (! DistributorEnterpriseVariety::whereDistributorId($distributor_id)->whereEnterpriseVarietyId($type->enterprise_variety_id)->first()) {
            DistributorEnterpriseVariety::create([
                'distributor_id' => $distributor_id,
                'enterprise_variety_id' => $type->enterprise_variety_id
            ]);
            $this->fillEnterprise($distributor_id, $type->enterprise_variety_id);
        }
    }
}