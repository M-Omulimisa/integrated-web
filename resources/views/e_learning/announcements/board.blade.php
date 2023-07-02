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
                    @can('view_course_announcements')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/board/'.$course->id) }}">Notice Board</a></li>
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Announcement Details</a></li>
                    @endcan
                    @can('list_el_announcements')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/announcements/'.$course->id) }}">Announcements</a></li>
                    @endcan
                    @can('add_el_announcements')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/announcements/'.$course->id.'/create') }}">Post Announcement</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">

                        <div class="row profile ng-scope">
                                <div class="col-md-12">
                                    <div class="activities">
                                        <section class="event card border">
                                            <div class="d-flex">
                                                <span class="thumb-sm  pull-left mr-sm"><img class="avatar avatar-md brround" src="{{ asset('uploads/profile_pics/default.png') }}" alt="..."></span>
                                                <div>
                                                    <h4 class="event-heading"><a href="#">{{ $announcement->user->name }}</a><span><small class="text-muted"><a href="#">&nbsp;{{ $announcement->user->telephone }}</a></small></span></h4>
                                                    <p class="text-xs text-muted ml-2">{{ date('d F Y, h:i A', strtotime($announcement->created_at)) }}</p>
                                                </div>
                                                </div>
                                                <h4>{{ $announcement->title }}</h4>
                                                @if (strlen(strip_tags($announcement->body)) != 0)
                                                    <p class="text-sm mb-0">{!! $announcement->body !!}</p>
                                                @elseif(!is_null($announcement->audio_url))
                                                    <div>
                                                        <audio controls class="col-12 mb-1">
                                                            <source src="{{ asset('uploads/courses/'.$announcement->audio_url) }}">
                                                        </audio>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="clearfix border-top post-comments">
                                                        <ul class="post-links mt-sm pull-left p-2">
                                                            <li><a href="#">{{ count($announcement->views) }} Views</a></li>
                                                        </ul>                                                       
                                                    </div>
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
</div>
<!-- End Row -->
   
@endsection

@section('styles')

@endsection

@section('scripts')

@endsection


