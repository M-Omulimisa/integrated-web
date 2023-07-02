@inject('set', 'App\Http\Controllers\Trainings\TrainingResouceController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Resources',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of resources</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new resource</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            {!! Form::open(['files' => true, 'method' => 'POST', 'route' => ['trainings.resources.store']]) !!} 

                                <div class="form-group mb-3">
                                    {!! Form::label('languages', 'Languages (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::select('languages[]', $languages, old('languages'), ['class' => 'form-control js-example-basic-multiple select2-hidden-accessible', 'multiple'=>'']) !!} 
                                    </div> 
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('enterprises', 'Enterprises (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::select('enterprises[]', $enterprises, old('enterprises'), ['class' => 'form-control js-example-basic-multiple select2-hidden-accessible', 'multiple'=>'']) !!} 
                                    </div> 
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('heading', 'Heading (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::text('heading', old('heading'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                {!! Form::label('thumbnail', 'Image Thumbnail (optional)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        <input class="form-control" id="formSizeDefault" type="file" name="image">
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

