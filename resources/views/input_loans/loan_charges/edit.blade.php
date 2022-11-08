@inject('set', 'App\Http\Controllers\InputLoan\LoanChargeController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Wholesale prices',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of farmers</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">Add new farmer</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.show',[$id]) }}">Farmer details</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Edit farmer</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            {!! Form::open(['method' => 'POST', 'route' => [$set->_route.'.store']]) !!}

                                <div class="form-group mb-3">
                                        {!! Form::label('microfinance_id', 'Microfinance (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                        <div class="col-sm-5">
                                       {!! Form::select('microfinance_id', $microfinances, old('microfinance_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                        </div>
                                    </div> 

                                <div class="form-group mb-3">
                                    {!! Form::label('name', 'Charge Name (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                        {!! Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name of charge', 'required' => ''], old('name')) !!}
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                        {!! Form::label('application', 'Application (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                        <div class="col-sm-5">
                                       {!! Form::select('application', $computation_types, old('application'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                        </div>
                                    </div> 
                                <div class="form-group mb-3">
                                    {!! Form::label('charge', 'Charge (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                        {!! Form::number('charge', '0', ['step' => '0.01', 'class' => 'form-control', 'placeholder' => ''], old('charge')) !!}
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

