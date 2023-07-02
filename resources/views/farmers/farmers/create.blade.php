@inject('set', 'App\Http\Controllers\Farmers\FarmerController')
@extends('layouts.app')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Farmers',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of farmers</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new farmer</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                            {!! Form::open(['method' => 'POST', 'route' => ['farmers.farmers.store']]) !!}

                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                            <div class="form-group mb-3">
                                {!! Form::label('country_id', 'Country (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">

                                    <select id="countries" name="country_id" required>
                                        <option></option>
                                        @foreach($countries as $country)
                                        <option value="{{$country->id}}">{{$country->name}}</option>
                                        @endforeach
                                    </select>   

                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('organisation_id', 'Organisation (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                    <select required id="organisations" name="organisation_id" class="form-control">
                                        <option value="" selected>Choose Item...</option> 
                                    </select>    
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('farmer_group_id', 'Farmer Group (optional)', ['class' => 'col-sm-3 form-label']) !!}
                                <div class="col-sm-5">
                                    <select required id="farmer_groups" name="farmer_group_id" class="form-control">
                                        <option value="" selected>Choose Item...</option> 
                                    </select>    
                                </div>
                               
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('agent_id', 'Managed by (optional)', ['class' => 'col-sm-3 form-label']) !!}
                                <div class="col-sm-5">
                                    <select required id="agents" name="agent_id" class="form-control">
                                        <option value="" selected>Choose Item...</option> 
                                    </select>    
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('language_id', 'Language (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">

                                    <select id="languages" name="language_id" required>
                                        <option></option>
                                        @foreach($languages as $language)
                                        <option value="{{$language->id}}">{{$language->name}}</option>
                                        @endforeach
                                    </select>   

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

                                    <select id="education_levels" name="education_level" required>
                                        <option></option>
                                        @foreach($education_levels as $education)
                                        <option value="{{$education}}">{{$education}}</option>
                                        @endforeach
                                    </select>   

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
                                <label class="col-sm-6 form-label">Is phone registered for Mobile Money? (optional)</label>
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
                                <label class="col-sm-6 form-label">Ever bought agriculture insurance? (optional)</label>
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
                                <label class="col-sm-6 form-label">Ever received credit from an MFI for agriculture? (optional)</label>
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
                                    <select required id="locations" name="location_id" class="form-control">
                                        <option value="" selected>Choose Item...</option> 
                                    </select>   
                                </div> 
                            </div>
                            <br/>

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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script>
$(document).ready(function() {

///////////////////////////AJAX CSRF SET UP //////////////////////////////////////////////
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });



//////////////////// SELECT2 INITIALISATION ///////////////////////////////////////////////

    $('#organisations').select2({
        
        'placeholder': 'select an organisation'
    });


    $('#countries').select2({
        
        'placeholder': 'select a country'
    });

    $('#farmer_groups').select2({
        
        'placeholder': 'select a farmer group'
    });

    $('#languages').select2({
        
        'placeholder': 'select a language '
    });

    $('#agents').select2({
        
        'placeholder': 'select a agent'
    });


    $('#education_levels').select2({
        
        'placeholder': 'select an education level'
    });

    $('#locations').select2({
        
        'placeholder': 'select a location'
    });



    $('#countries').change(function () {
        var id = $(this).val();

        $.ajax({
            url:'/organisations/organisations-by-country/'+id,
            type:'get',
            dataType:'json',
            success:function (response) {
                $("#organisations").empty();
                console.log(response);
                var len = 0;
                if (response.items != null) {
                    len = response.items.length;
                }

                if (len>0) {

                    first_option_line = '<option value ="">Choose Item... </option>'
                    $("#organisations").append(first_option_line);
                    for (var i = 0; i<len; i++) {
                            var id = response.items[i].id;
                            var name = response.items[i].name;

                            var option = "<option value='"+id+"'>"+name+"</option>"; 

                            $("#organisations").append(option);
                    }
                }
            }
        });

        $.ajax({
            url:'/settings/location-by-country/'+id,
            type:'get',
            dataType:'json',
            success:function (response) {
                $("#locations").empty();
                console.log(response);
                var len = 0;
                if (response.items != null) {
                    len = response.items.length;
                }

                if (len>0) {

                    first_option_line = '<option value ="">Choose Item... </option>'
                    $("#locations").append(first_option_line);
                    for (var i = 0; i<len; i++) {
                            var id = response.items[i].id;
                            var name = response.items[i].name;

                            var option = "<option value='"+id+"'>"+name+"</option>"; 

                            $("#locations").append(option);
                    }
                }
            }
        })
    });

    $('#organisations').change(function () {
        var id = $(this).val();

        $.ajax({
            url:'/farmers/groups-by-organisation/'+id,
            type:'get',
            dataType:'json',
            success:function (response) {
                $("#farmer_groups").empty();
                console.log(response);
                var len = 0;
                if (response.items != null) {
                    len = response.items.length;
                }

                if (len>0) {

                    first_option_line = '<option value ="">Choose Item... </option>'
                    $("#farmer_groups").append(first_option_line);
                    for (var i = 0; i<len; i++) {
                            var id = response.items[i].id;
                            var name = response.items[i].name;

                            var option = "<option value='"+id+"'>"+name+"</option>"; 

                            $("#farmer_groups").append(option);
                    }
                }
            }
        })

        $.ajax({
            url:'/village-agents/agents-by-organisation/'+id,
            type:'get',
            dataType:'json',
            success:function (response) {
                $("#agents").empty();
                console.log(response);
                var len = 0;
                if (response.items != null) {
                    len = response.items.length;
                }

                if (len>0) {

                    first_option_line = '<option value ="">Choose Item... </option>'
                    $("#agents").append(first_option_line);
                    for (var i = 0; i<len; i++) {
                            var id = response.items[i].id;
                            var name = response.items[i].name;

                            var option = "<option value='"+id+"'>"+name+"</option>"; 

                            $("#agents").append(option);
                    }
                }
            }
        })
    });

});

</script>
@endsection

