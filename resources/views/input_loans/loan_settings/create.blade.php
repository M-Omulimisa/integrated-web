@inject('set', 'App\Http\Controllers\InputLoan\LoanSettingController')
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of loan settings</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new loan settings</a></li>
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
                                        {!! Form::label('group_leader_name', "Number of Farmers applying in a group", ['class' => 'col-sm-12 form-label']) !!}
                                        <div class="col-sm-5">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                {!! Form::text('min_group_members', old('min_group_members'), ['class' => 'form-control', 'placeholder' => 'Min']) !!}               
                                                </div>
                                                <div class="col-sm-6">
                                                {!! Form::text('max_group_members', old('max_group_members'), ['class' => 'form-control', 'placeholder' => 'Max']) !!}               
                                                </div> 
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="form-group mb-3">
                                        {!! Form::label('group_leader_name', "Loan amount per Group", ['class' => 'col-sm-12 form-label']) !!}
                                        <div class="col-sm-5">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                {!! Form::text('min_group_loan_amount', old('min_group_loan_amount'), ['class' => 'form-control', 'placeholder' => 'Min']) !!}               
                                                </div>
                                                <div class="col-sm-6">
                                                {!! Form::text('max_group_loan_amount', old('max_group_loan_amount'), ['class' => 'form-control', 'placeholder' => 'Max']) !!}               
                                                </div> 
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="form-group mb-3">
                                        {!! Form::label('group_leader_name', "Loan amount per Farmer in a Group", ['class' => 'col-sm-12 form-label']) !!}
                                        <div class="col-sm-5">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                {!! Form::text('min_amount_per_farmer', old('min_amount_per_farmer'), ['class' => 'form-control', 'placeholder' => 'Min']) !!}               
                                                </div>
                                                <div class="col-sm-6">
                                                {!! Form::text('max_amount_per_farmer', old('max_amount_per_farmer'), ['class' => 'form-control', 'placeholder' => 'Max']) !!}               
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

