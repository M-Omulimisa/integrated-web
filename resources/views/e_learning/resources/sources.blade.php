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
                    @can('view_course_resources')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Resources</a></li>
                    @endcan
                    @can('list_el_resources')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/resources/'.$course->id) }}">Manage Resources</a></li>
                    @endcan
                    @can('add_el_resources')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/resources/'.$course->id.'/create') }}">Post Resource</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">

                        <div class="row profile ng-scope">
                                <div class="col-md-12">
                                    <h4 class="mt-3">&nbsp;
                                        @can('add_el_resource_subscriptions')
                                            @if ($course->userHasResourceSubscription())
                                                <a href="#" class="btn btn-default btn-sm float-right">Subscribed</a>
                                            @else
                                                <a href="{{ url('e-learning/courses/resources/'.$course->id.'/subscribe') }}" class="btn btn-warning btn-sm float-right">Subscribe</a>
                                            @endif
                                        @endcan
                                    </h4>
                                    @if (count($resources) == 0)
                                        <p>  The instructors have not uploaded any links to resources yet.<br/>Resources are additional readings to learn more about the course. </p>
                                    @else
                                        <div class="activities">
                                            @foreach ($resources as $announcement)
                                            <a href="{{ auth()->user()->can('view_el_resources') ? url('e-learning/courses/resources/'.$announcement->id.'/view') : '#' }}">
                                                <section class="event card border">
                                                        <h4>{{ $announcement->title }}</h4>
                                                            <p class="text-xs text-muted">{{ date('d F Y, h:i A', strtotime($announcement->created_at)) }} | <span>{{ count($announcement->views) }} views</span></p>
                                                    </section>                                                
                                            </a>
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

    <style type="text/css">
        .activities p {
            margin-left: 0px;
            font-size: .65rem !important;
        }
        .activities p span{
            font-weight: bold;
        }
        .activities h4 {
            margin-bottom: 0.2em;
            font-family: 'Montserrat', sans-serif;
            font-weight: 400;
            line-height: 1.1;
            color: inherit;
        }
    </style>
    
@endsection

@section('scripts')

@endsection


