@inject('set', 'App\Http\Controllers\Weather\WeatherSubscriptionController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Subscriptions',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of subscriptions</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new subscriptions</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.upload') }}">Upload subscriptions</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            {!! Form::open(['method' => 'POST', 'route' => ['weather-info.subscriptions.store']]) !!}

                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                <input type="hidden" name="payment_confirmation" value="on">

                                <div class="form-group mb-3">
                                    {!! Form::label('country_id', 'Country (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('country_id', $countries, old('country_id'), array('class' => 'form-control select2', 'placeholder' => 'Select Country', 'required' => '')) !!} 
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('organisation_id', 'Organization (Optional)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('organisation_id', $organisations, old('organisation_id'), array('class' => 'form-control select2', 'placeholder' => 'Select Organisation')) !!} 
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
                                    {!! Form::label('phonenumber', 'Phonenumber*', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::text('phonenumber', old('phonenumber'), ['class' => 'form-control', 'placeholder' => 'e.g. 775xxxxxx', 'required' => '']) !!}
                                    </div> 
                                </div> 

                                <div class="form-group mb-3">
                                    {!! Form::label('location_id', 'Location*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('location_id', [null=>'Select'] + $locations, old('location_id'), array('class' => 'form-control select2', 'required' => '', 'id' => 'location')) !!} 
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('language_id', 'Language*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('language_id', $languages, old('language_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!}   
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('frequency', 'Frequency*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('frequency', [null=>'Select Frequency*'] + $frequencies, $package ??  old('frequency'), array('class' => 'form-control select2', 'required' => '', 'id' => 'frequency')) !!}   
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('period_paid', 'Period Paying for', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                       {!! Form::select('period_paid', [null=>'Period*'], old('period_paid'), array('class' => 'form-control select2', 'required' => '', 'id' => 'period')) !!}
                                    </div> 
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('payment_method', 'Payment Method*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5"> 
                                   {!! Form::select('payment_method', [null=>'Select Payment Method*'] + $methods, old('payment_method'), array('class' => 'form-control select2', 'required' => '')) !!}   
                                    </div>
                                </div> 

                                <div class="form-buttons-w">
                                    {!! Form::submit('Subscribe', ['class' => 'btn btn-primary']) !!}
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
    <script type="text/javascript">

          $('#frequency').change(function(){
            $.get("{{ url('get-period-by-frequency')}}", 
                { frequency: $(this).val() }, 
                function(data) {
                    var model = $('#period');
                    model.empty();
                     model.append("<option value='' selected=''>Select Period*</option>");
                    $.each(data, function(index, element) {
                        model.append("<option value='"+ element.id +"'>" + element.name + "</option>");
                    });
                });
            });

          @if(! is_null($package))
          $.get("{{ url('get-period-by-frequency')}}", 
                { frequency: '{{ $package }}' }, 
                function(data) {
                    var model = $('#period');
                    model.empty();
                     model.append("<option value='' selected=''>Select Period*</option>");
                    $.each(data, function(index, element) {
                        model.append("<option value='"+ element.id +"'>" + element.name + "</option>");
                    });
                });
          @endif
    </script>

@endsection

