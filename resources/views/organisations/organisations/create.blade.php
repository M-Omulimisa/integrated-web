@inject('set', 'App\Http\Controllers\Organisations\OrganisationController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Organisations',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of organisations</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new organisation</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                            {!! Form::open(['files'=>true, 'method' => 'POST', 'route' => ['organisations.organisations.store']]) !!}

                                <div class="form-group mb-3">
                                    {!! Form::label('organisation', 'Name (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        {!! Form::text('organisation', old('organisation'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'id' => 'name']) !!}
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
                                {!! Form::label('file', 'Signature (optional)', ['class' => 'col-sm-4 col-form-label']) !!}
                                    <div class="col-sm-5">
                                        <input class="form-control" id="formSizeDefault" type="file" name="file">
                                    </div> 
                                </div>
                                
                                <h4><span>Organisation Administrator</span></h4>

                                    <input type="hidden" name="roles" value="{{ $organisation_admin }}">

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
                                            {!! Form::text('phone', old('phone'), ['class' => 'form-control', 'placeholder' => '775xxxxxx', 'required' => '']) !!}
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

