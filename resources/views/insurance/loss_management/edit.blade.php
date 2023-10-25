@inject('set', 'App\Http\Controllers\Insurance\InsuranceLossManagementController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Loss management',
    'menu_item_url' => route($set->_route.'.index'),
    'current'       => 'Edit'
])
<!-- end page title -->

<div class="row">
    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card" id="pages">
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of loss percentage values</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">Add new loss percentage values</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Edit loss percentage values</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                            {!! Form::model($loss, ['class'=>'form-horizontal', 'method' => 'PATCH','route' => ['insurance.loss-management.update', $loss->id]]) !!}

                            <div class="form-group mb-3">
                                {!! Form::label('country_id', 'Country (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                               {!! Form::select('country_id', $countries, old('country_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('season_id', 'Season (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                               {!! Form::select('season_id', $seasons, old('season_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('location_id', 'Locations (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                               {!! Form::select('location_id', $locations, old('location_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('payable_percentage', 'Payable % (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('payable_percentage', old('payable_percentage'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('less_excess', 'Less Excess % (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('less_excess', old('less_excess'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
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

