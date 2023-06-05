@inject('set', 'App\Http\Controllers\IdValidations\PhoneValidationController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Phone Validation',
    'menu_item_url' => route($set->_route.'.index'),
    'current'       => 'New'
])
<!-- end page title -->

<div class="row">
    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card" id="pages">
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">Validation History</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">New Validation</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                            {!! Form::open(['method' => 'POST', 'route' => ['validations.phones.store']]) !!}

                            <div class="form-group mb-3">
                                {!! Form::label('phonenumber', 'Phone Number (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                <div class="col-sm-5">
                                {!! Form::text('phonenumber', old('phonenumber'), ['class' => 'form-control', 'placeholder' => '256XXXXXXXXX', 'required' => '']) !!}               
                                </div>
                            </div>

                            <div class="form-buttons-w">
                            {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
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

