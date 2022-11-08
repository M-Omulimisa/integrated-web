@inject('set', 'App\Http\Controllers\Farmers\FarmerGroupController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Groups',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of farmer groups</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new farmer group</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                        {!! Form::open(['method' => 'POST', 'route' => ['farmers.groups.store']]) !!}

                            <div class="form-group mb-3">
                                {!! Form::label('country_id', 'Country (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                               {!! Form::select('country_id', $countries, old('country_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('organisation_id', 'Organisation (optional)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                               {!! Form::select('organisation_id', $organisations ?? [], old('organisation_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('agent_id', 'Managed by (optional)', ['class' => 'col-sm-3 form-label']) !!}
                                <div class="col-sm-5">
                                    {!! Form::select('agent_id', $agents, old('agent_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!} 
                                    <span class="help-block">Agent supporting the group</span>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('name', 'Group Name (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('code', 'Group Code (required)', ['class' => 'col-sm-3 form-label']) !!}
                                <div class="col-sm-5">
                                {!! Form::text('code', $groupCode, ['class' => 'form-control', 'placeholder' => '', 'required' => '','id' => 'code']) !!}
                                </div>                   
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('group_leader_name', "Group Leader's Name (required)", ['class' => 'col-sm-12 form-label']) !!}
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
                                {!! Form::label('group_leader_contact', "Group Leader's Contact (required)", ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('group_leader_contact', old('group_leader_contact'), ['class' => 'form-control', 'placeholder' => '775xxxxxx', 'required' => '']) !!}               
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('establishment_year', 'Establishment Year (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('establishment_year', old('establishment_year'), ['class' => 'form-control', 'placeholder' => 'YYYY e.g 1990', 'required' => '']) !!}               
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('subcounty_id', 'Location (required)', ['class' => 'col-sm-3 form-label']) !!}
                                <div class="col-sm-5">
                                    @if (isset($locations) && count($locations) > 0)
                                    {!! Form::select('location_id', $locations, old('location_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!}
                                    @else
                                    No Locations in the system. Ensure they are set to continue
                                    @endif 
                                </div> 
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('meeting_venue', 'Meeting Venue (optional)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                    {!! Form::text('meeting_venue', old('meeting_venue'), ['class' => 'form-control', 'placeholder' => 'Venue/Place']) !!}               
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

                            <div class="form-group mb-3">
                                {!! Form::label('organisation_id', 'Meeting Frequency (optional)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                               {!! Form::select('meeting_frequency', $meeting_frequencies, old('meeting_frequency'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                </div>
                            </div>

                            <div class="row">
                                {!! Form::label('day', 'Meeting day(s) (optional)', ['class' => 'col-sm-12 form-label']) !!}
                                <div class="row">
                                    @if (isset($meeting_days) && count($meeting_days) > 0)
                                        @for ($i = 0; $i < count($meeting_days); $i++)
                                            <div class="col-lg-12 col-md-12">
                                              <label class="form-check mb-2">
                                                <input class="form-check-input" name="meeting_days[]" type="checkbox" value="{{ $meeting_days[$i] }}" {{ old('day') ? 'checked=""' : '' }}><span class="form-check-label">{{ $meeting_days[$i] }}</span>
                                              </label>                     
                                            </div><!-- col-3 -->
                                            @endfor
                                        @endif                                            
                                </div>
                              </div><!-- row -->

                              <div class="form-group mb-3">
                                {!! Form::label('meeting_time', "Meeting Time (optional)", ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('meeting_time', old('meeting_time'), ['class' => 'form-control', 'placeholder' => '00:00', 'id' => 'timepicker']) !!}             
                                </div>
                            </div>

                              <div class="row">
                                {!! Form::label('activity', 'Choose group main enterprises (required)', ['class' => 'col-sm-12 form-label']) !!}
                                @if (isset($enterprises) && count($enterprises) > 0)
                                    @foreach ($enterprises as $activity)
                                    <div class="col-lg-12 col-md-12">
                                      <label class="form-check mb-2">
                                        <input class="form-check-input" name="enterprises[]" type="checkbox" value="{{ $activity->id }}" {{ old('activity') ? 'checked=""' : '' }}><span class="form-check-label">{{ $activity->name }}</span>
                                      </label>                     
                                    </div><!-- col-3 -->
                                    @endforeach
                                @endif
                              </div><!-- row -->

                            <div class="form-group mb-3">
                                {!! Form::label('last_cycle_savings', 'Amount of verifiable savings in the last cycle (required)', ['class' => 'col-sm-12 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('last_cycle_savings', old('last_cycle_savings'), ['class' => 'form-control', 'placeholder' => '0', 'required'=>'']) !!}               
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

