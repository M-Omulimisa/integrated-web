@inject('set', 'App\Http\Controllers\Trainings\ResourceTopicController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Topics',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of topics</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new topic</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                            {!! Form::open(['files'=>true, 'method' => 'POST', 'route' => ['trainings.topics.store']]) !!}

                                <div class="form-group mb-3">
                                    {!! Form::label('country_id', 'Country (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('country_id', $countries, old('country_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('organisation_id', 'Organisation (optional)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('organisation_id', $organisations, old('organisation_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('topic', 'Topic (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::text('topic', old('topic'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'id' => 'topic']) !!}
                                    </div> 
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('details', 'Details (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::textarea('details', old('details'), ['class' => 'form-control', 'rows' => 3, 'required' => '']) !!}
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

@endsection

