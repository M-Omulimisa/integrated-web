<?php
    
namespace App\Http\Controllers\Questions;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\User;
use App\Models\Farmers\Farmer;
use App\Models\Settings\Keyword;
use App\Models\Questions\Question;
use App\Models\Questions\QuestionImage;
    
class QuestionController extends Controller
{
    public $_permission    = "questions";
    public $_route         = "questions.questions";
    public $_dir           = "questions.questions";
    public $_menu_group    = "Questions";
    public $_page_title    = 'Questions';

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
            $farmers = Farmer::pluck('first_name', 'id')->all();  
            $keywords = Keyword::where('category', 'Questions')->pluck('name', 'id')->all();      
            return view($this->_dir.'.create', compact('farmers', 'keywords'));
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
            'farmer_id' => 'required',
            'keyword_id' => 'required',
            'body' => 'required',
        ]);

        $question = Question::create($request->all());

        if ($question) {
            // code...
        }

        try {            
            return redirect()->route($this->_route.'.index')
                            ->with('success','Operation was successfully');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
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
            $question = Question::findOrFail($id);
            return view($this->_dir.'.show', compact('question', 'id'));
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
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
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
            $data = Question::select(['*', DB::raw('@DT_RowIndex  := @DT_RowIndex  + 1 AS DT_RowIndex') ])->orderBy('created_at', 'DESC');
            $datatables = app('datatables')->of($data);

            if ($search = $datatables->request->search['value']) {
                // $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return $datatables
                ->addIndexColumn()
                ->addColumn('details', function($data) {
                    return $data->created_at.'<br>Sent via '.$data->sent_via;
                  })
                ->addColumn('images', function($data) {
                    $image = QuestionImage::whereQuestionId($data->id)->first();
                    if ($image) {
                        return '<div class="avatar-sm bg-light rounded p-1 me-2"><img src="assets/images/products/img-5.png" alt="" class="img-fluid d-block"></div>';
                    }
                    return 'None';
                  })
                ->addColumn('keyword', function($data) {
                    return $data->keyword->name;
                  })
                ->addColumn('farmer', function($data) {
                    return $data->farmer->first_name.' '.$data->farmer->last_name.'<br>'.$data->farmer->phone;
                  })
                ->addColumn('response', function($data) {
                    return $data->id;
                  })
                ->addColumn('action', function($data){
                    $route   = $this->_route;
                    $id      = $data->id;
                    $manage  = 'manage_'.$this->_permission;
                    $delete  = 'delete_'.$this->_permission;
                    $view    = 'list_'.$this->_permission;
                    return view('partials.actions', compact('route','id','view'))->render();
                })
                ->rawColumns(['action', 'details', 'images', 'response', 'farmer'])
                ->make(true);
        }
    }
}