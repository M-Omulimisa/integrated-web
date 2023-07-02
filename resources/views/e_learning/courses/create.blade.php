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
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add Course</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">
                        
                        {!! Form::open(['method' => 'POST', 'files' => true, 'route' => ['e-learning.courses.store']]) !!}

                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                        <div class="form-group mb-3">
                            {!! Form::label('title', 'Title*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}          
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('code', 'Course Code*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('code', old('code'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}          
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('lecture_type', 'Lecture Delivery Method*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                           {!! Form::select('lecture_type', $lecture_types, old('lecture_type'), array('class' => 'form-control select2', 'required' => '')) !!}   
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('summary', 'Summary (Optional)', ['class' => 'col-sm-12 col-form-label']) !!}                
                            <div class="col-sm-12">
                                {!! Form::textarea('summary', old('summary'), ['class' => 'content']) !!}             
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('description', 'Description (Optional)', ['class' => 'col-sm-12 col-form-label']) !!}                
                            <div class="col-sm-12">
                                {!! Form::textarea('description', old('description'), ['class' => 'content2']) !!}             
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('content', 'Content (Optional)', ['class' => 'col-sm-12 col-form-label']) !!}                
                            <div class="col-sm-12">
                                {!! Form::textarea('content', old('content'), ['class' => 'content3']) !!}             
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('audience', 'Audience (Optional)', ['class' => 'col-sm-12 col-form-label']) !!}                
                            <div class="col-sm-12">
                                {!! Form::textarea('audience', old('audience'), ['class' => 'content4']) !!}             
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('outcomes', 'Outcomes (Optional)', ['class' => 'col-sm-12 col-form-label']) !!}                
                            <div class="col-sm-12">
                                {!! Form::textarea('outcomes', old('outcomes'), ['class' => 'content5']) !!}             
                            </div>
                        </div>

                        <div class="row">
                            {!! Form::label('start_date', 'Start Date (Optional)', ['class' => 'col-sm-12 col-form-label']) !!}                
                            <div class="col-sm-3">
                            {!! Form::text('start_date', old('start_date'), ['class' => 'form-control datepicker', 'placeholder' => '', 'data-date-format' => 'yyyy-mm-dd']) !!}          
                            </div>
                            <div class="col-sm-2">
                            {!! Form::text('start_time', old('start_time'), ['class' => 'form-control timepicker', 'placeholder' => '00:00']) !!}          
                            </div>
                        </div>

                        <div class="row">
                            {!! Form::label('end_date', 'End Date (Optional)', ['class' => 'col-sm-12 col-form-label']) !!}                
                            <div class="col-sm-3">
                            {!! Form::text('end_date', old('end_date'), ['class' => 'form-control datepicker', 'placeholder' => '', 'data-date-format' => 'yyyy-mm-dd']) !!}          
                            </div>
                            <div class="col-sm-2">
                            {!! Form::text('end_time', old('end_time'), ['class' => 'form-control timepicker', 'placeholder' => '00:00']) !!}          
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('duration_in_weeks', 'Duration in Weeks (Optional)', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('duration_in_weeks', old('duration_in_weeks'), ['class' => 'form-control', 'placeholder' => '']) !!}          
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('team', 'Team (Optional)', ['class' => 'col-sm-12 col-form-label']) !!}                
                            <div class="col-sm-12">
                                {!! Form::textarea('team', old('team'), ['class' => 'content6']) !!}             
                            </div>
                        </div>

                        {{-- <div class="form-group mb-3">
                            {!! Form::label('operations', 'Operations', ['class' => 'col-sm-12 col-form-label']) !!}                
                            <div class="col-sm-12">
                                {!! Form::textarea('operations', old('operations'), ['class' => 'content7']) !!}             
                            </div>
                        </div> --}}

                        {{-- <div class="form-group mb-3">
                            {!! Form::label('logo', 'Logo', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            <input type='file' id="logo" name="logo" accept=".png, .jpg, .jpeg" value="{{ old('logo') }}">          
                            </div>
                            <span class="text-muted ml-2">Allowed types: .png, .jpg, .jpeg, 200x200px</span>
                        </div> --}}

                        {{-- <div class="form-group mb-3">
                            {!! Form::label('brochure', 'Brochure', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            <input type='file' id="brochure" name="brochure" accept=".pdf, .doc, .docx, .ppt .pptx" value="{{ old('brochure') }}">          
                            </div>
                            <span class="text-muted ml-2">Allowed types: .pdf, .doc, .docx, .ppt .pptx</span>
                        </div> --}}

                        {{-- <div class="form-group mb-3">
                            {!! Form::label('image_banner', 'Image Banner', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            <input type='file' id="image_banner" name="image_banner" accept=".png, .jpg, .jpeg" value="{{ old('image_banner') }}">          
                            </div>
                            <span class="text-muted ml-2">Allowed types: .png, .jpg, .jpeg, 1000x260px</span>
                        </div> --}}

                        {{-- <div class="form-group mb-3">
                            {!! Form::label('video_url', 'Video URL', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('video_url', old('video_url'), ['class' => 'form-control', 'placeholder' => 'youtube link']) !!}          
                            </div>
                        </div> --}}

                        <div class="form-group mb-3">
                            {!! Form::label('about_certificates', 'About Certificates (Optional)', ['class' => 'col-sm-12 col-form-label']) !!}                
                            <div class="col-sm-12">
                                {!! Form::textarea('about_certificates', old('about_certificates'), ['class' => 'content8']) !!}             
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('certificate_url', 'Certificate URL (Optional)', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('certificate_url', old('certificate_url'), ['class' => 'form-control', 'placeholder' => '']) !!}          
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="col-sm-12">
                                <input type="checkbox" name="read_only_mode">
                                <label>Read only</label>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('status', 'Status*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                           {!! Form::select('status', $status, old('status'), array('class' => 'form-control select2', 'required' => '')) !!}   
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('enrollment_status', 'Enrollment Status*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                           {!! Form::select('enrollment_status', $enrollment_status, old('enrollment_status'), array('class' => 'form-control select2', 'required' => '')) !!}   
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
   
@endsection

@section('styles')

@endsection

@section('scripts')
    
    <!-- WYSIWYG Editor js -->
    <script src="{{ asset('assets/plugins/wysiwyag/jquery.richtext.js') }}"></script>
    <script src="{{ asset('assets/plugins/wysiwyag/richText1.js') }}"></script>
    
    

@endsection



