@inject('set', 'App\Http\Controllers\MarketInformation\CommodityPriceController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Commodity prices',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of commodity prices</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new commodity prices</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            {!! Form::open(['method' => 'POST', 'route' => [$set->_route.'.store']]) !!}

                                <div class="form-group mb-3">
                                    <label class="col-sm-4 col-form-label"> Price Type </label>
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" name="type" type="radio" value="Wholesale" checked=""> Wholesale
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                       <input class="form-check-input" name="type" type="radio" value="Retail"> Retail
                                                   </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('output_product_id', 'Commodity*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('output_product_id', $commodities, old('output_product_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!}    
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('market_id', 'Market*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('market_id', $markets, old('market_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!}    
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('price', 'Price*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::text('price', old('price'), ['class' => 'form-control', 'placeholder' => '0', 'required' => '']) !!}               
                                    </div>   
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('currency_id', 'Currency*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('currency_id', $currencies, old('currency_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--FX--']) !!}    
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('price_date', 'Price Date*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::text('price_date', old('price_date'), ['class' => 'form-control', 'placeholder' => 'YYYY-MM-DD', 'data-provider' => 'flatpickr', 'data-date-format' => 'Y-m-d']) !!}                 
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

