@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@php ($module = "Students")

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => '...',
    'menu_group'    => '...',
    'menu_item'     => 'E-Learning',
    'menu_item_url' => '#',
    'current'       => '...'
])
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">

        <div class="card" id="pages">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    @can('list_el_students')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.students.index') }}">All Students</a></li>
                    @endcan
                    @can('add_el_students')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.students.create') }}">Add Students</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.students.upload') }}">Upload Students</a></li>
                    @endcan
                    @can('view_el_students')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.students.show', $data->id) }}">Student Profile</a></li>
                    @endcan
                    @can('edit_el_students')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Edit Student Profile</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">

                        {!! Form::model($data, ['method' => 'PUT', 'route' => ['e-learning.students.update', $data->id]]) !!}

                        <div class="form-group mb-3">
                            {!! Form::label('full_name', 'Full Name*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('full_name', old('full_name'), ['class' => 'form-control', 'placeholder' => '']) !!}               
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('email', 'Email (Optional)', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => '']) !!}               
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('gender', 'Gender*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::select('gender', [null => 'Select']+$gender, old('gender'), ['class' => 'form-control select2']) !!}
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('age_group', 'Age Group*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::select('age_group', [null => 'Select']+$age_group, old('age_group'), ['class' => 'form-control select2']) !!}
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('affiliation', 'Affiliation*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::select('affiliation', [null => 'Select']+$affiliation, old('affiliation'), ['class' => 'form-control select2']) !!}
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('qualification', 'Qualification*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::select('qualification', [null => 'Select']+$qualification, old('qualification'), ['class' => 'form-control select2']) !!}
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('phone_number', 'Phone number*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('phone_number', old('phone_number'), ['class' => 'form-control', 'placeholder' => '2567...']) !!}               
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('organisation_id', 'Organisation (Optional)', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::select('organisation_id', [null => 'Select']+$organisations, old('organisation_id'), ['class' => 'form-control select2']) !!}
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('country', 'Country*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::select('country', [null => 'Select']+$countries, old('country'), ['class' => 'form-control select2']) !!}
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('district_id', 'District*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::select('district_id', [null => 'Select']+$districts, old('district_id'), ['class' => 'form-control select2', 'id' => 'district']) !!}
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('subcounty_id', 'Subcounty (Optional)', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::select('subcounty_id', [null => 'Select']+$subcounties, old('subcounty_id'), ['class' => 'form-control select2', 'id' => 'subcounty']) !!}
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('parish_id', 'Parish (Optional)', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::select('parish_id', [null => 'Select']+$parishes, old('parish_id'), ['class' => 'form-control select2', 'id' => 'parish']) !!}
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('village', 'Village (Optional)', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('village', old('village'), ['class' => 'form-control', 'placeholder' => '']) !!}               
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('business', 'Business (Optional)', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('business', old('business'), ['class' => 'form-control', 'placeholder' => '']) !!}               
                            </div>
                        </div>   

                        <div class="form-group mb-3">
                            <div class="col-sm-12">
                                <input type="checkbox" name="email_notifications" {{ $data->email_notifications ? 'checked="checked"' : '' }}>
                                <label>Recieve Email notifications</label>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="col-sm-12">
                                <input type="checkbox" name="sms_notifications" {{ $data->sms_notifications ? 'checked="checked"' : '' }}>
                                <label>Recieve SMS alerts</label>
                            </div>
                        </div>                             

                        <div class="form-buttons-w">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        </div>

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>

        </div>
        
    </div>
</div>
   
@endsection

@section('styles')

@endsection

@section('scripts')
    

    <script type="text/javascript">

        jQuery(document).ready(function($){
          //you can now use $ as your jQuery object.
            $('.select2').select2();

              $('#district').change(function(){
                $.get("{{ url('get-subcounties-by-district')}}", 
                    { district_id: $(this).val() }, 
                    function(data) {
                        var model = $('#subcounty');
                        model.empty();
                         model.append("<option value='' selected=''>Select Subcounty</option>");
                        $.each(data, function(index, element) {
                            model.append("<option value='"+ element.id +"'>" + element.name + "</option>");
                        });
                    });
                });

              $('#subcounty').change(function(){
                $.get("{{ url('get-parishes-by-subcounty')}}", 
                    { subcounty_id: $(this).val() }, 
                    function(data) {
                        var model = $('#parish');
                        model.empty();
                         model.append("<option value='' selected=''>Select Parish</option>");
                        $.each(data, function(index, element) {
                            model.append("<option value='"+ element.id +"'>" + element.name + "</option>");
                        });
                    });
                });

          var body = $( 'body' );
        });


    </script>
@endsection


