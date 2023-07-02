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
                    @can('view_course_forum')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">General</a></li>
                    @endcan
                    {{-- @can('list_el_lecture_topics')
                        <li class="nav-item"><a class="nav-link" href="#">Lecture Discussions</a></li>
                    @endcan
                    @if (auth()->user()->can('list_el_lecture_topic_subscriptions') || auth()->user()->can('list_el_forum_topic_subscriptions'))
                        <li class="nav-item"><a class="nav-link" href="#">Subscribed</a></li>
                    @endif --}}
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">

                        <div class="row profile ng-scope">
                                <div class="col-md-12">
                                    <h4 class="mt-3">Discussions
                                        @can('add_el_forum_topics')
                                            <a href="{{ url('e-learning/courses/forums/'.$course->id.'/topics/new') }}" class="btn btn-primary btn-sm float-right">New Topic</a>
                                        @endcan
                                    </h4>
                                    @if (count($topics) == 0)
                                        <p>This course doesn't have any general ongoing discussions yet.
                                        @can('add_el_forum_topics')
                                            &nbsp;You can start a new discussion topic in case you have any doubts or questions about the course content.
                                         @endcan</p>
                                    @else
                                        <div class="activities">
                                            @foreach ($topics as $discussion)
                                                <section class="event card border">
                                                    <div class="d-flex">
                                                        <span class="thumb-sm  pull-left mr-sm"><img class="avatar avatar-md brround" src="{{ asset('uploads/profile_pics/default.png') }}" alt="..."></span>
                                                        <div>
                                                            <h4 class="event-heading"><a href="#">{{ isset($discussion->user) ? $discussion->user->name : $discussion->student->full_name }}</a><span><small class="text-muted"><a href="#">&nbsp;{{ isset($discussion->user) ? $discussion->user->telephone : $discussion->student->phone_number }}</a></small></span></h4>
                                                            <p class="text-xs text-muted ml-2">{{ date('d F Y, h:i A', strtotime($discussion->created_at)) }}</p>
                                                        </div>
                                                        </div>
                                                        <h4>{{ $discussion->subject }}</h4>
                                                        @if (strlen(strip_tags($discussion->description)) != 0)
                                                            <p class="text-sm mb-0">{!! $discussion->description !!}</p>
                                                        @endif
                                                        @if(!is_null($discussion->audio_url))
                                                            <div>
                                                                <audio controls class="col-12 mb-1">
                                                                    <source src="{{ asset('uploads/courses/'.$discussion->audio_url) }}">
                                                                </audio>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="clearfix border-top post-comments">
                                                                <ul class="post-links mt-sm pull-left p-2">
                                                                    <li><a href="#">{{ count($discussion->responses) }} Responses</a></li>
                                                                    <li><a href="#">{{ count($discussion->subscriptions) }} Subscriptions</a></li>
                                                                    <li><a href="#">{{ count($discussion->likes) }} Likes</a></li>
                                                                </ul>
                                                                @can('view_el_forum_topics')
                                                                    <a href="{{ url('e-learning/courses/forums/'.$discussion->id.'/topics/show') }}" class="btn btn-success btn-sm float-right mr-2 mt-2">View Discussion</a>
                                                                @endcan                                                        
                                                            </div>
                                                        </div>
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