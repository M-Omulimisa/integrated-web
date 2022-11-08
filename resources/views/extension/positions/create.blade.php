@inject('set', 'App\Http\Controllers\Extension\ExtensionOfficerPositionController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Extension Officer Positions',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of positions</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new position</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                            {!! Form::open(['method' => 'POST', 'route' => ['extension-officers.positions.store']]) !!}

                                    <div class="form-group mb-3">
                                        {!! Form::label('organisation_id', 'Organisation (optional)', ['class' => 'col-sm-3 form-label']) !!}             
                                        <div class="col-sm-5">
                                       {!! Form::select('organisation_id', $organisations ?? [], old('organisation_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        {!! Form::label('name', 'Name (required)', ['class' => 'col-sm-3 form-label']) !!}
                                        <div class="col-sm-5">
                                            {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'id' => 'name']) !!}
                                        </div> 
                                    </div>

                                    <div class="form-group mb-3">
                                        {!! Form::label('admin_level', 'Administration Level (optional)', ['class' => 'col-sm-3 form-label']) !!}             
                                        <div class="col-sm-5">
                                       {!! Form::select('admin_level', $admin_levels, old('admin_level'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}  
                                       <span class="help-block">Leave empty for full access</span>  
                                        </div>
                                    </div> 

                                    <div class="form-group mb-3">
                                        {!! Form::label('locations', 'Locations (optional)', ['class' => 'col-sm-4 col-form-label']) !!}
                                        <div class="col-sm-5">
                                            {!! Form::select('locations[]', $locations, old('locations'), ['class' => 'form-control js-example-basic-multiple select2-hidden-accessible', 'multiple'=>'']) !!} 
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

