@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@php ($module = $course->code)

@section('title', $module)

@section('breadcrumb')
    @include('partials.breadcrumb',['link1'=> '', 'active_link'=> '<a href="'.route('e-learning.courses.show', $course->id).'">'.$module.'</a>: '.$course->title])
@endsection

@section('extra-buttons')
    <div class="ml-auto">
        <div class="input-group">

        @if ($unanswered_questions > 0)
            <a href="#" class="btn btn-danger btn-icon mr-2" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Questions"><span> <i class="fe fe-message-square"></i> </span> {{ $unanswered_questions }} New Questions  </a>
        @endif

        </div>
      </div>
@endsection

@section('content')

<!-- Row -->
<div class="row">
    <div class="col-12 col-sm-12 col-md-3">
        <div class="card">
            <div class="card-body">
                @include('e_learning.courses.menu')
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-9">
        <div class="card" id="pages">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">                    
                    @can('view_course_contents')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Course Content</a></li>
                    @endcan
                    @can('list_el_chapters')
                        @if ($course->lecture_type == 'Weekly')
                            <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/weeks/'.$course->id) }}">Weeks</a></li>
                        @endif
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/chapters/'.$course->id) }}">Topics</a></li>
                    @endcan
                    {{-- @can('add_el_chapters')
                        <a href="{{ url('e-learning/courses/chapters/'.$course->id.'/create') }}">Add Topics</a>
                    @endcan --}}
                    @can('list_el_lectures')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/lectures/'.$course->id) }}">Lectures</a></li>
                    @endcan
                    @can('list_el_lectures')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/assignments/'.$course->id) }}">Chapter Quiz</a></li>
                    @endcan
                    {{-- @can('add_el_lectures')
                        <a href="{{ url('e-learning/courses/lectures/'.$course->id.'/create') }}">Add Lectures</a>
                    @endcan --}}
                    @can('list_el_lectures')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/general-assignments/'.$course->id) }}">General Quiz</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">
                        
                        @if (count($chapters) > 0)
                            <!-- Accordions Bordered -->                            
                            @foreach ($chapters as $chapter)
                                <div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-secondary" id="accordionBordered">
                                    <div class="accordion-item mt-2">
                                        <h2 class="accordion-header" id="chapterX{{ $chapter->id }}">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#chapter{{ $chapter->id }}" aria-expanded="false" aria-controls="chapter{{ $chapter->id }}">
                                                {{ $chapter->title }}
                                                @if ($chapter->unansweredQuestions() > 0)
                                                   <span class="float-right"> <i class="fe fe-message-square"></i>  {{ $chapter->unansweredQuestions() }}</span>
                                               @endif 
                                            </button>
                                        </h2>
                                        <div id="chapter{{ $chapter->id }}" class="accordion-collapse collapse" aria-labelledby="chapterX{{ $chapter->id }}" data-bs-parent="#accordionBordered">
                                            <div class="accordion-body">
                                                <div class="panel panel-info">
                                                    <div class="list-group">
                                                        @if ($course->lecture_type == "Topical")
                                                            <p>{!! $chapter->summary !!}</p>
                                                            @if (count($chapter->lectures) > 0)
                                                                @foreach ($chapter->lectures as $lecture)
                                                                    <a href="{{ $lecture->status ? route('e-learning.lectures.show', $lecture->id) : '#' }}" class="list-group-item {{ $lecture->hasBeenWatched() ? '' : 'active'}} {{ $lecture->status ? '' : 'disabled' }}">
                                                                        <span class="mr-3"><i class="fa fa-play fa-lg"></i></span>
                                                                        {{ $lecture->title }}
                                                                        <span class="float-right ml-5">{{ $lecture->lecture_length() }}</span>
                                                                        @if ($lecture->unansweredQuestions() > 0)
                                                                            <span class="float-right"> <i class="fe fe-message-square"></i>  {{ $lecture->unansweredQuestions() }}</span>
                                                                        @endif
                                                                    </a>
                                                                @endforeach
                                                            @else
                                                                <a href="#" class="list-group-item">No lectures</a>
                                                            @endif

                                                            <p class="mt-5"><b>Quiz</b></p>
                                                            @if (count($chapter->assignments) > 0)
                                                                @foreach ($chapter->assignments as $assignment)
                                                                    <a href="{{ $assignment->status ? route('e-learning.assignments.show', $assignment->id) : '#' }}" class="list-group-item {{ $assignment->hasBeenWatched() ? '' : 'danger'}} {{ $assignment->status ? '' : 'disabled' }}">
                                                                        <span class="mr-3"><i class="fa fa-play fa-lg"></i></span>{{ $assignment->title }}<span class="float-right">{{ $assignment->lecture_length() }}</span></a>
                                                                @endforeach
                                                            @else
                                                                <a href="#" class="list-group-item">No questions</a>
                                                            @endif
                                                        @else
                                                            @if (count($chapter->topics) > 0)
                                                                @foreach ($chapter->topics as $topic)
                                                                    <p class="mt-3 mb-1">{!! $topic->title !!}</p>
                                                                    @if (count($topic->lectures) > 0)
                                                                        @foreach ($topic->lectures as $lecture)
                                                                            <a href="{{ $lecture->status ? route('e-learning.lectures.show', $lecture->id) : '#' }}" class="list-group-item {{ $lecture->hasBeenWatched() ? '' : 'active'}} {{ $lecture->status ? '' : 'disabled' }}">
                                                                                <span class="mr-3"><i class="fa fa-play fa-lg"></i></span>{{ $lecture->title }}<span class="float-right">{{ $lecture->lecture_length() }}</span></a>
                                                                        @endforeach
                                                                    @else
                                                                        <a href="#" class="list-group-item">No lectures</a>
                                                                    @endif
                                                                    <p class="mt-5"><b>Quiz</b></p>
                                                                    @if (count($topic->assignments) > 0)
                                                                        @foreach ($topic->assignments as $assignment)
                                                                            <a href="{{ $assignment->status ? route('e-learning.assignments.show', $assignment->id) : '#' }}" class="list-group-item {{ $assignment->hasBeenWatched() ? '' : 'danger'}} {{ $assignment->status ? '' : 'disabled' }}">
                                                                                <span class="mr-3"><i class="fa fa-play fa-lg"></i></span>{{ $assignment->title }}<span class="float-right">{{ $assignment->lecture_length() }}</span></a>
                                                                        @endforeach
                                                                    @else
                                                                        <a href="#" class="list-group-item">No Quiz</a>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                <a href="#" class="list-group-item">No Topics</a>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            No topics
                        @endif

                        @if (count($general_questions) > 0)                            

                            <!-- Accordions Bordered -->
                            <div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-secondary mt-1">
                                <div class="accordion-item mt-2">
                                    <h2 class="accordion-header" id="accordionborderedExample2">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedExamplecollapse2" aria-expanded="false" aria-controls="accor_borderedExamplecollapse2">
                                            General Quiz
                                        </button>
                                    </h2>
                                    <div id="accor_borderedExamplecollapse2" class="accordion-collapse collapse" aria-labelledby="accordionborderedExample2" data-bs-parent="#accordionBordered">
                                        <div class="accordion-body">
                                            <div class="panel panel-info">
                                                <div class="list-group">
                                                    @foreach ($general_questions as $question)
                                                        <a href="{{ $question->status ? route('e-learning.general-assignments.show', $question->id) : '#' }}" class="list-group-item {{ $question->hasBeenWatched() ? '' : 'active'}} {{ $question->status ? '' : 'disabled' }}"><span class="mr-3"><i class="fa fa-play fa-lg"></i></span>{{ $question->title }}<span class="float-right">{{ $question->lecture_length() }}</span></a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="mt-5">No General Quiz</p>
                        @endif                       

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Row -->
   
@endsection

@section('styles')
    

    <style type="text/css">
        .panel-info .list-group-item.danger {
            color: #fff;
            background-color: #d02828 !important;
            border-color: #ffffff;
        }
    </style>
@endsection


