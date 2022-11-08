@inject('set', 'App\Http\Controllers\Weather\WeatherConditionController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Conditions',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of conditions</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new conditions</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            {!! Form::open(['method' => 'POST', 'route' => ['weather-info.conditions.store']]) !!}

                                <div class="form-group mb-3">
                                    {!! Form::label('language_id', 'Language*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">                                        
                                   {!! Form::select('language_id', $languages, old('language_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Language--']) !!}    
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('digit', 'Digit/Code*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::text('digit', old('digit'), ['class' => 'form-control', 'placeholder' => '0', 'required' => '']) !!}               
                                    </div>   
                                </div> 

                                <div class="form-group mb-3">
                                    {!! Form::label('description', 'Description*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::textarea('description', old('description'), ['class' => 'form-control', 'placeholder' => '', 'rows' => 2]) !!}               
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

