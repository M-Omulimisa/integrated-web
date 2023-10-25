@inject('set', 'App\Http\Controllers\Trainings\TrainingResouceController')
@inject('set2', 'App\Http\Controllers\Trainings\TrainingResouceSectionController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Resources',
    'menu_item_url' => route($set->_route.'.index'),
    'current'       => 'Details'
])
<!-- end page title -->

<div class="row">
    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card" id="pages">
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of resources</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">Add new resource</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Resource</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.edit',[$resource->id]) }}">Edit resource</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('trainings/resource-section/create/'.$resource->id) }}">Add section</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card bs-card">
                                        <div class="card-body">
                                            <h4>{{ $resource->heading }}</h4>
                                            <div class="row">
                                                <div class="col-6 col-md-4">
                                                    <img width="300px" src="{{ asset($set->thumbnailUrl($resource->thumbnail)) }}">
                                                </div>
                                                <!--end col-->
                                            </div>
                                            <!--end row-->
                                            
                                        </div>
                                        <!--end card-body-->
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card bs-card">
                                        <div class="card-body">
                                            <div class="tab-content text-muted">
                                                <div class="tab-pane active" id="today" role="tabpanel">
                                                    <div class="profile-timeline">
                                                        <div class="accordion accordion-flush" id="todayExample">

                                                            @if (count($resource->sections) > 0)
                                                                @foreach ($resource->sections as $section)
                                                                    @isset($section->subheading) <h5>{{ $section->subheading }}</h5> @endisset
                                                                    {!! $section->details !!}

                                                                    @if (!is_null($section->image))
                                                                        <br>
                                                                        <img class="mb-2" width="300px" src="{{ asset($set2->imageUrl($section->image)) }}">
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                No sections yet
                                                            @endif

                                                        </div>
                                                        <!--end accordion-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->
                            </div>

                        <!-- content ends here -->
                    </div>
                </div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
    <!--end col-->

</div>

@endsection

@section('styles')

@endsection

@section('scripts')

@endsection

