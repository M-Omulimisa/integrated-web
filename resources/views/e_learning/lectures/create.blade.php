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
                    @can('add_el_lectures')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add Lecture</a></li>
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
                        
                        {!! Form::open(['method' => 'POST', 'files' => true, 'route' => ['e-learning.lectures.store']]) !!}

                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="course_id" value="{{ $course->id }}">

                        <div class="form-group mb-3">
                            {!! Form::label('chapter_id', 'Topics*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                           {!! Form::select('chapter_id', $chapters, old('chapter_id'), array('class' => 'form-control select2', 'required' => '')) !!}   
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('title', 'Title*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}          
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('audio', 'Audio Recording', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            <input type='file' id="audio" name="audio" accept=".mp3">           
                            </div>
                            <span class="text-muted ml-2">Allowed types: .mp3 | Max size: 5MBs</span>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('numbering', 'Order of lectures*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('numbering', old('numbering'), ['class' => 'form-control', 'placeholder' => '1', 'required' => '']) !!}          
                            </div>
                        </div>

                        <div class="form-buttons-w">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        </div>

                        {!! Form::close() !!}

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



