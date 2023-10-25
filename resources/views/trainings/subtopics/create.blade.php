@inject('set', 'App\Http\Controllers\Trainings\ResourceSubTopicController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Subtopics',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of subtopics &amp; activities</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new subtopic / activity</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                            {!! Form::open(['files'=>true, 'method' => 'POST', 'route' => ['trainings.sub-topics.store']]) !!}

                                <div class="form-group mb-3">
                                    {!! Form::label('topic_id', 'Topic (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('topic_id', $topics, old('country_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="col-sm-4 col-form-label"> Type </label>
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" name="type" type="radio" value="subtopic" checked=""> Subtopic
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                       <input class="form-check-input" name="type" type="radio" value="activity"> Activity
                                                   </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('title', 'Title (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'id' => 'title']) !!}
                                    </div> 
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('details', 'Details (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::textarea('details', old('details'), ['class' => 'form-control', 'rows' => 3, 'required' => '']) !!}
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

