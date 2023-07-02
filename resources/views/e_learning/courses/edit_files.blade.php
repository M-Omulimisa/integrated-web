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
                    @can('list_el_instructors')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.courses.index') }}">List Courses</a></li>
                    @endcan
                    @can('add_el_courses')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.courses.create') }}">Add Course</a></li>
                    @endcan
                    @can('view_el_courses')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.courses.show', $data->id) }}">View Course</a></li>
                    @endcan
                    @can('edit_el_courses')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Edit Course {{ $file }}</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">

                        {!! Form::model($data, ['method' => 'PUT', 'files' => true, 'url' => ['e-learning/courses/file/'.$data->id.'/'.$file]]) !!}

                        <div class="form-group mb-3">              
                            <div class="col-sm-12">
                                @if (strpos($data->$file, '.jpg') !== false || strpos($data->$file, '.png') !== false || strpos($data->$file, '.jpeg') !== false)
                                    <img src="{{ asset('uploads/courses/'.$data->$file) }}">   
                                @elseif(strpos($data->$file, '.pdf') !== false || strpos($data->$file, '.doc') !== false || strpos($data->$file, '.docx') !== false || strpos($data->$file, '.ppt') !== false || strpos($data->$file, '.pptx') !== false || strpos($data->$file, '.jpeg') !== false)
                                    <a href="{{ asset('uploads/courses/'.$data->$file) }}" target="_blank">Download document</a>       
                                @endif
                            </div>
                            {{-- <span class="text-muted ml-2">Allowed types: .png, .jpg, .jpeg, 200x200px</span> --}}
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label($file, $file, ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            <input type='file' id="logo" name="{{$file}}" value="{{ old($file) }}">          
                            </div>
                            {{-- <span class="text-muted ml-2">Allowed types: .png, .jpg, .jpeg, 200x200px</span> --}}
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
   
@endsection

@section('styles')

@endsection

@section('scripts')
    
    <!-- WYSIWYG Editor js -->
    <script src="{{ asset('assets/plugins/wysiwyag/jquery.richtext.js') }}"></script>
    <script src="{{ asset('assets/plugins/wysiwyag/richText1.js') }}"></script>
    
    

@endsection



