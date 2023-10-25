@inject('set', 'App\Http\Controllers\Insurance\InsuranceCalculatorController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Calculator',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of calculation values</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">Add new calculation values</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.show',[$calculator->id]) }}">Calculation details</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Edit calculation values</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                        {!! Form::model($calculator, ['class'=>'form-horizontal', 'method' => 'PATCH','route' => ['insurance.calculator.update', $calculator->id]]) !!}

                                <h5 class="text-info">Location</h5>

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
                                    {!! Form::label('location_level_id', 'Administration Level (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('location_level_id', $admin_levels, old('location_level_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!} 
                                   <span class="help-block">For locations with subsidies</span>   
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('locations', 'Locations with subsidies (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        <select class="form-control js-example-basic-multiple select2-hidden-accessible" name="locations[]" multiple>
                                            @if (count($locations) > 0)
                                                @foreach ($locations as $location)
                                                    <option value="{{$location->id}}" {{ $calculator->isLocationWithSubsidy($location->id) ? 'selected=""' : '' }}>{{ $location->name }}</option>                                        
                                                @endforeach
                                            @endif
                                        </select>
                                    </div> 
                                </div>

                                <h5 class="text-info">Enterprises</h5>

                                <div class="form-group mb-3">
                                    {!! Form::label('enterprises', 'Enterprises with subsidies (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        <select class="form-control js-example-basic-multiple select2-hidden-accessible" name="enterprises[]" multiple>
                                            @if (count($enterprises) > 0)
                                                @foreach ($enterprises as $enterprise)
                                                    <option value="{{$enterprise->id}}" {{ $calculator->isEnterpriseWithSubsidy($enterprise->id) ? 'selected=""' : '' }}>{{ $enterprise->name }}</option>                                        
                                                @endforeach
                                            @endif
                                        </select>
                                    </div> 
                                </div>

                                <h5 class="text-info">Sum Insured Multiplier</h5>
                                <p>Hint: Basic Premium = Sum Insured x (this value)%</p>

                                <div class="form-group mb-3">
                                    {!! Form::label('sum_insured', 'For locations without subsidy (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::text('sum_insured', old('sum_insured'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('sum_insured_special', 'For locations with subsidy (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::text('sum_insured_special', old('sum_insured_special'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                    </div>
                                </div>

                                <h5 class="text-info">Government Subsidy</h5>

                                <div class="form-group mb-3">
                                    {!! Form::label('govt_subsidy_none', 'No Government subsidy (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::text('govt_subsidy_none', old('govt_subsidy_none'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('govt_subsidy_locations', 'For specific districts (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::text('govt_subsidy_locations', old('govt_subsidy_locations'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('govt_subsidy_small_scale', 'For small scale (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::text('govt_subsidy_small_scale', old('govt_subsidy_small_scale'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('govt_subsidy_large_scale', 'For large scale (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::text('govt_subsidy_large_scale', old('govt_subsidy_large_scale'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                    </div>
                                </div>

                                <h5 class="text-info">Taxes (%)</h5>

                                <div class="form-group mb-3">
                                    {!! Form::label('ira_levy', 'IRA Levy (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::text('ira_levy', old('ira_levy'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                    </div>
                                </div>

                                <h5 class="text-info">Commission (%)</h5>

                                <div class="form-group mb-3">
                                    {!! Form::label('commission', 'Village agent commission (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::text('commission', old('commission'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                    </div>
                                </div>

                                <h5 class="text-info">Others</h5>

                                <div class="form-group mb-3">
                                    {!! Form::label('scale_limit', 'Farming scale determinant (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::text('scale_limit', old('scale_limit'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
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

