@inject('set', 'App\Http\Controllers\MarketInformation\MarketOutputProductController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Commodities',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of commodities</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new commodities</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            {!! Form::open(['method' => 'POST', 'route' => ['market.commodities.store']]) !!}

                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                                <div class="form-group mb-3">
                                    {!! Form::label('farmgain_id', 'Farmgain ID (Optional)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::text('farmgain_id', old('farmgain_id'), ['class' => 'form-control', 'placeholder' => '', 'id' => 'farmgain_id']) !!}
                                    </div> 
                                </div> 

                                <div class="form-group mb-3">
                                    {!! Form::label('name', 'Commodity*', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'e.g Beans', 'required' => '', 'id' => 'name']) !!}
                                    </div> 
                                </div>               

                                <div class="form-group mb-3">
                                    {!! Form::label('enterprise_id', 'Enterprise*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('enterprise_id', $enterprises, old('enterprise_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!}    
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('unit_id', 'Unit *', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('unit_id', $units, old('units'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!}    
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

