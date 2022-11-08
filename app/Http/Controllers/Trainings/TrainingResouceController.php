<?php
    
namespace App\Http\Controllers\Trainings;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Image;
use App\Models\Training\TrainingResource;
use App\Models\Settings\Language;
use App\Models\Settings\Country;
use App\Models\Settings\Enterprise;
use App\Models\Training\TrainingResourceLanguage;
use App\Models\Training\TrainingResourceEnterprise;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
    
class TrainingResouceController extends Controller
{
    public $_permission    = "training_resources";
    public $_route         = "trainings.resources";
    public $_dir           = "trainings.resources";
    public $_menu_group    = "Training";
    public $_page_title    = 'Resources';

    const THUMBNAIL_PATH = "public/uploads/resource_thumbs";

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
            $languages = Language::pluck('name', 'id')->all();
            $countries = Country::pluck('name', 'id')->all();
            $enterprises = Enterprise::pluck('name', 'id')->all();
            return view($this->_dir.'.create', compact('languages', 'countries', 'enterprises'));
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
            'heading' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg|max:5120',
            'languages' => 'required',
            'enterprises' => 'required'
        ]);

        try { 

            $thumbnail = $this->uploadThumbnail('image');
            if (! $thumbnail) {
                return redirect()->back()->withErrors(trans('strings.file_upload_failed'));
            }

            $resource = TrainingResource::create($request->all() + ['thumbnail' => $thumbnail]);

            if ($resource) {
                for ($i=0; $i < count($request->languages); ++$i) {
                      TrainingResourceLanguage::create([
                        'resource_id' => $resource->id,
                        'language_id' => $request->languages[$i]
                      ]);
                  } 

                  for ($i=0; $i < count($request->enterprises); ++$i) {
                      TrainingResourceEnterprise::create([
                        'resource_id' => $resource->id,
                        'enterprise_id' => $request->enterprises[$i]
                      ]);
                  }                
            }

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
            $resource = TrainingResource::findOrFail($id);

            return view($this->_dir.'.show', compact('resource'));
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
            $resource = TrainingResource::findOrFail($id);             
            $languages = Language::pluck('name', 'id')->all();
            $countries = Country::pluck('name', 'id')->all();
            $enterprises = Enterprise::pluck('name', 'id')->all();
            return view($this->_dir.'.edit', compact('resource', 'languages', 'countries', 'enterprises'));
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
            // 'name' => 'required',
            // 'email' => 'required|email|unique:users,email,'.$id,
            // 'password' => 'same:confirm-password',
            // 'roles' => 'required'
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
            $data = TrainingResource::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ]);
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('image_heading', function($data) {
                    $rsc = '<img width="200px" src="'.asset($this->thumbnailUrl($data->thumbnail)).'" /><br>';
                    $rsc .= $data->heading;
                    return $rsc;
                  })
                ->addColumn('languages', function($data) {
                    $list = '';
                    if (isset($data->languages) && count($data->languages) > 0) {
                        foreach ($data->languages as $language) {
                            $list .= '-'.$language->language->name.'<br>';
                        }
                    }
                    return $list;
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
                    $delete  = 'delete_'.$this->_permission;
                    $view    = 'list_'.$this->_permission;
                    return view('partials.actions', compact('route','id','manage','view','delete'))->render();
                })
                ->rawColumns(['action', 'image_heading', 'languages', 'enterprises'])
                ->make(true);
        }
    }

    /**
     * Upload file and return file name or false.
     * @param string $file_key The key in the request
     * @return false|string
     */
    public static function uploadThumbnail(string $file_key)
    {
        $image = request()->file($file_key);
        $file_name = time().'.'.$image->getClientOriginalExtension();

        $img = Image::make($image->getRealPath());
        $uploaded = $img->resize(600, 400, function ($constraint) {
            $constraint->aspectRatio();
        })->save(self::thumbnailUrl($file_name));

        $path = self::THUMBNAIL_PATH . '/' . $uploaded->basename;
        // $path = request()->file($file_key)->store(self::THUMBNAIL_PATH);

        if (! Storage::exists($path)) {
            return false;
        }

        return File::basename($path);
    }

    /**Get resolved file url
     * @param string $file_name File name on disk.
     * @return string
     */
    public static function thumbnailUrl(string $file_name): string
    {
        return 'storage/uploads/resource_thumbs/'.$file_name;
    }

    /**
     * Delete file
     * @param string $file_name
     * @return bool
     */
    public static function deleteThumbnail(string $file_name): bool
    {
        return Storage::delete(self::THUMBNAIL_PATH . '/' . $file_name);
    }
}