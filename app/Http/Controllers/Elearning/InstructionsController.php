<?php

namespace App\Http\Controllers\Elearning;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Elearning\ELearningInstruction;

use Illuminate\Support\Facades\Validator;
use App\Models\User;

use Illuminate\Support\Facades\File;
use App\Helpers\MP3File;
use Illuminate\Validation\Rule;

class InstructionsController extends Controller
{
    public $_permission    = "elearning-settings";
    public $_route         = "e-learning.instructions";
    public $_dir           = "e_learning.default_instructions";
    public $_menu_group    = "E-Learning";
    public $_page_title    = 'E-Learning Settings';
        
    // add_el_instructions create
    // view_el_instructions show
    // delete_el_instructions delete

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $instructions = ELearningInstruction::orderBy('numbering', 'ASC')->get();
        return view($this->_dir.'.index', compact('instructions'));
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
     * Store the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'instruction'       => 'required',
            'numbering'         => 'required|unique:e_learning_instructions',
            'audio'             => 'required|file|mimes:mp3,mpga|max:5120',
        ]);  

        try {
            $audio = null;
            if ($request->hasFile('audio')){
                $file = $request->audio;

                $audio = $file->store('instructions', 'uploads');     
            }

            $data = [
                'instruction'       => $request->instruction,
                'numbering'         => $request->numbering,
                'default_audio_url' => $audio,
                'user_id'           => auth()->user()->id
            ];

            if (ELearningInstruction::create($data)) {  
              return redirect()->route('e-learning.instructions.index')->with('success', 'Instruction successfully created');
            }
            else{
              return redirect()->back()->withErrors('Resource NOT Created')->withInput();
            }

        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        } 
    }

    public function show($id)
    {
        $data = ELearningInstruction::find($id);
        return view($this->_dir.'.show', compact('data'));
    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = ELearningInstruction::findOrFail($id);
        return view($this->_dir.'.edit', compact('data'));
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
            'instruction'       => 'required',
            'numbering'         => 'required|unique:e_learning_instructions,numbering,' . $id,
        ]);

        try {
            if ($data   = ELearningInstruction::find($id)) {

                $audio = $data->audio_url;
                    
                if ($request->hasFile('audio')){

                    $this->validate($request, [
                        'audio' => 'required|file|mimes:mp3,mpga|max:5120',
                    ]); 

                    $file = $request->audio;
                
                    if(!is_null($audio)){
                        if (file_exists('uploads/'.$audio)) {
                            File::delete('uploads/'.$audio);
                        }
                    }
                    $audio = $file->store('instructions', 'uploads');      
                }

              $instruction = [
                    'instruction'       => $request->instruction,
                    'numbering'         => $request->numbering,
                    'default_audio_url' => $audio,
                ];

                if ($data->update($instruction)) {  
                  return redirect()->route('e-learning.instructions.index')->with('success', 'Instruction successfully updated');
                }
                else{
                  return redirect()->back()->withErrors('Resource NOT Updated')->withInput();
                }
            }
            else{
              return redirect()->back()->withErrors('Resource NOT Found')->withInput();
            }

        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        } 
    }

    public function destroy($id)
    {
        if($data = ELearningInstruction::findOrFail($id)) {

            // code
        } 
        else {
            return redirect()->back()->withErrors('Operation was NOT successful');
        }
    }

    public function massData(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $data = ELearningInstruction::select(['*', \DB::raw('@rownum  := @rownum  + 1 AS rownum') ])->orderBy('numbering','ASC');

        $datatables = app('datatables')->of($data);

        return $datatables
        ->addColumn('user', function ($data){
            return $data->user->name;
            })
        ->addColumn('_instruction', function ($data){
            return $data->instruction;
            })
        ->addColumn('audio', function ($data){
            return '<audio src="'.asset('uploads/'.$data->default_audio_url).'" controls></audio>';
            })
        ->addColumn('actions', function ($data){
                $entity = "e-learning.instructions";
                $id = $data->id;
                $edit_rights = 'edit_el_instructions';
                return view('partials.actions', compact('entity', 'id','edit_rights'))->render();
           
            })
        ->rawColumns(['check', 'actions', 'user','audio', '_instruction'])
        ->make(true);
    }

}
