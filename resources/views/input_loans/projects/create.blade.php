@inject('set', 'App\Http\Controllers\InputLoan\InputProjectController')
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of projects</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new projects</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            {!! Form::open(['method' => 'POST', 'route' => [$set->_route.'.store']]) !!}

                                <div class="form-group mb-3">
                                  {!! Form::label('organisation_id', 'Organisation', ['class' => 'col-sm-4 col-form-label']) !!}                
                                  <div class="col-sm-5">
                                  {!! Form::select('organisation_id', $organisations, old('organisation_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                  </div>
                              </div>            
                                <div class="form-group mb-3">
                                    {!! Form::label('name', 'Name*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                  {!! Form::label('description', 'Description*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                  <div class="col-sm-5">
                                  {!! Form::textarea('description', old('description'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                  </div>
                              </div>
                                <div class="form-group mb-3">
                                  {!! Form::label('start_date', 'Start Date*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                  <div class="col-sm-5">
                                 {!! Form::date('start_date',  old('start_date'), ['class' => 'form-control','required' => '', 'data-provider' => 'flatpickr', 'data-date-format' => 'Y-m-d']) !!}    
                                  </div>
                              </div>

                              <div class="form-group mb-3">
                                {!! Form::label('end_date', 'End Date*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                <div class="col-sm-5">
                               {!! Form::date('end_date',  old('end_date'), ['class' => 'form-control','required' => '', 'data-provider' => 'flatpickr', 'data-date-format' => 'Y-m-d']) !!}    
                                </div>
                            </div>

                            @can('add_units')
                            <div class="form-group mb-3">
                              {!! Form::label('users', 'Officers', ['class' => 'col-sm-4 col-form-label']) !!}                
                              <div class="col-sm-5">
                              {!! Form::select('users[]', $users, old('users'), ['class' => 'form-control select2','multiple'=>'']) !!}    
                              </div>
                            </div> 
                            @endcan

                             

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

