@inject('set', 'App\Http\Controllers\Organisations\OrganisationUserController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Organisation Users',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of organisation users</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new organisation user</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                                {!! Form::open(['method' => 'POST', 'route' => ['organisations.users.store']]) !!}

                                    <input type="hidden" name="roles" value="{{ $organisation_user }}">

                                    <div class="form-group mb-3">
                                        {!! Form::label('services', 'Organisation (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                        <div class="col-sm-5">
                                            {!! Form::select('organisation_id', $organisations, old('organisation_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!} 
                                        </div> 
                                    </div> 

                                    <div class="form-group mb-3">
                                        {!! Form::label('services', 'Position (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                        <div class="col-sm-5">
                                            {!! Form::select('position_id', $positions, old('position_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!} 
                                        </div> 
                                    </div> 

                                    <div class="form-group mb-3">
                                        {!! Form::label('name', 'Name (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'id' => 'name']) !!}
                                    </div> 
                                        </div>

                                    <div class="form-group mb-3">
                                        {!! Form::label('email', 'Email (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                        <div class="col-sm-5">
                                        {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '', 'required'=>'']) !!}
                                        @if($errors->has('email'))
                                            <p class="help-block">{{ $errors->first('email') }}</p>
                                        @endif
                                        </div>                   
                                    </div>

                                    <div class="form-group mb-3">
                                        {!! Form::label('services', 'Telephone (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                        <div class="row">
                                            <div class="col-sm-2">
                                                {!! Form::select('dialing_code', $dialing_codes, old('dialing_code'), ['class' => 'form-control select2','required' => '','placeholder'=>'000']) !!} 
                                            </div> 
                                            <div class="col-sm-3">
                                                {!! Form::text('telephone', old('telephone'), ['class' => 'form-control', 'placeholder' => '775xxxxxx', 'required' => '']) !!}
                                            </div>                                         
                                        </div>
                                    </div> 

                                    <div class="form-group mb-3">
                                        {!! Form::label('password', 'Password (auto)', ['class' => 'col-sm-4 col-form-label']) !!}
                                        <div class="col-sm-5">
                                            {!! Form::text('password', generatePassword(0,9,10), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                        </div> 
                                    </div>

                                    <input type="hidden" name="status" value="1">

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

