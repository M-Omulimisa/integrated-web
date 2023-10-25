@inject('set', 'App\Http\Controllers\Agents\AgentController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Village Agents',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of agents</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new village agent</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                            {!! Form::open(['method' => 'POST', 'route' => ['village-agents.agents.store']]) !!}

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
                                    <label class="col-sm-3 form-label">Category (required)</label>
                                    <div class="col-sm-5">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input checked="" class="form-check-input" name="category" type="radio" value="village"> Village Agent</label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                               <input class="form-check-input" name="category" type="radio" value="insurance">  Insurance Agent</label>
                                        </div>
                                    </div>
                                </div> 

                                <div class="form-group mb-3">
                                    {!! Form::label('agent_id', 'Supervisor (If supervised)', ['class' => 'col-sm-3 form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::select('agent_id', $agents, old('agent_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!} 
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
                                                <input checked="" class="form-check-input" name="gender" type="radio" value="Male"> Male</label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                               <input class="form-check-input" name="gender" type="radio" value="Female"> Female</label>
                                        </div>
                                    </div>
                                </div> 

                                <div class="form-group mb-3">
                                    {!! Form::label('national_id_number', 'NIN Number (optional)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::text('national_id_number', old('national_id_number'), ['class' => 'form-control', 'placeholder' => '']) !!}               
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('services', 'Phone number (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::text('telephone', old('telephone'), ['class' => 'form-control', 'placeholder' => '775xxxxxx', 'required' => '']) !!}
                                    </div>                                         
                                </div> 
                               
                                <div class="form-group mb-3">
                                    <label class="col-sm-5 form-label">Is phone number your mobile money account? (required)</label>
                                    <div class="col-sm-5">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" name="is_mm_phone" type="radio" value="Yes"> YES</label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                               <input class="form-check-input" name="is_mm_phone" type="radio" value="No"> NO</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('services', 'Mobile money phone number (optional)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::text('mm_telephone', old('mm_telephone'), ['class' => 'form-control', 'placeholder' => '775xxxxxx']) !!}
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

                                <h4><span>Account Settings</span></h4>

                                {{-- <div class="form-group mb-3">
                                    {!! Form::label('code', 'Code*', ['class' => 'col-sm-3 form-label']) !!}
                                    <div class="col-sm-5">
                                    {!! Form::text('code', $agentCode ?? null, ['class' => 'form-control', 'placeholder' => '', 'required' => '','id' => 'code']) !!}
                                    </div>                   
                                </div> --}}
                                   
                                <input type="hidden" name="status" value="1">

                                <div class="form-group mb-3">
                                    {!! Form::label('email', 'Email (optional)', ['class' => 'col-sm-3 form-label']) !!}
                                    <div class="col-sm-5">
                                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    </div>                   
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('password', 'Password*', ['class' => 'col-sm-3 form-label']) !!}
                                    <div class="col-sm-5">                 
                                        {!! Form::text('password', generateCode(4), ['class' => 'form-control']) !!}
                                    </div>
                                </div>

                                <p>Agent will be notified via SMS to provided phone number</p>
                                
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

