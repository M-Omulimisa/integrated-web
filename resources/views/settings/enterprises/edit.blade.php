@inject('set', 'App\Http\Controllers\Settings\EnterpriseController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Enterprise',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of enterprises</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">Add new enterprise</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.show',[$enterprise->id]) }}">Country details</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Edit enterprise</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                        {!! Form::model($enterprise, ['class'=>'form-horizontal', 'method' => 'PATCH','route' => ['settings.enterprises.update', $enterprise->id]]) !!}

                            <div class="form-group mb-3">
                                {!! Form::label('name', 'Name (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                </div>
                            </div>

                            <div class="form-group mb-3">
                              {!! Form::label('unit_id', 'Unit (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                              <div class="col-sm-5">
                                  {!! Form::select('unit_id', $units, old('unit_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                  </div>
                              </div>  

                            <div class="form-group mb-3">
                                {!! Form::label('category', 'Category (required)', ['class' => 'col-sm-4 col-form-label']) !!}             
                                <div class="col-sm-5">
                                    {!! Form::select('category', ['Crop'=>'Crop', 'Livestock' => 'Livestock'], old('category'), array('class' => 'chzn_a form-control')) !!}           
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

