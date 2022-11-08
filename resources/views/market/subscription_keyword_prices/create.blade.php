@inject('set', 'App\Http\Controllers\MarketInformation\SubscriptionKeywordController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Keyword Pricing (Subscription-Based)',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">Keyword prices</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new keyword prices</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            {!! Form::open(['method' => 'POST', 'route' => [$set->_route.'.store']]) !!}

                                <div class="form-group mb-3">
                                    {!! Form::label('keyword_id', 'Keyword', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                        @php($keywords=[])
                                    {!! Form::select('keyword_id', $keywords, old('keyword_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="col-sm-4 col-form-label">Sms</label>
                                    <div class="col-sm-8"> 
                                         <textarea class="form-control" name="sms" rows="4"></textarea>
                                         <span>Use | i.e pipe to seperate the messages. Each SMS must not exceed 160 characters</span>
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

