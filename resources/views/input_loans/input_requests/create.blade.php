@inject('set', 'App\Http\Controllers\InputLoan\InputRequestController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Markets',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of input requests</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new input request</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            {!! Form::open(['method' => 'POST', 'route' => [$set->_route.'.store']]) !!}

                                <input type="hidden" name="added_by" value="{{ Auth::user()->id }}">

                                <div class="form-group mb-3">
                                    <label class="col-sm-4 col-form-label"> Input Type / Payment Type </label>
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" name="request_type" type="radio" value="cash" checked=""> Cash
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                       <input class="form-check-input" name="request_type" type="radio" value="loan"> Loan
                                                   </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('group_id', 'Season (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('season_id', $seasons, old('season_id'), ['class' => 'form-control select2','placeholder'=>'--Select--', 'id' => 'season_id']) !!}    
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('project_id', 'Project (optional)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('project_id', $projects, old('project_id'), ['class' => 'form-control select2','placeholder'=>'--Select--', 'id' => 'project_id']) !!}    
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('group_id', 'Microfinance (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('group_id', $microfinances, old('group_id'), ['class' => 'form-control select2','placeholder'=>'--Select--', 'id' => 'group_id']) !!}    
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('microfinance_id', 'Farmer Group (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('microfinance_id', $farmer_groups, old('microfinance_id'), ['class' => 'form-control select2','placeholder'=>'--Select--', 'id' => 'microfinance_id']) !!}    
                                    </div>
                                </div>

                                <input type="hidden" name="date_of_request" id="request_date" type="date" 
                                value="{{ date('Y-m-d') }}">

                                <input type="hidden"  name="status" value="preparation">

                                <div class="form-group mb-3 d-none">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-4 p-1">
                                                {!! Form::label('date_of_request', 'Date of Request', ['class' => 'col-sm-6 col-form-label']) !!}
                                                <div class="col-sm-10">
                                                    <input class="form-control" name="date_of_request" id="request_date" type="date" value="{{ date('Y-m-d') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4 p-1">
                                                {!! Form::label('status', 'Status*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                                <div class="col-sm-9">
                                                    <select class="form-control select2" name="status">
                                                        <option value="preparation">Preparation</option>
                                                        <option value="approved">Approved</option>
                                                        <option value="rejected">Rejected</option>
                                                        <option value="paid">Paid</option>
                                                        <option value="request">Request</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="inputs_value" value="0.0">

                                <input type="hidden" name="loan_fees" value="0.0">

                                <div class="form-group mb-3 d-none">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6 p-2">
                                                {!! Form::label('inputs_value', 'Inputs Value*', ['class' => 'col-md-4 col-form-label']) !!}                
                                                <div class="col-sm-10">
                                                    {!! Form::number('inputs_value', '0.00', ['step' => '100', 'class' => 'form-control', 'placeholder' => '', 'required' => ''], old('inputs_value')) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 p-2">
                                                {!! Form::label('loan_fees', 'Loan Fees', ['class' => 'col-md-4 col-form-label']) !!}                
                                                <div class="col-sm-10">
                                                    {!! Form::number('loan_fees', '0.00', ['step' => '100', 'class' => 'form-control', 'placeholder' => ''], old('loan_fees')) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>                  
                                </div>

                                <input type="hidden" name="total_loan_amount" value="0.0">

                                <input type="hidden" name="total_agent_insurance_commission" value="0.0">

                                <div class="form-group mb-3 d-none">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6 p-2">
                                                {!! Form::label('total_loan_amount', 'Total Loan Amount', ['class' => 'col-md-4 col-form-label']) !!}                
                                                <div class="col-sm-10">
                                                    {!! Form::number('total_loan_amount', '0.00', ['step' => '100', 'class' => 'form-control', 'id' => 'total_loan_amount', 'placeholder' => ''], old('total_loan_amount')) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 p-2">
                                                {!! Form::label('total_agent_insurance_commission', 'Total Agent Insurance Commission', ['Added By' => 'col-md-6 col-form-label']) !!}                
                                                <div class="col-sm-10">
                                                    {!! Form::number('total_agent_insurance_commission', '', ['step' => '100', 'min' => '10000', 'max' => '40000000', 'class' => 'form-control', 'placeholder' => ''], old('total_agent_insurance_commission')) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>                  
                                </div>

                                <input type="hidden" name="total_input_commission" value="0.0">

                                <div class="form-group mb-3 d-none">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6 p-2">
                                                {!! Form::label('total_input_commission', 'Total Input Commission', ['class' => 'col-md-6 col-form-label']) !!}                
                                                <div class="col-sm-10">
                                                    {!! Form::number('total_input_commission', '', ['step' => '100', 'min' => '10000', 'max' => '40000000', 'class' => 'form-control', 'placeholder' => ''], old('total_input_commission')) !!}
                                                </div>
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

