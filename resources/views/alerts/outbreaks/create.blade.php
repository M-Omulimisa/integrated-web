@inject('set', 'App\Http\Controllers\Alerts\OutbreakController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Outbreaks',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of outbreaks</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new outbreaks</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            {!! Form::open(['method' => 'POST', 'route' => ['alerts.outbreaks.store']]) !!}  

                                <div class="form-group mb-3">
                                    {!! Form::label('country_id', 'Country (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::select('country_id', $countries, old('country_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!} 
                                    </div> 
                                </div> 

                                <div class="row">
                                {!! Form::label('activity', 'Reported by (required)', ['class' => 'col-sm-12 form-label']) !!}
                                <div class="col-sm-5">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4">
                                          <label class="form-check mb-2">
                                            <input class="form-check-input" name="reported_by" type="radio" value="farmer"><span class="custom-control-label">Farmers
                                            </span>
                                          </label>                     
                                        </div><!-- col-3 -->
                                        <div class="col-lg-4 col-md-4">
                                          <label class="form-check mb-2">
                                            <input class="form-check-input" name="reported_by" type="radio" value="extension"><span class="custom-control-label">Extension Officers
                                            </span>
                                          </label>                     
                                        </div><!-- col-3 -->
                                    </div>
                                </div>
                              </div><!-- row --> 

                              <div class="form-group mb-3">
                                    {!! Form::label('farmer_id', 'Farmer (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::select('farmer_id', $farmers, old('farmer_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!} 
                                    </div> 
                                </div> 

                                <div class="form-group mb-3">
                                    {!! Form::label('extension_officer_id', 'Extension Officer (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::select('extension_officer_id', $extension_officers, old('extension_officer_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!} 
                                    </div> 
                                </div>                              

                                <div class="form-group mb-3">
                                    {!! Form::label('enterprises', 'Enterprises (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::select('enterprises[]', $enterprises, old('enterprises'), ['class' => 'form-control js-example-basic-multiple select2-hidden-accessible', 'multiple'=>'']) !!} 
                                    </div> 
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('locations', 'Location (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::select('locations', $locations, old('locations'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!} 
                                    </div> 
                                </div> 

                                <div class="form-group mb-3">
                                    {!! Form::label('description', 'Description (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::textarea('description', old('description'), ['class' => 'form-control', 'placeholder' => '', 'rows' => 2]) !!}               
                                    </div>
                                </div> 

                                <div class="form-group mb-3">
                                   {!! Form::label('file', 'images (optional)', ['class' => 'col-sm-4 col-form-label']) !!}
                                <div class="col-sm-5">
                                    <input type='file' id="image" name="image[]" accept=".png, .jpg, .jpeg" multiple="">
                                    <span class="help-block">max 3 images</span>
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

