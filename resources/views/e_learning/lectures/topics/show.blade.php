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
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/chapters/'.$course->id) }}">Topics</a></li>
                    @endcan
                    @can('list_el_lectures')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/lectures/'.$course->id) }}">Lectures</a></li>
                    @endcan
                    @can('view_el_lectures')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.lectures.show', $data->lecture->id) }}">Lecture Detail</a></li>
                    @endcan
                    @can('view_el_lecture_topics')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Discussion Topic</a></li>
                    @endcan
                    @can('list_el_lectures')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/assignments/'.$course->id) }}">Chapter Quiz</a></li>
                    @endcan
                    @can('list_el_lectures')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/general-assignments/'.$course->id) }}">General Quiz</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">

                       <div class="row profile ng-scope">
                            <div class="col-md-12">
                                <h4 class="mt-3">Discussions
                                    @can('view_el_lectures')
                                        <a href="{{ route('e-learning.lectures.show', $data->lecture->id) }}" class="btn btn-primary btn-sm float-right">Back To Lecture</a>
                                    @endcan
                                </h4>
                                <section class="event card border">
                                    <div class="d-flex">
                                        <span class="thumb-sm  pull-left mr-sm"><img class="avatar avatar-md brround" src="{{ asset('uploads/profile_pics/default.png') }}" alt="..."></span>
                                        <div>
                                            <h4 class="event-heading"><a href="#">{{ $data->user->name ?? $data->student->full_name }}</a><span><small class="text-muted"><a href="#">&nbsp;{{ $data->user->telephone ?? $data->student->phone_number }}</a></small></span></h4>
                                            <p class="text-xs text-muted ml-2">{{ date('d F Y, h:i A', strtotime($data->created_at)) }}</p>
                                        </div>
                                    </div>
                                        <h4>{{ $data->subject }}</h4>
                                        @if (strlen(strip_tags($data->description)) != 0)
                                            <p class="text-sm mb-0">{!! $data->description !!}</p>
                                        @endif
                                        @if(!is_null($data->audio_url))
                                            <div>
                                                <audio controls class="col-12 mb-1">
                                                    <source src="{{ isset($data->user) ? asset('uploads/courses/'.$data->audio_url) : $data->audio_url }}">
                                                </audio>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="clearfix border-top post-comments">
                                                <ul class="post-links mt-sm pull-left p-2">
                                                    <li><a href="#">{{ count($data->responses) }} Responses</a></li>
                                                    <li><a href="#">{{ count($data->subscriptions) }} Subscriptions</a></li>
                                                    <li><a href="#">{{ count($data->likes) }} Likes</a></li>
                                                </ul>
                                                @can('add_el_lecture_topic_subscriptions')
                                                    @if ($data->hasSuscribed())
                                                        <a href="#" class="btn btn-default btn-sm float-right mr-2 mt-2">Subscribed</a>
                                                    @else
                                                        <a href="{{ url('e-learning/courses/lectures/topics/'.$data->id.'/subscribe') }}" class="btn btn-warning btn-sm float-right mr-2 mt-2">Subscribe</a>
                                                    @endif
                                                @endcan
                                                @can('add_el_lecture_topic_likes')
                                                    @if ($data->hasLiked())
                                                        <a href="#" class="btn btn-default btn-sm float-right mr-2 mt-2">You Liked</a>
                                                    @else
                                                        <a href="{{ url('e-learning/courses/lectures/topics/'.$data->id.'/like') }}" class="btn btn-success btn-sm float-right mr-2 mt-2">Like</a>
                                                    @endif
                                                @endcan
                                            </div>
                                        </div>
                                    </section>

                                    <section class="event card border">

                                        <h4 class="mt-3">Responses
                                            @can('add_el_lecture_topic_responses')
                                                <a href="{{ url('e-learning/courses/lectures/topics/'.$data->id.'/respond') }}" class="btn btn-primary btn-sm float-right">Respond</a>
                                            @endcan
                                        </h4>
                                        <div>
                                            @if (count($data->responses) > 0)
                                                <ul class="post-comments mt-sm mb-0">
                                                    @foreach ($data->responses as $response)
                                                        <li class="d-flex">
                                                            <span class="thumb-sm  float-left mr-sm"><img class="avatar avatar-md brround" src="{{ asset('uploads/profile_pics/default.png') }}" alt="..."></span>
                                                            <div class="comment-body">
                                                                <h4 class="author fw-semi-bold">{{ $response->user->name ?? $response->student->full_name }} <br/><small class="text-muted">{{ date('d F Y, h:i A', strtotime($response->created_at)) }}</small></h4>
                                                                @if (strlen(strip_tags($response->comment)) != 0)
                                                                    <p class="text-xs">{!! $response->comment !!}</p>
                                                                @endif
                                                                @if(!is_null($response->audio_url))
                                                                    <p>&nbsp;</p>
                                                                    <div>
                                                                        <audio controls class="mb-1">
                                                                            <source src="{{ isset($response->user) ? asset('uploads/courses/'.$response->audio_url) : $response->audio_url }}">
                                                                        </audio>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            
                                                        </li>
                                                        <div>
                                                                <div class="pull-right" style="margin-top:-45px">
                                                                    <ul class="post-links mt-sm pull-left p-2">
                                                                        <li><a href="#">{{ count($response->likes) }} Likes</a></li>
                                                                    </ul>
                                                                    @can('add_el_lecture_topic_likes')
                                                                        @if ($response->hasLiked())
                                                                            <a href="#" class="btn btn-default btn-sm float-right mr-2 mt-2">You Liked</a>
                                                                        @else
                                                                            <a href="{{ url('e-learning/courses/lectures/topics/responses/'.$response->id.'/like') }}" class="btn btn-success btn-sm float-right mr-2 mt-2">Like</a>
                                                                        @endif
                                                                    @endcan
                                                                </div>
                                                            </div>
                                                        
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    </section>
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