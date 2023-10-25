@inject('set', 'App\Http\Controllers\Alerts\AlertController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Alerts',
    'menu_item_url' => route($set->_route.'.index'),
    'current'       => 'Send New'
])
<!-- end page title -->

<div class="row">
    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card" id="pages">
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of alerts</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('alerts.single.create') }}">Send single alert</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('alerts.bulk.create') }}">Send bulk alert</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('alerts.keyword.create') }}">Send by language</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('alerts.enterprise.create') }}">Send by enterpise</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('alerts.area.create') }}">Send by location</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('alerts.user-group.create') }}">Send to user groups</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('alerts.farmer-group.create') }}">Send to farmer groups</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('alerts.group-member.create') }}">Send to group members</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            {!! Form::open(['method' => 'POST', 'route' => ['alerts.alerts.store'],'files' => true]) !!} 

                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">     
                                <input type="hidden" name="type" value="user_groups">  

                                <div class="form-group mb-3">
                                    {!! Form::label('country_id', 'Country (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('country_id', $countries, old('country_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                    </div>
                                </div>                         

                                <div class="form-group mb-3">
                                    {!! Form::label('user_roups', 'User group (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::select('user_roups[]', $user_groups, old('user_roups'), ['class' => 'form-control js-example-basic-multiple select2-hidden-accessible', 'multiple'=>'']) !!} 
                                    </div> 
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('message', 'Message*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::textarea('message', old('message'), ['class' => 'form-control', 'placeholder' => '', 'rows' => 2]) !!}               
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                   {!! Form::label('file', 'Choose image to upload(Optional)', ['class' => 'col-sm-4 col-form-label']) !!}
                                <div class="col-sm-5">
                                    <input type='file' id="image" name="image[]" accept=".png, .jpg, .jpeg" multiple="">
                                </div>
                                    </div>   
                                   
                                <div class="form-group mb-3">
                                    <label class="col-sm-4 col-form-label">Is scheduled?</label>
                                    <div class="col-sm-8">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input checked="" class="form-check-input" name="is_scheduled" type="radio" value="0" data-id="noidentity">NO</label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                               <input class="form-check-input" name="is_scheduled" type="radio" value="1" data-id="identity">YES</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="none" id="noidentity">

                                    <div class="form-group mb-3">
                                        {!! Form::label('date', 'Date(* if Yes)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                        <div class="col-sm-5">
                                            {!! Form::text('date', old('date'), ['class' => 'form-control', 'placeholder' => 'YYYY-MM-DD', 'data-provider' => 'flatpickr', 'data-date-format' => 'Y-m-d']) !!}               
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        {!! Form::label('time', 'Time(* if Yes)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                        <div class="col-sm-5">
                                        {!! Form::select('time', $time_intervals, old('time'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}               
                                        </div>
                                    </div>
                                    
                                </div>

                                <div id="identity"> </div>

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

