@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@php ($module = "Courses")

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

<div class="row">
    <div class="col-xl-12">

        <div class="card" id="pages">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    @can('list_el_courses')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">List Courses</a></li>
                    @endcan
                    @can('add_el_courses')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.courses.create') }}">Add Course</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">
                        
                        <!-- Row -->
                        <div class="row">
                            @if (count($data) > 0)
                                @foreach ($data as $course)
                                    <div class="col-md-12 col-lg-4 col-md-4 col-xl-4">

                                        <div class="card">
                                            <div class="card-header">
                                                <a href="{{ route('e-learning.courses.show', $course->id) }}" class="btn btn-sm btn-primary float-end fs-11">Visit Course</a>
                                                <h6 class="card-title mb-0">{{ $course->code }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text"> {{ strlen($course->title) > 90 ? substr($course->title, 0, 90)."..." : $course->title }}</p>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            @else
                                <p>No course yet</p>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="card" id="pages">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    @can('list_el_courses')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Closed courses</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">
                        
                        <!-- Row -->
                        <div class="row">
                            @if (count($closed) > 0)
                                @foreach ($closed as $course)
                                    <div class="col-md-12 col-lg-4 col-md-4 col-xl-4">

                                        <div class="card">
                                            <div class="card-header">
                                                <a href="{{ route('e-learning.courses.show', $course->id) }}" class="btn btn-sm btn-primary float-end fs-11">Visit Course</a>
                                                <h6 class="card-title mb-0">{{ $course->code }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text"> {{ strlen($course->title) > 90 ? substr($course->title, 0, 90)."..." : $course->title }}</p>
                                            </div>
                                        </div>
                                        
                                    </div>
                                @endforeach
                            @else
                                No closed course yet
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
   
@endsection

@section('styles') 

@endsection

@section('scripts')

@endsection


