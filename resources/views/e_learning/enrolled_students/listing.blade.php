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
                    @can('list_el_students')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Students ({{ count($students) }})</a></li>
                    @endcan
                    @can('add_el_students')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/enrolled-students/'.$course->id.'/create') }}">Enroll Student</a></li>
                    @endcan 
                </ul>
                <div class="tab-content">
                                    {{-- @if (count($enrolled-students) == 0)
                                        <p> No Students have been enrolled yet.<br/>Check this section to know who is enrolled for the course.</p>
                                    @else --}}

                    <div class="tab-content p-0 active">
                        <!-- begin #profile-friends tab -->
                        <div class="" id="profile-friends">
                            <div class="row row-space-2">

                                @if (count($students) == 0)
                                    <p> No Students have been enrolled yet.<br/>Check this section to know who is enrolled for the course.</p>
                                @else

                                    @foreach ($students as $enrollment)
                                        <div class="col-xl-6">
                                            <div class="mb-2 border shadow">
                                                <div class="media  media-xs overflow-visible">
                                                    <a class="media-left" href="javascript:;"><img alt="" class="avatar avatar-md brround" src="{{ asset(is_null($enrollment->student->picture) ? 'uploads/profile_pics/default.png' : 'uploads/'.$enrollment->student->picture) }}"></a>
                                                    <div class="media-body valign-middle">
                                                        <b class="text-inverse">{{ $enrollment->student->full_name }}</b> ({{ $enrollment->status ? 'Active' : 'Not Active' }})<br/>
                                                        {{ $enrollment->student->gender }}, {{ $enrollment->student->age_group }}<br/>
                                                        <span class="text-primary" style="font-size:11px">Enrolled On: {{ $enrollment->created_at }}</span><br/>
                                                        <span class="text-muted" style="font-size:11px">Enrolled By: {{ $enrollment->user->name }}</span>
                                                    </div>
                                                    <div class="media-body valign-middle text-right overflow-visible">
                                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                            Actions
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                            <li><a target="_blank" href="{{ route('e-learning.students.show', $enrollment->student->id) }}">View profile</a></li>
                                                            <li><a href="{{ url('e-learning/courses/enrolled-students/'.$course->id.'/'.$enrollment->student_id.'/attendance') }}">View attendance</a></li>
                                                            <li><a href="{{ url('e-learning/courses/enrolled-students/'.$course->id.'/'.$enrollment->student_id.'/delist') }}">Cancel Enrollment</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                @endif
                            </div><!-- end row -->
                        </div><!-- end #profile-friends tab -->
                    </div><!-- end tab-content -->
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


