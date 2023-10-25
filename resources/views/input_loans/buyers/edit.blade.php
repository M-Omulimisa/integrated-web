@inject('set', 'App\Http\Controllers\InputLoan\BuyerController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Buyers',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of buyers</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">Add new buyer</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.show',[$buyer->id]) }}">Buyer details</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Edit buyer</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                        {!! Form::model($buyer, ['files'=>true, 'class'=>'form-horizontal', 'method' => 'PATCH','route' => ['input-loans.buyers.update', $buyer->id]]) !!}

                            <div class="form-group mb-3">
                                {!! Form::label('name', 'Name (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                <div class="col-sm-5">
                                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'id' => 'name']) !!}
                                </div> 
                            </div>

                                <div class="form-group mb-3">
                                {!! Form::label('address', 'Address (optional)', ['class' => 'col-sm-4 col-form-label']) !!}
                            <div class="col-sm-5">
                                {!! Form::textarea('address', old('address'), ['class' => 'form-control', 'rows' => 2, 'required' => '']) !!}
                            </div> 
                                </div>

                                <div class="form-group mb-3">
                                {!! Form::label('services', 'Services (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                            <div class="col-sm-5">
                                {!! Form::textarea('services', old('services'), ['class' => 'form-control', 'placeholder' => '', 'rows' => 2, 'required' => '']) !!}
                            </div> 
                                </div>

                                <div class="form-group mb-3">
                                {!! Form::label('services', 'Logo (optional)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        <input class="form-control" id="formSizeDefault" type="file">
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

