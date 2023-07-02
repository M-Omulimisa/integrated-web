@inject('set', 'App\Http\Controllers\Organisations\OrganisationController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Organisations',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of organisations</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new organisation</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                            {!! Form::open(['files'=>true, 'method' => 'POST', 'id' => 'create-organisation-form' , 'route' => ['organisations.organisations.store']]) !!}

                                <div class="form-group mb-3">
                                    {!! Form::label('organisation', 'Name (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::text('organisation', old('organisation'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'id' => 'name']) !!}
                                    </div> 
                                </div>

                                <div class="form-group mb-3">
                                {!! Form::label('country_id', 'Countries of operation (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">

                                    <select id="countries" name="country_id[]" multiple required>
                                        <option></option>
                                        @foreach($countries as $country)
                                        <option value="{{$country->id}}">{{$country->name}}</option>
                                        @endforeach
                                    </select>   
                                    
                                </div>
                            </div>

                                    <div class="form-group mb-3">
                                    {!! Form::label('address', 'Address (optional)', ['class' => 'col-sm-4 col-form-label']) !!}
                                <div class="col-sm-5">
                                    {!! Form::textarea('address', old('address'), ['class' => 'form-control', 'rows' => 2, 'required' => '']) !!}
                                </div> 
                                    </div>

                                    <div class="form-group mb-3">
                                    {!! Form::label('services', 'Services (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                <div class="col-sm-5">
                                    {!! Form::textarea('services', old('services'), ['class' => 'form-control', 'placeholder' => '', 'rows' => 2, 'required' => '']) !!}
                                </div> 
                                    </div>

                                <div class="form-group mb-3">
                                {!! Form::label('file', 'Signature (optional)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        <input class="form-control" id="formSizeDefault" type="file" name="file">
                                    </div> 
                                </div>
                                
                                <h4><span>Organisation Administrator</span></h4>

                    

                                <div class="form-group mb-3">
                                    {!! Form::label('name', 'Name (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                <div class="col-sm-5">
                                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'id' => 'name']) !!}
                                </div> 
                                    </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('email', 'Email (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '', 'required'=>'']) !!}
                                    @if($errors->has('email'))
                                        <p class="help-block">{{ $errors->first('email') }}</p>
                                    @endif
                                    </div>                   
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('services', 'Telephone (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="row">
                                        <div class="col-sm-2">
                                            {!! Form::select('dialing_code', $dialing_codes, old('dialing_code'), ['class' => 'form-control select2','required' => '','placeholder'=>'000']) !!} 
                                        </div> 
                                        <div class="col-sm-3">
                                            {!! Form::text('phone', old('phone'), ['class' => 'form-control', 'placeholder' => '775xxxxxx', 'required' => '']) !!}
                                        </div>                                         
                                    </div>
                                </div> 

                                <div class="form-group mb-3">
                                    {!! Form::label('password', 'Password (auto)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::text('password', generatePassword(0,9,10), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    </div> 
                                </div>

                                <input type="hidden" name="status" value="1">

                                <div class="form-buttons-w">
                                    {!! Form::submit('Save',  [ 'id' => 'Create-Organisation', 'class' => 'btn btn-primary button-prevent-multiple-submits']) !!}
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

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

$(document).ready(function() {

///////////////////////////AJAX CSRF SET UP //////////////////////////////////////////////
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

//////////////////// SELECT2 INITIALISATION ///////////////////////////////////////////////

    $('#countries').select2({
        
        'placeholder': 'select a country'
    });


    ///////////////// JQUERY FORM VALIDATION AND SUBMIT /////////////////////
    $("#create-organisation-form").validate({
        ignore: null,   
        rules: {
            
        },
        messages: {

        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        // Called when the element is invalid:
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
  
        // Called when the element is valid:
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },

        submitHandler: function(form) {
              
            $('.button-prevent-multiple-submits').attr('disabled', true); // Disable button on clicking submit
                var formData = new FormData(form);
         
                $.ajax({
                url: "{{ route('organisations.organisations.store') }}",
                type: 'post',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend:function(){
                $('#Create-Organisation').text('Processing...');
                },
                success: function(response) {

                    Swal.fire({
                        title: 'Success',
                        text: 'Transfer completed successfully',
                        icon: 'success',
                        toast:'true',
                        showConfirmButton:false,
                        position:'top-end',
                        timer:2000
                        
                    }).then(function() {
                        window.location = "/view-money-transfers";
                    });
                
                    $('.button-prevent-multiple-submits').attr('disabled', false);
                    $('#Create-Organisation').text('Save');
                },
                error: function(response){
                    
                    console.log(response);
    
                    if(response.status == 422){
                        var firstKey = Object.keys(response.responseJSON.errors)[0];
                        Swal.fire({
                        title: 'Error',
                        text: response.responseJSON.errors[firstKey][0],
                        icon: 'error',
                        toast:'true',
                        showConfirmButton:false,
                        position:'top-end',
                        timer:10000
                        
                        });
                        $('.button-prevent-multiple-submits').attr('disabled', false);
                        $('#Create-Organisation').text('Save');
                    }
                    else if(response.status == 404){

                    }
                    else if(response.status == 500){

                        Swal.fire({
                            title: 'Error',
                            text: 'An Unexpected error occured, Please contact support',
                            icon: 'error',
                            toast:'true',
                            showConfirmButton:false,
                            position:'top-end',
                            timer:5000
                            
                        });

                        $('.button-prevent-multiple-submits').attr('disabled', false);
                        $('#Create-Organisation').text('Save');
                    }
                    else{

                        Swal.fire({
                        title: 'Error',
                        text: 'Unexpected error. Please contact support',
                        icon: 'error',
                        toast:'true',
                        showConfirmButton:false,
                        position:'top-end',
                        timer:10000
                        
                        });
                    }
                    $('.button-prevent-multiple-submits').attr('disabled', false);
                    $('#Create-Organisation').text('Save');
                }
            });
        }
    });

});

</script>
@endsection

