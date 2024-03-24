@inject('set', 'App\Http\Controllers\Settings\SeasonController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Season',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of seasons</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">Add new season</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.show',[$season->id]) }}">Season details</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Edit season</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                        {!! Form::model($season, ['class'=>'form-horizontal', 'method' => 'PATCH','route' => ['settings.seasons.update', $season->id]]) !!}

                            <div class="form-group mb-3">
                                {!! Form::label('country_id', 'Country (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                               {!! Form::select('country_id', $countries, old('country_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('name', 'Name (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('start_date', 'Start Date (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('start_date', old('start_date'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('cut_off_date', 'Cut off date (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('cut_off_date', old('cut_off_date'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                {!! Form::label('end_date', 'End date (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('end_date', old('end_date'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('status', 'Status (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('status', old('status'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
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

