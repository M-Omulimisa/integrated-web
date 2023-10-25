<?php

namespace App\Http\Controllers\Elearning;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Settings\Language;
use App\Models\Elearning\ELearningChapter;
use App\Models\Elearning\ELearningCourse;
use App\Models\Elearning\ELearningResource;
use App\Models\Elearning\ELearningResourceView;
use App\Models\Elearning\ELearningResourceTopic;
use App\Models\Elearning\ELearningResourceTopicResponse;
use App\Models\Elearning\ELearningResourceSubscription;
use App\Models\Elearning\ELearningResourceTopicLike;
use App\Models\Elearning\ELearningResourceTopicResponseLike;

use Illuminate\Support\Facades\Validator;
use App\Models\User;

use Illuminate\Support\Facades\File;
use App\Helpers\MP3File;
use Illuminate\Validation\Rule;

class ResourcesController extends Controller
{
    // if (! Gate::allows('view_el_resources single
    // if (!Gate::allows('add_el_resources create
    // if (! Gate::allows('add_el_resources store
    // if (! Gate::allows('view_el_resources show
    // if (! Gate::allows('delete_el_resources destroy
    // if (! Gate::allows('add_el_announcement_subscriptions subscribeResource

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function board($courseId)
    {
        $course = ELearningCourse::find($courseId);
        $resources = ELearningResource::orderBy('created_at', 'DESC')->get();
        return view('e_learning.resources.sources', compact('course', 'resources'));
    }

    public function single($id)
    {
        $announcement = ELearningResource::find($id);
        $course = ELearningCourse::find($announcement->course_id);

        if (!$announcement->hasBeenRead()) {
            ELearningResourceView::create([
                'resource_id' => $id,
                'user_id' => auth()->user()->id
            ]);
        }
        return view('e_learning.resources.sourve', compact('course', 'announcement'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($courseId)
    {
        $course = ELearningCourse::find($courseId);
        return view('e_learning.resources.index', compact('course'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($courseId)
    {
        $status     = ELearningResource::status;
        $course     = ELearningCourse::find($courseId);

        return view('e_learning.resources.create', compact('status', 'course'));
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
            'title'     => 'required|max:255',
            'audio'     => 'nullable|file|mimes:mp3,mpga|max:5120',
            'user_id'   => 'required',
            'course_id' => 'required',
            'body'      => 'required',
            'start_date' => 'nullable',
            'end_date'  => 'nullable',
            'display_days' => 'nullable|numeric',
            'user_id' => 'required'
        ]);

        try {
            $audio = null;
            if ($request->hasFile('audio')) {
                $file = $request->audio;

                $audio = $file->store('resources', 'courses');
            }

            $data = [
                'title'             => $request->title,
                'body'              => $request->body,
                'course_id'         => $request->course_id,
                'display_days'      => $request->display_days,
                'start_date'        => $request->start_date,
                'end_date'          => $request->end_date,
                'user_id'           => $request->user_id
            ];

            if (isset($audio)) {
                // 'audio_url'         => $audio,
            }

            if (ELearningResource::create($data)) {
                return redirect('e-learning/courses/resources/' . $request->course_id)->with('success', 'Resource successfully created');
            } else {
                return redirect()->back()->withErrors('Resource NOT Created')->withInput();
            }
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $data = ELearningResource::find($id);
        $course = ELearningCourse::find($data->course_id);

        return view('e_learning.resources.show', compact('data', 'course'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = ELearningResource::findOrFail($id);
        $status = ELearningResource::status;
        $course = ELearningCourse::where('id', $data->course_id)->first();
        return view('e_learning.resources.edit', compact('data', 'status', 'course'));
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
            'title'     => 'required|max:255',
            'audio'     => 'nullable|file|mimes:mp3,mpga|max:5120',
            'status'    => 'required',
            'body'      => 'required',
            'start_date' => 'nullable',
            'end_date'  => 'nullable',
            'display_days' => 'nullable|numeric',
        ]);

        try {
            if ($data   = ELearningChapter::find($id)) {

                $audio = $data->audio_url;

                if ($request->hasFile('audio')) {
                    $file = $request->audio;

                    if (!is_null($audio)) {
                        if (file_exists('uploads/courses/' . $audio)) {
                            File::delete('uploads/courses/' . $audio);
                        }
                    }
                    $audio = $file->store('resources', 'courses');
                }

                $chapter = [
                    'title'             => $request->title,
                    'body'              => $request->body,
                    'display_days'      => $request->display_days,
                    'start_date'        => $request->start_date,
                    'end_date'          => $request->end_date,
                    'status'            => $request->status
                ];

                if (isset($audio)) {
                    // 'audio_url'         => $audio,
                }

                if ($data->update($chapter)) {
                    return redirect('e-learning/courses/resources/' . $data->course_id)->with('success', 'Resource successfully updated');
                } else {
                    return redirect()->back()->withErrors('Resource NOT Updated')->withInput();
                }
            } else {
                return redirect()->back()->withErrors('Resource NOT Found')->withInput();
            }
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        if ($data = ELearningChapter::findOrFail($id)) {

            // code
        } else {
            return redirect()->back()->withErrors('Operation was NOT successful');
        }
    }

    public function massData(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $data = ELearningResource::select(['*', \DB::raw('@rownum  := @rownum  + 1 AS rownum')]);

        $datatables = app('datatables')->of($data);

        $course_id = $datatables->request->get('course');

        $data->whereIn('course_id', function ($query) use ($course_id) {
            $query->select('id')->where('course_id', $course_id)->from('e_learning_courses');
        });

        return $datatables
            ->addColumn('user', function ($data) {
                return $data->user->name;
            })
            ->addColumn('_body', function ($data) {
                return $data->body;
            })
            ->addColumn('_status', function ($data) {
                return $data->status ? 'Visible' : 'Invisible';
            })
            ->addColumn('_course', function ($data) {
                return $data->course->title;
            })
            ->addColumn('attachment', function ($data) {
                // '<audio src="'.asset('uploads/courses/'.$data->audio_url).'" controls></audio>'
                return '';
            })
            ->addColumn('actions', function ($data) {
                $entity = "e-learning.resources";
                $id = $data->id;
                $edit_rights = 'edit_el_resources';
                // $view_rights = 'view_el_resources';
                return view('partials.actions', compact('entity', 'id', 'edit_rights'))->render();
            })
            ->rawColumns(['check', 'actions', '_body', 'attachment'])
            ->make(true);
    }

    public function subscribeResource($courseId)
    {
        $course = ELearningCourse::find($courseId);

        if (!$course->userHasResourceSubscription()) {
            ELearningResourceSubscription::create([
                'course_id' => $course->id,
                'user_id' => auth()->user()->id
            ]);
        }
        return redirect()->back()->with('success', 'Subscription successfully');
    }
}
