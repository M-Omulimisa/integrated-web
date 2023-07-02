@inject('set', 'App\Http\Controllers\Settings\KeywordController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Keyword',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of keywords</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new keyword</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                            {!! Form::open(['method' => 'POST', 'route' => ['settings.keywords.store']]) !!}

                            <div class="form-group mb-3">
                                {!! Form::label('language_id', 'Language (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                               {!! Form::select('language_id', $languages, old('language_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('category', 'Category (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                               {!! Form::select('category', $categories, old('category'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('name', 'Name (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('organisation_id', 'Organisation (optional)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                               {!! Form::select('organisation_id', $organisations, old('organisation_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                {!! Form::label('shortcode', 'shortcode (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('shortcode', old('shortcode'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
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

