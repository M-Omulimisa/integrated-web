<?php
    
namespace App\Http\Controllers\Trainings;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\Training\TrainingResource;
use App\Models\Training\TrainingResourceSection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
    
class TrainingResouceSectionController extends Controller
{
    public $_permission    = "training_resources";
    public $_route         = "trainings.resources";
    public $_dir           = "trainings.sections";
    public $_menu_group    = "Training";
    public $_page_title    = 'Resource Sections';

    const IMAGE_PATH = "public/uploads/resource_images";

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
    public function create($resource_id)
    {
        try { 
            $resource = TrainingResource::findOrFail($resource_id);
            return view($this->_dir.'.create', compact('resource'));
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
            'resource_id' => 'required',
            'subheading' => 'nullable',
            'file' => 'nullable|mimes:png,jpg,jpeg|max:5120',
            'details' => 'required',
        ]);

        try { 


            $image = NULL;
            if ($request->hasFile('file')) {
                $image = $this->uploadImage('file');
                if (! $image) {
                    return redirect()->back()->withErrors(trans('strings.file_upload_failed'));
                }
            }

            TrainingResourceSection::create($request->all() + ['image' => $image]);

            return redirect()->route('trainings.resources.show', $request->resource_id)
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
    { }

    /**
     * Upload file and return file name or false.
     * @param string $file_key The key in the request
     * @return false|string
     */
    public static function uploadImage(string $file_key)
    {
        $path = request()->file($file_key)->store(self::IMAGE_PATH);

        if (! Storage::exists($path)) {
            return false;
        }

        return File::basename($path);
    }

    /**Get resolved file url
     * @param string $file_name File name on disk.
     * @return string
     */
    public static function imageUrl(string $file_name): string
    {
        return 'storage/uploads/resource_images/'.$file_name;
    }

    /**
     * Delete file
     * @param string $file_name
     * @return bool
     */
    public static function deleteImage(string $file_name): bool
    {
        return Storage::delete(self::IMAGE_PATH . '/' . $file_name);
    }
}