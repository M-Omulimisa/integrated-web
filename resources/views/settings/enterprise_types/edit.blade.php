@inject('set', 'App\Http\Controllers\Settings\EnterpriseTypeController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Enterprise types',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of enterprise types</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">Add new enterprise type</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.show',[$enterprise type->id]) }}">Country details</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Edit enterprise type</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                        {!! Form::model($enterprise type, ['class'=>'form-horizontal', 'method' => 'PATCH','route' => ['settings.enterprise-types.update', $enterprise type->id]]) !!}

                            <div class="form-group mb-3">
                                {!! Form::label('enterprise_id', 'Enterprise (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                <div class="col-sm-5">
                                    {!! Form::select('enterprise_id', $enterprises, old('enterprise_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!} 
                                </div> 
                            </div> 

                            <div class="form-group mb-3">
                                {!! Form::label('enterprise_variety_id', 'Variety (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                <div class="col-sm-5">
                                    {!! Form::select('enterprise_variety_id', $enterprise_varieties, old('enterprise_variety_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!} 
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

