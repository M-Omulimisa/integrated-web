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
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.courses.index') }}">List Courses</a></li>
                    @endcan
                    @can('add_el_courses')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.courses.create') }}">Add Course</a></li>
                    @endcan
                    @can('view_el_courses')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">View Course</a></li>
                    @endcan
                    @can('edit_el_courses')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.courses.edit', $data->id) }}">Edit Course</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">

                        <!-- Row -->
                        <div class="row">
                            <div class="col-md-12">

                                {{-- <div class="card overflow-hidden">
                                    <div class="card-body">
                                        <h5 class="card-title mb-2"><a href="{{ route('e-learning.courses.show', $data->id) }}">{{ $data->code }}: <span class="text-primary">{{ $data->title }}</span></a></h5>
                                    </div>
                                    <img src="{{ !is_null($data->image_banner) ? asset('uploads/courses/'.$data->image_banner) : asset('assets/images/courses/banner.png') }}" alt="image">
                                        <div class="row mt-1 mb-1 pl-2">
                                            <div class="col-md-8">
                                                @if ($data->status == "Open" && $data->enrollment_status == "Current")
                                                    @can('register_el_courses')
                                                        @if (!$data->userRegisteredForThisCourse(auth()->user()->id))
                                                            <a class="btn btn-success btn-sm" href="{{ url('e-learning/courses/register/'.$data->id) }}">Register</a>
                                                        @endif
                                                    @endcan
                                                @endif
                                                    @can('deregister_el_courses')
                                                        @if ($data->userRegisteredForThisCourse(auth()->user()->id))
                                                            @if ($data->userRegisteredForThisCourse(auth()->user()->id)->status)
                                                                <a class="btn btn-danger btn-sm" href="{{ url('e-learning/courses/deregister/'.$data->id) }}">Deregister</a>
                                                            @endif
                                                        @endif
                                                    @endcan
                                                @if ($data->status == "Open" && $data->userRegisteredForThisCourse(auth()->user()->id))
                                                    @if ($data->userRegisteredForThisCourse(auth()->user()->id)->status)
                                                        <a class="btn btn-primary btn-sm" href="{{ url('e-learning/courses/content/'.$data->id) }}">Access course</a>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="col-md-2">
                                                @can('edit_el_courses')
                                                   <a href="{{ url('e-learning/courses/file/'.$data->id.'/image_banner') }}">Update image banner</a> 
                                                @endcan
                                            </div>
                                            <div class="col-md-2">
                                                @can('edit_el_courses')
                                                    <a class="text-danger" href="{{ url('e-learning/courses/file/remove/'.$data->id.'/image_banner') }}">Remove image banner</a>
                                                @endcan
                                            </div>
                                        </div>                                    
                                </div> --}}

                                <div class="card">
                                    <div class="card-header">
                                            @if ($data->status == "Open" && auth()->user()->id == $data->user_id || $data->status == "Open" && $data->userRegisteredForThisCourse(auth()->user()->id))
                                                @if (auth()->user()->id == $data->user_id || $data->userRegisteredForThisCourse(auth()->user()->id)->status)
                                                    <a class="btn btn-primary btn-sm float-end" href="{{ url('e-learning/courses/content/'.$data->id) }}">Access course</a>
                                                @endif
                                            @endif
                                            @can('deregister_el_courses')
                                                @if ($data->userRegisteredForThisCourse(auth()->user()->id))
                                                    @if ($data->userRegisteredForThisCourse(auth()->user()->id)->status)
                                                        <a class="btn btn-danger btn-sm float-end" href="{{ url('e-learning/courses/deregister/'.$data->id) }}">Deregister</a>
                                                    @endif
                                                @endif
                                            @endcan
                                            @if ($data->status == "Open" && $data->enrollment_status == "Current")
                                                    @can('register_el_courses')
                                                        @if (!$data->userRegisteredForThisCourse(auth()->user()->id))
                                                            <a class="btn btn-success btn-sm float-end mr-1" href="{{ url('e-learning/courses/register/'.$data->id) }}">Register</a>
                                                        @endif
                                                    @endcan
                                            @endif
                                        <h5 class="card-title mb-0">{{ $data->code }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text"> {{ $data->title }}</p>
                                    </div>
                                </div>

                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade active show" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                                        <p>
                                            @isset ($data->start_date)
                                                {!! '<strong>Start Date:</strong> '.$data->start_date.'<br>' !!}
                                            @endisset
                                            @isset ($data->duration_in_weeks)
                                                {!! '<strong>Duration:</strong> '.$data->duration_in_weeks.' weeks<br>' !!}
                                            @endisset
                                            @isset ($data->status)
                                                {!! '<strong>Status:</strong> '.$data->status.'<br>' !!}
                                            @endisset
                                            @isset ($data->read_only_mode)
                                                @php($read_only = $data->read_only_mode ? 'Yes' : 'No')
                                                {!! '<strong>Read Only:</strong> '.$read_only.'<br>' !!}
                                            @endisset
                                            @isset ($data->enrollment_status)
                                                {!! '<strong>Enrollment Status:</strong> '.$data->enrollment_status !!}
                                            @endisset
                                        </p>                                        

                                        @if (strlen(strip_tags($data->summary)) > 0 && isset($data->summary))                                                    
                                            <h4><strong>Summary</strong></h4>
                                            {!! $data->summary !!}
                                        @endif

                                        @if (strlen(strip_tags($data->description)) > 0 && isset($data->description))                                                   
                                            <div class="media-heading mt-3">
                                                <h4><strong>Description</strong></h4>
                                            </div>
                                            {!! $data->description !!}
                                        @endif

                                        @if (strlen(strip_tags($data->content)) > 0 && isset($data->content))                                                   
                                            <div class="media-heading mt-3">
                                                <h4><strong>Content</strong></h4>
                                            </div>
                                            {!! $data->content !!}
                                        @endif

                                       @if (strlen(strip_tags($data->audience)) > 0 && isset($data->audience))                                                    
                                            <div class="media-heading mt-3">
                                                <h4><strong>Audience</strong></h4>
                                            </div>
                                            {!! $data->audience !!}
                                        @endif

                                        @if (strlen(strip_tags($data->outcomes)) > 0 && isset($data->outcomes))                                                   
                                            <div class="media-heading mt-3">
                                                <h4><strong>Outcomes</strong></h4>
                                            </div>
                                            {!! $data->outcomes !!}
                                        @endif

                                        @if (strlen(strip_tags($data->team)) > 0 && isset($data->team))                                                    
                                            <div class="media-heading mt-3">
                                                <h4><strong>Team</strong></h4>
                                            </div>
                                            {!! $data->team !!}
                                        @endif

                                        @if (strlen(strip_tags($data->operations)) > 0 && isset($data->operations))                                                   
                                            <div class="media-heading mt-3">
                                                <h4><strong>Operations</strong></h4>
                                            </div>
                                            {!! $data->operations !!}
                                        @endif

                                        {{-- @isset ($data->logo)                                                    
                                            <div class="media-heading mt-3">
                                                <h4><strong>Logo</strong></h4>
                                            </div>
                                            <img src="{{ asset('uploads/courses/'.$data->logo) }}"><br/> 
                                        @endisset
                                        @can('edit_el_courses')
                                            <a href="{{ url('e-learning/courses/file/'.$data->id.'/logo') }}">Update logo</a>&nbsp;|&nbsp;
                                                <a class="text-danger" href="{{ url('e-learning/courses/file/remove/'.$data->id.'/logo') }}">Remove</a>
                                        @endcan --}}

                                        {{-- @isset ($data->brochure)                                                    
                                            <div class="media-heading mt-3">
                                                <h4><strong>Brochure</strong></h4>
                                            </div>
                                            <a href="{{ asset('uploads/courses/'.$data->brochure) }}" target="_blank">Download</a>&nbsp;|&nbsp;
                                            @can('edit_el_courses')
                                                <a class="text-danger" href="{{ url('e-learning/courses/file/remove/'.$data->id.'/brochure') }}">Remove</a> &nbsp;|&nbsp;
                                            @endcan
                                        @endisset
                                        @can('edit_el_courses')
                                            <a href="{{ url('e-learning/courses/file/'.$data->id.'/brochure') }}">Update</a>
                                        @endcan --}}

                                        <div class="media-heading mt-3">
                                            <h4><strong>Lecture Delivery Method</strong></h4>
                                        </div>
                                        {!! $data->lecture_type !!}

                                        {{-- @isset ($data->video_url)                                                    
                                            <div class="media-heading mt-3">
                                                <h4><strong>Video</strong></h4>
                                            </div>
                                             <iframe width="420" height="315" src="{{ $data->video_url }}"></iframe> 
                                        @endisset --}}

                                        @if (strlen(strip_tags($data->about_certificates)) > 0 && isset($data->about_certificates))                                                  
                                            <div class="media-heading mt-3">
                                                <h4><strong>About certificates</strong></h4>
                                            </div>
                                            {!! $data->about_certificates !!}
                                        @endif

                                        @isset ($data->certificate_url)
                                            <br/>
                                            {!! $data->certificate_url !!}                                                    
                                        @endisset
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- End Row -->

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

@endsection

