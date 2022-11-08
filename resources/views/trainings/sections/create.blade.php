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
    'current'       => 'Add'
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.show',[$resource->id]) }}">Resource</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.edit',[$resource->id]) }}">Edit resource</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add section</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            {!! Form::open(['files' => true, 'method' => 'POST', 'route' => ['trainings.resource-sections.store']]) !!} 

                                <input type="hidden" name="resource_id" value="{{ $resource->id }}">

                                <div class="form-group mb-3">
                                    {!! Form::label('subheading', 'Heading', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                    <h5>{{ $resource->heading }}</h5>               
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('subheading', 'Sub heading (optional)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::text('subheading', old('subheading'), ['class' => 'form-control', 'placeholder' => '']) !!}               
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                {!! Form::label('details', 'Description (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::textarea('details', old('details'), ['class' => 'ckeditor form-control']) !!}
                                    </div> 
                                </div>

                                <div class="form-group mb-3">
                                {!! Form::label('file', 'Image (optional)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        <input class="form-control" id="formSizeDefault" type="file" name="file">
                                    </div> 
                                </div>

                                <div class="form-buttons-w">
                                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                                </div>

                            {!! Form::close() !!}
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

    <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

    <script type="text/javascript">

        $(document).ready(function() {
           $('.ckeditor').ckeditor();
        });

    </script>

@endsection

