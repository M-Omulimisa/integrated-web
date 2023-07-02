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
                    @can('list_el_course_instructions')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/content/'.$course->id) }}">Content</a></li>
                    @endcan
                    @can('list_el_course_instructions')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/course-instructions/'.$course->id) }}">Instructions</a></li>
                    @endcan
                    @can('edit_el_course_instructions')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Edit Instruction</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">
                        
                        {!! Form::model($data, ['method' => 'PUT', 'files' => true, 'route' => ['e-learning.course-instructions.update', $data->id]]) !!}

                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        <input type="hidden" name="instruction_id" value="{{ $instruction->id }}">

                        <div class="form-group mb-3">
                            {!! Form::label('instruction_id', 'Instruction', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-12">
                           {!! $instruction->instruction !!}   
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    {!! Form::label('audio', 'Audio', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                    <input type='file' id="audio" name="audio" accept=".mp3">
                                    <br><span class="text-danger bold">Allowed types: .mp3 | Max size: 5MBs</span>               
                                    </div>
                                </div>                        
                            </div>
                            <div class="col-sm-4">
                                @if ($data->audio_url != '#')
                                    <audio src="{{ asset('uploads/courses/'.$data->audio_url) }}" controls></audio>
                                @endif
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