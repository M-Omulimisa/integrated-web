@inject('set', 'App\Http\Controllers\Settings\KeywordFailureResponseController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Farmers',
    'menu_item_url' => route($set->_route.'.index'),
    'current'       => 'Edit'
])
<!-- end page title -->

<div class="row">
    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card" id="pages">
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of failure responses</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">Add new failure response</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.show',[$response->id]) }}">Failure responses details</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Edit failure response</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            {!! Form::model($response, ['class'=>'form-horizontal', 'method' => 'PATCH','route' => ['settings.failure-responses.update', $response->id]]) !!}

                                <div class="form-group mb-3">
                                    {!! Form::label('country_id', 'Country (optional)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('country_id', $countries, old('country_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('keyword_id', 'Keyword (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('keyword_id', $keywords, old('keyword_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('reason', 'Failure reason (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('reason', $reasons, old('reason'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('response', 'Response (required)', ['class' => 'col-sm-3 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                        {!! Form::textarea('response', old('response'), ['class' => 'form-control', 'placeholder' => '', 'rows' => 2]) !!}
                                        <span class="help-block">NOTE: Use <strong>{{ '<firstname>' }}</strong>, <strong>{{ '<lastname>' }}</strong>, <strong>{{ '<shortcode>' }}</strong><br/>They will be auto-replaced with the correct content.<br/>Hello {{ '<firstname>' }} your sms...., send sms to {{ '<shortcode>' }}</span>              
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

