@inject('set', 'App\Http\Controllers\InputLoan\InputPriceController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Input prices',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of input prices</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new input prices</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            {!! Form::open(['method' => 'POST', 'route' => [$set->_route.'.store']]) !!}               

                                    <div class="form-group mb-3">
                                        {!! Form::label('project_id', 'Project (optional)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                        <div class="col-sm-5">
                                        {!! Form::select('project_id', $projects, old('project_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                        </div>
                                    </div> 

                                    <div class="form-group mb-3">
                                        {!! Form::label('season_id', 'Season (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                        <div class="col-sm-5">
                                        {!! Form::select('season_id', $seasons, old('project_id'), ['class' => 'form-control select2','placeholder'=>'--Select--','required' => '']) !!}    
                                        </div>
                                    </div> 

                                    <div class="form-group mb-3">
                                        {!! Form::label('distibutor_id', 'Distributor (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                        <div class="col-sm-5">
                                        {!! Form::select('distributor_id', $distributors, old('distributor_id'), ['class' => 'form-control select2','placeholder'=>'--Select--','required' => '']) !!}    
                                        </div>
                                    </div>                                  

                                    <div class="form-group mb-3">
                                        {!! Form::label('enterprise_id', 'Enterprise (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                        <div class="col-sm-5">
                                       {!! Form::select('enterprise_id', $enterprises, old('enterprise_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!}    
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        {!! Form::label('enterprise_variety_id', 'Enterprise Variety (optional)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                        <div class="col-sm-5">
                                       {!! Form::select('enterprise_variety_id', $enterprise_varieties, old('enterprise_variety_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        {!! Form::label('enterprise_type_id', 'Enterprise Type (optional)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                        <div class="col-sm-5">
                                       {!! Form::select('enterprise_type_id', $enterprise_types, old('enterprise_type_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        {!! Form::label('services', 'Price (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                            <div class="col-sm-5">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    {!! Form::text('price', old('price'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'id' => 'price']) !!}              
                                                </div>
                                                <div class="col-sm-6">
                                                    {!! Form::select('currency_id', $currencies, old('currency_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Currency--']) !!}        
                                                </div> 
                                            </div>
                                        </div> 
                                    </div> 

                                    <div class="form-group mb-3">
                                        {!! Form::label('unit_id', 'Per Unit (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                        <div class="col-sm-5">
                                       {!! Form::select('unit_id', $units, old('units'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!}    
                                        </div>
                                    </div>

                                    {{-- <div class="form-group mb-3">
                                        {!! Form::label('start_date', 'Price Start', ['class' => 'col-sm-4 col-form-label']) !!}                
                                        <div class="col-sm-5">
                                        {!! Form::date('start_date', old('start_date'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'data-provider' => 'flatpickr', 'data-date-format' => 'Y-m-d']) !!}               
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        {!! Form::label('end_date', 'Price end Date*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                        <div class="col-sm-5">
                                        {!! Form::date('end_date', old('end_date'), ['class' => 'form-control ', 'placeholder' => '']) !!}               
                                        </div>
                                    </div>      --}} 

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

