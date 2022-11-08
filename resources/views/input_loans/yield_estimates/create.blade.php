@inject('set', 'App\Http\Controllers\InputLoan\YieldEstimationController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Yield estimates',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of yield estimates</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new yield estimate</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            {!! Form::open(['method' => 'POST', 'route' => [$set->_route.'.store']]) !!}

                                <div class="form-group mb-3">
                                  {!! Form::label('enterprise_id', 'Enterprise (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                  <div class="col-sm-5">
                                      {!! Form::select('enterprise_id', $enterprises, old('enterprise_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                      </div>
                                  </div>  

                                <div class="form-group mb-3">
                                  {!! Form::label('project_id', 'Variety (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                  <div class="col-sm-5">
                                      {!! Form::select('enterprise_variety_id', $enterprise_varieties, old('project_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                      </div>
                                  </div>  

                                  <div class="form-group mb-3">
                                        {!! Form::label('min_estimate', "Farm size", ['class' => 'col-sm-12 form-label']) !!}
                                        <div class="col-sm-5">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                {!! Form::text('farm_size', old('farm_size'), ['class' => 'form-control', 'placeholder' => '0']) !!}               
                                                </div>
                                                <div class="col-sm-6">
                                                {!! Form::select('farm_size_unit_id', $units, old('farm_size_unit_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}              
                                                </div> 
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="form-group mb-3">
                                        {!! Form::label('min_estimate', "Input Estimate", ['class' => 'col-sm-12 form-label']) !!}
                                        <div class="col-sm-5">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                {!! Form::text('input_estimate', old('input_estimate'), ['class' => 'form-control', 'placeholder' => '0']) !!}               
                                                </div>
                                                <div class="col-sm-6">
                                                {!! Form::select('input_unit_id', $units, old('input_unit_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}              
                                                </div> 
                                            </div>
                                        </div>
                                    </div>   

                                        <div class="form-group mb-3">
                                            {!! Form::label('output_min_estimate', "Output Estimates", ['class' => 'col-sm-12 form-label']) !!}
                                            <div class="col-sm-5">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                    {!! Form::text('output_min_estimate', old('output_min_estimate'), ['class' => 'form-control', 'placeholder' => 'Min e.g. 500']) !!}               
                                                    </div>
                                                    <div class="col-sm-4">
                                                    {!! Form::text('output_max_estimate', old('output_max_estimate'), ['class' => 'form-control', 'placeholder' => 'Max e.g. 1000']) !!}               
                                                    </div> 
                                                    <div class="col-sm-4">
                                                    {!! Form::select('output_unit_id', $units, old('output_unit_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}               
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 

                                        <div class="form-buttons-w ml-5">
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

