@inject('set', 'App\Http\Controllers\Settings\CommissionRankingController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Commission rankings',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of commission rankings</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new commission ranking</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                            {!! Form::open(['method' => 'POST', 'route' => ['settings.commission-rankings.store']]) !!}

                            <div class="form-group mb-3">
                                {!! Form::label('country_id', 'Country (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                <div class="col-sm-5">
                                    {!! Form::select('country_id', $countries, old('country_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!} 
                                </div> 
                            </div> 

                            <div class="form-group mb-3">
                                {!! Form::label('name', 'Name (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('order', 'Order (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('order', old('order'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
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

