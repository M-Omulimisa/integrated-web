<?php

namespace App\Http\Controllers\Elearning;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Elearning\ELearningMessage;

use Illuminate\Support\Facades\Validator;
use App\Models\User;

use Illuminate\Support\Facades\File;
use App\Helpers\MP3File;
use Illuminate\Validation\Rule;

class MessagesController extends Controller
{
    // if (!Gate::allows('add_el_instructions create
    // if (! Gate::allows('view_el_instructions show
    // if (! Gate::allows('delete_el_instructions destroy

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = ELearningMessage::orderBy('numbering', 'ASC')->get();
        return view('e_learning.default_messages.index', compact('messages'));
    } 

       /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('e_learning.default_messages.create');
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
            'default_message'       => 'required',
            'numbering'         => 'required|unique:e_learning_messages',
        ]);  

        try {
            $data = [
                'default_message'       => $request->default_message,
                'numbering'         => $request->numbering,
                'user_id'           => auth()->user()->id
            ];

            if (ELearningMessage::create($data)) {  
              return redirect()->route('e-learning.messages.index')->with('success', 'Operation successful');
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
        $data = ELearningMessage::find($id);
        return view('e_learning.default_messages.show', compact('data'));
    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = ELearningMessage::findOrFail($id);
        return view('e_learning.default_messages.edit', compact('data'));
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
            'default_message'       => 'required',
            'numbering'         => 'required|unique:e_learning_messages,numbering,' . $id,
        ]);

        try {
            if ($data   = ELearningMessage::find($id)) {

              $message = [
                    'default_message'   => $request->default_message,
                    'numbering'         => $request->numbering,
                ];

                if ($data->update($message)) {  
                  return redirect()->route('e-learning.messages.index')->with('success', 'Operation successful');
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
        if($data = ELearningMessage::findOrFail($id)) {

            // code
        } 
        else {
            return redirect()->back()->withErrors('Operation was NOT successful');
        }
    }

    public function massData(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $data = ELearningMessage::select(['*', \DB::raw('@rownum  := @rownum  + 1 AS rownum') ])->orderBy('numbering','ASC');

        $datatables = app('datatables')->of($data);

        return $datatables
        ->addColumn('user', function ($data){
            return $data->user->name;
            })
        ->addColumn('_message', function ($data){
            return $data->default_message;
            })
        ->addColumn('actions', function ($data){
                $entity = "e-learning.messages";
                $id = $data->id;
                $edit_rights = 'edit_el_instructions';
                return view('partials.actions', compact('entity', 'id','edit_rights'))->render();
           
            })
        ->rawColumns(['check', 'actions', 'user','audio', '_message'])
        ->make(true);
    }

}
