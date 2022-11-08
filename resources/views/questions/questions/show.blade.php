@inject('set', 'App\Http\Controllers\Questions\QuestionController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Farmer Questions',
    'menu_item_url' => route($set->_route.'.index'),
    'current'       => 'Details'
])
<!-- end page title -->

<div class="row">
    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card" id="pages">
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of farmer questions</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">Add new farmer question</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Farmer question details</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card bs-card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">Question details</h5>
                                            <p>{{ $question->body }}</p>
                                            <div class="row">
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-user-3-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Farmer :</p>
                                                            <h6 class="text-truncate mb-0">{{ $question->farmer->first_name.' '.$question->farmer->last_name }} ({{$question->farmer->phone }})</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Sent via :</p>
                                                            <a href="#" class="fw-semibold">{{ $question->sent_via }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">DateTime :</p>
                                                            <a href="#" class="fw-semibold">{{ $question->created_at }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end row-->
                                            
                                        </div>
                                        <!--end card-body-->
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card bs-card">
                                        <div class="card-header align-items-center d-flex">
                                            <h4 class="card-title mb-0  me-2">Images</h4>
                                        </div>
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row border border-dashed gx-2 p-2 mb-2">

                                                        @if (count($question->images) > 0)
                                                            @foreach ($question->images as $image)
                                                                <div class="col-4">
                                                                    <img src="assets/images/small/img-2.jpg" alt="" class="img-fluid rounded">
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            No images
                                                        @endif

                                                    </div>
                                                    <!--end row-->
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card bs-card">
                                        <div class="card-header align-items-center d-flex">
                                            <h4 class="card-title mb-0  me-2">Responses</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content text-muted">
                                                <div class="tab-pane active" id="today" role="tabpanel">
                                                    <div class="profile-timeline">
                                                        <div class="accordion accordion-flush" id="todayExample">

                                                            @if (count($question->responses) > 0)
                                                                @foreach ($question->responses as $response)
                                                                    <div class="accordion-item border-0">
                                                                        <div class="accordion-header" id="{{$response->id}}">
                                                                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseOne" aria-expanded="true">
                                                                                <div class="d-flex">
                                                                                    {{-- <div class="flex-shrink-0">
                                                                                        <img src="assets/images/users/avatar-2.jpg" alt="" class="avatar-xs rounded-circle">
                                                                                    </div> --}}
                                                                                    <div class="flex-shrink-0 avatar-xs">
                                                                                        <div class="avatar-title bg-light text-muted rounded-circle">
                                                                                            <i class="ri-user-2-fill"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="flex-grow-1 ms-3">
                                                                                        <h6 class="fs-14 mb-1">
                                                                                            {{ $response->user_id ?? $response->extension_officer_id }}
                                                                                        </h6>
                                                                                        <small class="text-muted">{{ !is_null($response->user_id) ? 'User' : 'Extersion officer' }} | {{ $response->created_at }}</small>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="{{$response->id}}" data-bs-parent="#accordionExample">
                                                                            <div class="accordion-body ms-2 ps-5">
                                                                                {{ $response->response }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                No responses yet
                                                            @endif

                                                        </div>
                                                        <!--end accordion-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->
                            </div>



                            @can('manage_question_responses')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card bs-card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3">Add response</h5>
                                                <div class="row">
                                                    <div class="col-12 col-md-12">

                                                        {!! Form::open(['method' => 'POST', 'route' => ['questions.responses.store']]) !!}

                                                            <input type="hidden" name="question_id" value="{{$question->id}}">
                                                            <input type="hidden" name="user_id" value="{{$question->id}}">

                                                            <div class="form-group mb-3">
                                                                {!! Form::label('response', 'Response (required)', ['class' => 'col-sm-3 col-form-label']) !!}                
                                                                <div class="col-sm-5">
                                                                    {!! Form::textarea('response', old('response'), ['class' => 'form-control', 'placeholder' => '', 'rows' => 2]) !!}
                                                                    <span class="help-block">...</span>               
                                                                </div>
                                                            </div>

                                                            <div class="form-buttons-w">
                                                            {!! Form::submit('Respond', ['class' => 'btn btn-primary']) !!}
                                                            </div>

                                                        {!! Form::close() !!}
                                                        

                                                    </div>
                                                </div>
                                                <!--end row-->
                                                
                                            </div>
                                            <!--end card-body-->
                                        </div>
                                    </div>
                                </div>
                            @endcan

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

