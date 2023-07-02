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
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/weeks/'.$course->id) }}">Weeks</a></li>
                    @endcan
                    @can('add_el_chapters')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add Weeks</a></li>
                    @endcan
                    @can('list_el_chapters')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/chapters/'.$course->id) }}">Topics</a></li>
                    @endcan
                    @can('list_el_lectures')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/lectures/'.$course->id) }}">Lectures</a></li>
                    @endcan
                    {{-- @can('add_el_lectures')
                        <a href="{{ url('e-learning/courses/lectures/'.$course->id.'/create') }}">Add Lectures</a></li>
                    @endcan --}}
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">
                        
                        {!! Form::open(['method' => 'POST', 'files' => true, 'route' => ['e-learning.weeks.store']]) !!}

                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        <input type="hidden" name="summary" value="{{ $title }}">

                        <div class="form-group mb-3">
                            {!! Form::label('Week', 'Week*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('title', $title, ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}          
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('start_date', 'Start Date', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('start_date', old('start_date'), ['class' => 'form-control datepicker', 'placeholder' => '', 'required' => '', 'data-date-format' => 'yyyy-mm-dd']) !!}          
                            </div>
                        </div>

                        {{-- <div class="form-group mb-3">
                            {!! Form::label('end_date', 'End Date', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-12">
                            {!! Form::text('end_date', old('end_date'), ['class' => 'form-control datepicker', 'placeholder' => '', 'data-date-format' => 'yyyy-mm-dd']) !!}          
                            </div>
                        </div> --}}

                        <input type="hidden" name="numbering" value="{{ $numbering }}">

                        {{-- <div class="form-group mb-3">
                            {!! Form::label('numbering', 'Order of chapters*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('numbering', old('numbering'), ['class' => 'form-control', 'placeholder' => '1', 'required' => '']) !!}          
                            </div>
                        </div> --}}

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


