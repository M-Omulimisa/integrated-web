@inject('set', 'App\Http\Controllers\Questions\QuestionController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Farmer Questions',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of farmer questions</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new farmer question</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                                {!! Form::open(['method' => 'POST', 'route' => ['questions.questions.store'],'files' => true]) !!}

                                    <input type="hidden" name="sender" value="app">

                                    <div class="form-group mb-3">
                                        {!! Form::label('farmer_id', 'Farmer*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                        <div class="col-sm-5">
                                       {!! Form::select('farmer_id', $farmers, old('farmer_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!}    
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        {!! Form::label('keyword_id', 'Keyword*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                        <div class="col-sm-5">
                                       {!! Form::select('keyword_id', $keywords, old('keyword_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!}    
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        {!! Form::label('body', 'Your question*', ['class' => 'col-sm-4 col-form-label']) !!}                
                                        <div class="col-sm-5">
                                        {!! Form::textarea('body', old('body'), ['class' => 'form-control', 'placeholder' => '', 'rows' => 2]) !!}               
                                        </div>
                                    </div>     

                                    <div class="form-group mb-3">
                                       {!! Form::label('file', 'Choose image to upload', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        <input type='file' id="image" name="image[]" accept=".png, .jpg, .jpeg" multiple="">
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

