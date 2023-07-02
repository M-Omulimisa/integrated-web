@inject('set', 'App\Http\Controllers\Organisations\OrganisationPositionController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Positions',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of positions</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">Add new position</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.show',[$position->id]) }}">Position details</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Edit position</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                        {!! Form::model($position, ['class'=>'form-horizontal', 'method' => 'PATCH','route' => ['organisations.positions.update', $position->id]]) !!}

                                <div class="form-group mb-3">
                                    {!! Form::label('services', 'Organisation (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::select('organisation_id', $organisations, old('organisation_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!} 
                                    </div> 
                                </div>  

                                <div class="form-group mb-3">
                                    {!! Form::label('name', 'Name (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                    </div>
                                </div>

                                {{-- <div class="form-group mb-3">
                                    {!! Form::label('permissions', 'Permissions (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::select('permissions[]', $permissions, old('permissions'), ['class' => 'form-control js-example-basic-multiple select2-hidden-accessible', 'multiple'=>'']) !!} 
                                    </div> 
                                </div> --}}

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

