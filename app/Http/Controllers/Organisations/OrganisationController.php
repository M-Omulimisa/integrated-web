<?php
    
namespace App\Http\Controllers\Organisations;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\User;

use App\Models\Users\Role;
use App\Models\Settings\Country;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendNewUserNotification;
use App\Models\Organisations\Organisation;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Organisations\StoreOrganisationRequest;
USE Str;

    
class OrganisationController extends Controller
{
    public $_permission    = "organisations";
    public $_route         = "organisations.organisations";
    public $_dir           = "organisations.organisations";
    public $_menu_group    = "Organisations";
    public $_page_title    = 'Organisations';

    const LOGO_PATH = "public/uploads/organisation_logo";

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
            $countries = Country::select('id', 'name')->get();
            $dialing_codes = Country::pluck('dialing_code', 'dialing_code')->all();
            $organisation_admin = Role::ROLE_ORG_ADMIN; 

            return view($this->_dir.'.create', compact('dialing_codes', 'organisation_admin', 'countries'));
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
    public function store(StoreOrganisationRequest $request)
    {
        $validated =  $request->validated();

        try{

            \DB::beginTransaction();


            ////////////INSERT INTO ORGANISATION  TABLE ////////////////

            $organisation  =  new Organisation();
            $organisation->name  =  $validated['organisation'];
            $organisation->address = $validated['address'];
            $organisation->services = $validated['services'];
            $organisation->save();

            ////////////////////ATTACH SELECTED COUNTIRES TO THE ORGANISATION /////////////////////////////

            foreach($validated['country_id'] as $country){      

                    DB::table('country_organisation')->insert([

                        [
                            'id' => Str::orderedUuid(),
                            'country_id' => $country, 
                            'organisation_id' => $organisation->id, 
                        ]
                    ]);
                }

            //////////// GET THE COUNTRY ID OF THE ADMINSITRATOR BASED OFF THE DIALING CODE ////////////////

            $country = Country::select('id')->where('dialing_code', $request->dialing_code)->first();

            /////////// INSERT INTO THE ADMINISTRATIR DETAILS INTO THE USERS TABLE

            $user = new User();
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'];
            $user->organisation_id = $organisation->id;
            $user->password  = $validated['password'];
            $user->status = $validated['status'];
            $user->country_id =  $country->id;
            $user->created_by = $validated['created_by'];
            $user->save();

            ///////////////// ASSIGN THE ADMINSTRATOR USER WITH THE CORRECT ROLE ////////////////////////////

            $user->assignRole($validated['roles']);

            \DB::commit();

            ////////////////////////SEND OUT AN EMAIL NOTIFICATION //////////////////////////////////

            Notification::route('mail', $validated['email'])->notify(new SendNewUserNotification($request));

            return response()->json(['message' => 'Organisation created successfully']);

        }
        catch(\Exception $e){
       
            \DB::rollBack();

            $error_message = array('server_error' => array( $e->getMessage() ));
            return response()->json([
                'message' => 'The given data was invalid',
                'errors' => $error_message
            ], 422);
        
        }

        

        // try {           

        //     $organisation = [
        //         'name' => $request->organisation,
        //         'address' => $request->address,
        //         'services' => $request->services
        //     ];

        //     $organisation = Organisation::create($organisation);

        //     if ($organisation) {

        //         $country = Country::where('dialing_code', $request->dialing_code)->first();

        //         $user = [
        //             'name' => $request->name,
        //             'email' => $request->email,
        //             'dialing_code' => $request->dialing_code,
        //             'phone' => $request->phone,
        //             'organisation_id' => $organisation->id,
        //             'password' => $request->password, 
        //             'status' => $request->status == "1" ? "Active" : "Suspended",
        //             'country_id' => $country->id,
        //             'created_by' => auth()->user()->id
        //         ];

        //         $user = User::create($user);
        //         $user->assignRole($request->input('roles'));

        //         Notification::route('mail', $request->email)->notify(new SendNewUserNotification($request));
        //     }

        //     return redirect()->route($this->_route.'.index')->with('success','Operation successfully');

        // } catch (\Throwable $r) {
        //     return redirect()->back()->withErrors($r->getMessage())->withInput();
        // }    
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
            $organisation = Organisation::findOrFail($id);
            return view($this->_dir.'.edit', compact('organisation'));
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
            $organisation = Organisation::findOrFail($id);
            $organisation->update($request->all());          
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
            $data = Organisation::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ])->orderBy('name', 'ASC');
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('id', function($data) {
                    return $data->id;
                  })
                ->addColumn('admin', function($data) {
                    return $data->administrator()->name ?? null;
                  })
                ->addColumn('logo', function($data) {
                    return '';
                  })
                ->addColumn('action', function($data){
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = 'manage_'.$this->_permission;
                    // $delete  = 'delete_'.$this->_permission;
                    // $view    = 'view_'.$this->_permission;
                    return view('partials.actions', compact('route','id','manage'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**Get resolved file url
     * @param string $file_name File name on disk.
     * @return string
     */
    public static function logoUrl(string $file_name): string
    {
        return 'storage/uploads/organisation_logo/'.$file_name;
    }

    /**
     * Delete file
     * @param string $file_name
     * @return bool
     */
    public static function deleteLogo(string $file_name): bool
    {
        return Storage::delete(self::LPO_SIGNATURE_PATH . '/' . $file_name);
    }


    public function getOrganisationsByCountry($country_id){

        $organisations = Organisation::select('organisations.id', 'organisations.name')->whereHas('countries', function($q) use($country_id) {

            $q->where('countries.id', '=', $country_id);

        })->get();

        return response()->json(['items' => $organisations]);
        
    }
}