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
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/enrolled-students/'.$course->id) }}">Students</a></li>
                    @endcan
                    @can('add_el_students')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/enrolled-students/'.$course->id.'/create') }}">Enroll Student</a></li>
                    @endcan              
                    @can('view_el_students')
                        <li class="nav-item"><a class="nav-link" href="{{ url()->current() }}">Student's Attendance</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">

                        <h4 class="mt-2">...</h4>
                        
                        

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