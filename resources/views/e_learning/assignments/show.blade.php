@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@php ($module = $course->code.': '.$course->title)

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => '...',
    'menu_group'    => '...',
    'menu_item'     => 'E-Learning',
    'menu_item_url' => '#',
    'current'       => '...'
])
<!-- end page title -->

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
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/content/'.$course->id) }}">Content</a></li>
                    @endcan
                    @can('list_el_chapters')
                        @if ($course->lecture_type == "Weekly")
                            <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/weeks/'.$course->id) }}">Weeks</a></li>
                        @endif
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/chapters/'.$course->id) }}">Topics</a></li>
                    @endcan
                    @can('list_el_lectures')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/lectures/'.$course->id) }}">Lectures</a></li>
                    @endcan
                    @can('list_el_lectures')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/assignments/'.$course->id) }}">Chapter Quiz</a></li>
                    @endcan
                    @can('view_el_lectures')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Chapter Question</a></li>
                    @endcan
                    @can('list_el_lectures')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/general-assignments/'.$course->id) }}">General Quiz</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">

                        <h4 class="mt-2">{{ $data->chapter->title }} > {{ $data->title }}</h4>
                        
                        <div class="row">
                            <div class="col-12">
                                <audio autoplay loop controls id="playAudio" class="col-12">
                                    <source src="{{ asset('uploads/courses/'.$data->audio_url) }}">
                                </audio>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                
                            </div>
                        </div>

                        <div class="row profile ng-scope">
                                <div class="col-md-12">
                                    <h4 class="mt-3">Answers</h4>
                                    @if (count($data->answers) == 0)
                                        <p>This question doesn't have any answers yet.</p>
                                    @else
                                        <div class="activities">
                                            @foreach ($data->answers as $answer)
                                                <section class="event card border">
                                                    <div class="d-flex">
                                                        <span class="thumb-sm  pull-left mr-sm"><img class="avatar avatar-md brround" src="{{ asset('uploads/profile_pics/default.png') }}" alt="..."></span>
                                                        <div>
                                                            <h4 class="event-heading"><a href="#">{{ $answer->student->full_name }}</a><span><small class="text-muted"><a href="#">&nbsp;{{ $answer->student->phone_number }}</a></small></span></h4>
                                                            <p class="text-xs text-muted ml-2">{{ date('d F Y, h:i A', strtotime($answer->created_at)) }}</p>
                                                        </div>
                                                        </div>
                                                        <h4>Answer: {{ $answer->answer }}</h4>
                                                        <p class="text-sm mb-0">{{ $data->answer == $answer->answer ? 'PASSED' : 'FAILED' }}</p>
                                                    </section>
                                            @endforeach
                                            

                                        </div>
                                    @endif
                                </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Row -->
   
@endsection

@section('styles')
@endsection

@section('scripts')
    
    <!-- WYSIWYG Editor js -->
    <script src="{{ asset('assets/plugins/wysiwyag/jquery.richtext.js') }}"></script>
    <script src="{{ asset('assets/plugins/wysiwyag/richText1.js') }}"></script>
@endsection