@inject('set', 'App\Http\Controllers\Farmers\FarmerController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Farmers',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.show',[$farmer->id]) }}">Farmer details</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Edit farmer</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                            {!! Form::model($farmer, ['class'=>'form-horizontal', 'method' => 'PATCH','route' => ['farmers.farmers.update', $farmer->id]]) !!}

                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                            <div class="form-group mb-3">
                                {!! Form::label('country_id', 'Country (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                               {!! Form::select('country_id', $countries, old('country_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                </div>
                            </div>

                            @if (isset($group) && $group)
                                <div class="form-group mb-3">
                                    {!! Form::label('group', 'Group (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                  {!! Form::text('group', $group->group_name ?? null, ['class' => 'form-control', 'disabled' => 'true']) !!}    
                                    </div>
                                </div>
                                <input type="hidden" name="group_id" value="{{ $group->id }}">
                                <input type="hidden" name="organisation_id" value="{{ $group->organisation_id }}">

                                <div class="form-group mb-3">
                                    {!! Form::label('role_in_group', 'Role in Group (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::text('role_in_group', old('role_in_group'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                    </div>
                                </div>

                            @else

                                <div class="form-group mb-3">
                                    {!! Form::label('organisation_id', 'Organisation (optional)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('organisation_id', $organisations ?? [], old('organisation_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                    </div>
                                </div>

                            @endif

                            <div class="form-group mb-3">
                                {!! Form::label('language_id', 'Language (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                               {!! Form::select('language_id', $languages ?? [], old('language_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!}    
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('group_leader_name', "Name (required)", ['class' => 'col-sm-12 form-label']) !!}
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
                                            <input checked="" class="form-check-input" name="gender" type="radio" value="Male"> Male</label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                           <input class="form-check-input" name="gender" type="radio" value="Female"> Female</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('year_of_birth', 'Year of Birth (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('year_of_birth', old('year_of_birth'), ['class' => 'form-control fc-datepicker', 'placeholder' => 'e.g. 1990', 'required'=>'']) !!}               
                                </div>
                            </div> 

                            <div class="form-group mb-3">
                                {!! Form::label('national_id_number', 'NIN Number (optional)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('national_id_number', old('national_id_number'), ['class' => 'form-control', 'placeholder' => '']) !!}               
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
                               
                            <div class="form-group mb-3">
                                <label class="col-sm-3 form-label">Is this your phone number? (optional)</label>
                                <div class="col-sm-5">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" name="is_your_phone" type="radio" value="1"> YES</label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                           <input class="form-check-input" name="is_your_phone" type="radio" value="0"> NO</label>
                                    </div>
                                </div>
                            </div>
                               
                            <div class="form-group mb-3">
                                <label class="col-sm-3 form-label">Is phone registered for Mobile Money? (optional)</label>
                                <div class="col-sm-5">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" name="is_mm_registered" type="radio" value="1"> YES</label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                           <input class="form-check-input" name="is_mm_registered" type="radio" value="0"> NO</label>
                                    </div>
                                </div>
                            </div>
                               
                            <div class="form-group mb-3">
                                <label class="col-sm-3 form-label">Farming Scale (optional)</label>
                                <div class="col-sm-5">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" name="farming_scale" type="radio" value="Small"> Small scale</label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                           <input class="form-check-input" name="farming_scale" type="radio" value="Medium"> Medium Scale</label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                           <input class="form-check-input" name="farming_scale" type="radio" value="Large"> Large Scale</label>
                                    </div>
                                </div>
                            </div>

                              <div class="row">
                                {!! Form::label('activity', 'Choose Enterprise (required)', ['class' => 'col-sm-12 form-label']) !!}
                                @if (isset($enterprises) && count($enterprises) > 0)
                                    @foreach ($enterprises as $activity)
                                    <div class="col-lg-12 col-md-12">
                                      <label class="form-check mb-2">
                                        <input class="form-check-input" name="activities[]" type="checkbox" value="{{ $activity->id }}" {{ old('activity') ? 'checked=""' : '' }}><span class="custom-control-label">{{ $activity->name }}</span>
                                      </label>                     
                                    </div><!-- col-3 -->
                                    @endforeach
                                @endif
                              </div><!-- row -->

                            <div class="form-group mb-3">
                                {!! Form::label('other_economic_activity', 'Other Activities (optional)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('other_economic_activity', old('other_economic_activity'), ['class' => 'form-control', 'placeholder' => 'e.g. activity1, activity2, activity3']) !!}               
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('land_holding_in_acres', 'Total land holding in acres (optional)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('land_holding_in_acres', 0, ['class' => 'form-control', 'placeholder' => '']) !!}               
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('land_under_farming_in_acres', 'Land under farming in acres (optional)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('land_under_farming_in_acres', 0, ['class' => 'form-control', 'placeholder' => '']) !!}               
                                </div>
                            </div>
                               
                            <div class="form-group mb-3">
                                <label class="col-sm-3 form-label">Ever bought agriculture insurance? (optional)</label>
                                <div class="col-sm-5">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" name="ever_bought_insurance" type="radio" value="1"> YES</label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                           <input class="form-check-input" name="ever_bought_insurance" type="radio" value="0"> NO</label>
                                    </div>
                                </div>
                            </div>
                               
                            <div class="form-group mb-3">
                                <label class="col-sm-3 form-label">Ever received credit from an MFI for agriculture? (optional)</label>
                                <div class="col-sm-5">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" name="ever_received_credit" type="radio" value="1"> YES</label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                           <input class="form-check-input" name="ever_received_credit" type="radio" value="0"> NO</label>
                                    </div>
                                </div>
                            </div>

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

                            <h4 class="mt-2"><span>Account Settings</span></h4>

                            <input type="hidden" name="status" value="Active">

                            <div class="form-group mb-3">
                                {!! Form::label('email', 'Email (optional)', ['class' => 'col-sm-3 form-label']) !!}
                                <div class="col-sm-5">
                                {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                </div>                   
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('password', 'PIN (required)', ['class' => 'col-sm-3 form-label']) !!}
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="password" value="{{ rand(1000,9999) }}">
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

