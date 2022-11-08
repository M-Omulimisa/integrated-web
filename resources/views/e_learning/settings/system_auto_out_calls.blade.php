@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@php ($module = "Instructions")

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
                    @can('list_el_instructions')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.instructions.index') }}">All Instructions</a></li>
                    @endcan
                    @can('add_el_instructions')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.instructions.create') }}">Add Instructions</a></li>
                    @endcan
                    @can('add_el_instructions')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">System Out Calls</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">

                        {!! Form::model($setting, ['method' => 'PUT', 'files' => true, 'route' => ['e-learning.system-out-calls.update', $setting->id]]) !!}

                        <div class="form-group mb-3">
                            {!! Form::label('calling_time', 'Calling Start Time*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('calling_time', old('calling_time'), ['class' => 'form-control', 'placeholder' => '1', 'required' => '']) !!}          
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('retry_after_in_minutes', 'Retry After (Minutes)*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('retry_after_in_minutes', old('retry_after_in_minutes'), ['class' => 'form-control', 'placeholder' => '1', 'required' => '']) !!}          
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('number_of_retries', 'Number of Retries*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('number_of_retries', old('number_of_retries'), ['class' => 'form-control', 'placeholder' => '1', 'required' => '']) !!}          
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('make_missed_after_in_minutes', 'Mark Call Missed After (Minutes)*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('make_missed_after_in_minutes', old('make_missed_after_in_minutes'), ['class' => 'form-control', 'placeholder' => '1', 'required' => '']) !!}          
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('calls_per_cycle', 'Number of calls per cycle*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('calls_per_cycle', old('calls_per_cycle'), ['class' => 'form-control', 'placeholder' => '1', 'required' => '']) !!}          
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

          var body = $( 'body' );
        });


    </script>
@endsection


