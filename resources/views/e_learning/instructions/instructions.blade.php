@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@php ($module = $course->code)

@section('title', $module)

@section('breadcrumb')
    @include('partials.breadcrumb',['link1'=> '', 'active_link'=> '<a href="'.route('e-learning.courses.show', $course->id).'">'.$module.'</a>: '.$course->title])
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
                    @can('list_el_course_instructions')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Instructions</a></li>
                    @endcan
                    {{-- @can('add_el_course_instructions')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/course-instructions/'.$course->id.'/create') }}">Add Instructions</a></li>
                    @endcan --}}
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">
                        
                        <!-- Row -->
                        <div class="row">
                            @if (count($instructions) > 0)
                                @foreach ($instructions as $instruction)
                                    <div class="col-md-12 col-lg-12 col-md-12 col-xl-12">  

                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">
                                                        @if ($instruction->isSetInCourse($course->id))
                                                                <audio src="{{ asset('uploads/courses/'.$instruction->isSetInCourse($course->id)->audio_url) }}" controls></audio>
                                                        @else
                                                            <audio src="{{ asset('uploads/'.$instruction->default_audio_url) }}" controls></audio>
                                                        @endif
                                                    </h3>
                                                    <div class="card-options d-none d-sm-block">
                                                        @if ($instruction->isSetInCourse($course->id))
                                                            <a href="{{ url('e-learning/courses/course-instructions/'.$instruction->isSetInCourse($course->id)->id.'/'.$course->id.'/'.$instruction->id.'/edit') }}" class="btn btn-warning btn-sm">Update</a>
                                                            <a href="{{ url('e-learning/courses/course-instructions/'.$instruction->isSetInCourse($course->id)->id.'/'.$course->id.'/'.$instruction->id.'/discard') }}" class="btn btn-danger btn-sm">Discard</a>
                                                        @else
                                                            <a href="{{ url('e-learning/courses/course-instructions/'.$course->id.'/'.$instruction->id.'/create') }}" class="btn btn-primary btn-sm">Upload</a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <p class="card-text">{{ $instruction->instruction }}</p>
                                                </div>
                                            </div>
                                    </div>
                                @endforeach
                            @endif
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
        .panel-info .list-group-item.danger {
            color: #fff;
            background-color: #d02828 !important;
            border-color: #ffffff;
        }
    </style>
@endsection

@section('scripts')

@endsection


