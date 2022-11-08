@inject('set', 'App\Http\Controllers\MarketInformation\MarketSubscriptionController')
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">Add new subscriptions</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Upload subscriptions</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                            
                            {!! Form::open(['method' => 'POST', 'files' => true, 'route' => ['market.subscriptions.upload']]) !!}

                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-info">Required file type: csv<br/>Organization field ONLY applies to subscribers under an organisation</div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('organisation_id', 'Organization (Optional)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                        @php($organisations=[])
                                   {!! Form::select('organisation_id', $organisations, old('organisation_id'), array('class' => 'form-control select2', 'placeholder' => 'Select Organisation')) !!} 
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('output_product_id', 'Choose File*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                   <input class="" type="file" id="file" name="subscriptions_file"/>  
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

