@inject('set', 'App\Http\Controllers\Extension\ExtensionOfficerController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Extension Officers',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of extension officers</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new extension officer</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                                {!! Form::open(['method' => 'POST', 'route' => ['extension-officers.officers.store']]) !!}

                                    <div class="form-group mb-3">
                                        {!! Form::label('country_id', 'Country (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                        <div class="col-sm-5">
                                       {!! Form::select('country_id', $countries, old('country_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        {!! Form::label('organisation_id', 'Organisation(Optional)', ['class' => 'col-sm-3 form-label']) !!}             
                                        <div class="col-sm-5">
                                       {!! Form::select('organisation_id', $organisations, old('organisation_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        {!! Form::label('name', "Name (required)", ['class' => 'col-sm-12 form-label']) !!}
                                        <div class="col-sm-5">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                {!! Form::text('first_name', old('first_name'), ['class' => 'form-control', 'placeholder' => 'First name']) !!}               
                                                </div>
                                                <div class="col-sm-6">
                                                {!! Form::text('last_name', old('last_name'), ['class' => 'form-control', 'placeholder' => 'Last name']) !!}               
                                                </div> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="col-sm-3 form-label">Gender (required)</label>
                                        <div class="col-sm-5">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input checked="" class="form-check-input" name="category" type="radio" value="Extension Officer"> Extension Officer</label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                   <input class="form-check-input" name="category" type="radio" value="Expert"> Expert</label>
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="form-group mb-3">
                                        {!! Form::label('position_id', 'Position (required)', ['class' => 'col-sm-3 form-label']) !!}             
                                        <div class="col-sm-5">
                                       {!! Form::select('position_id', $positions, old('position_id'), ['class' => 'form-control select2','placeholder'=>'--Select--', 'required' => 'required']) !!}    
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        {!! Form::label('extension_officer_id', 'Supervisor(If supervised)', ['class' => 'col-sm-3 form-label']) !!}             
                                        <div class="col-sm-5">
                                       {!! Form::select('extension_officer_id', $extension_officers, old('extension_officer_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        {!! Form::label('name', 'Name (required)', ['class' => 'col-sm-3 form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'id' => 'name']) !!}
                                    </div> 
                                        </div> 
                                       
                                    <div class="form-group mb-3">
                                        <label class="col-sm-3 form-label">Gender (required)</label>
                                        <div class="col-sm-5">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input checked="" class="form-check-input" name="gender" type="radio" value="Male"> Male</label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                   <input class="form-check-input" name="gender" type="radio" value="Female"> Female</label>
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="form-group mb-3">
                                        {!! Form::label('education_level', 'Education Level (required)', ['class' => 'col-sm-3 form-label']) !!}
                                        <div class="col-sm-5">
                                            {!! Form::select('education_level', $education_levels, old('education_level'), ['class' => 'form-control select2','placeholder'=>'--Select--', 'required' => '']) !!} 
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        {!! Form::label('services', 'Telephone (optional)', ['class' => 'col-sm-4 col-form-label']) !!}
                                        <div class="col-sm-5">
                                            {!! Form::text('phone', old('phone'), ['class' => 'form-control', 'placeholder' => '775xxxxxx']) !!}
                                        </div>                                         
                                    </div> 

                                      <div class="row">
                                        {!! Form::label('language', 'Choose prefered language (required)', ['class' => 'col-sm-12 form-label']) !!}
                                        @if (isset($languages) && count($languages) > 0)
                                            @foreach ($languages as $language)
                                            <div class="col-lg-12 col-md-12">
                                              <label class="form-check mb-2">
                                                <input class="form-check-input" name="languages[]" type="checkbox" value="{{ $language->id }}" {{ old('language') ? 'checked=""' : '' }}><span class="form-check-label">{{ $language->name }}</span>
                                              </label>                     
                                            </div><!-- col-3 -->
                                            @endforeach                                            
                                        @endif
                                      </div><!-- row -->

                                    <div class="form-group mb-3">
                                        {!! Form::label('location_id', 'Location (required)', ['class' => 'col-sm-3 form-label']) !!}
                                        <div class="col-sm-5">
                                            @if (isset($locations) && count($locations) > 0)
                                            {!! Form::select('location_id', $locations, old('location_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!}
                                            @else
                                            No Locations in the system. Ensure they are set to continue
                                            @endif 
                                        </div> 
                                    </div>

                                    <div class="form-group mb-3">
                                        <div class="col-sm-5">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                {!! Form::text('longitude', old('longitude'), ['class' => 'form-control', 'placeholder' => 'Longitude (optional)']) !!}               
                                                </div>
                                                <div class="col-sm-6">
                                                {!! Form::text('latitude', old('latitude'), ['class' => 'form-control', 'placeholder' => 'Latitude (optional)']) !!}               
                                                </div> 
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="mt-2"><span>Account Settings</span></h5>
                                       
                                    <input type="hidden" name="status" value="Active">

                                    <div class="form-group mb-3">
                                        {!! Form::label('email', 'Email (optional)', ['class' => 'col-sm-3 form-label']) !!}
                                        <div class="col-sm-5">
                                        {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                        </div>                   
                                    </div>

                                    <div class="form-group mb-3">
                                        {!! Form::label('password', 'Password (required)', ['class' => 'col-sm-3 form-label']) !!}
                                        <div class="col-sm-5">
                                            Account details will be sent to the telephone/email. Ensure they are correct
                                            <input type="text" name="password" value="{{ generateCode(4) }}" class="form-control">
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

