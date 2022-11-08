@inject('set', 'App\Http\Controllers\Insurance\InsurancePeriodController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Window',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of windows</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new window</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                            {!! Form::open(['method' => 'POST', 'route' => ['insurance.insurance-periods.store']]) !!}

                            <div class="form-group mb-3">
                                {!! Form::label('season_id', 'Season (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                               {!! Form::select('season_id', $seasons, old('season_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('opening_date', 'Opening Date (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('opening_date', old('opening_date'), ['class' => 'form-control','required' => '', 'data-provider' => 'flatpickr', 'data-date-format' => 'Y-m-d']) !!}               
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('closing_date', 'Closing date (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('closing_date', old('closing_date'), ['class' => 'form-control','required' => '', 'data-provider' => 'flatpickr', 'data-date-format' => 'Y-m-d']) !!}               
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

