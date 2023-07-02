@inject('set', 'App\Http\Controllers\InputLoan\InputCommissionRateController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Markets',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of input commission rates</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new input commission rates</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            {!! Form::open(['method' => 'POST', 'route' => [$set->_route.'.store']]) !!}

                                <div class="form-group mb-3">
                                    {!! Form::label('country_id', 'Country (required)', ['class' => 'col-sm-3 form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('country_id', $countries, old('country_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                    </div>
                                </div>                                   

                                <div class="form-group mb-3">
                                    {!! Form::label('enterprise_id', 'Enterprise (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('enterprise_id', $enterprises, old('enterprise_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!}    
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('enterprise_variety_id', 'Enterprise Variety (optional)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('enterprise_variety_id', $enterprise_varieties, old('enterprise_variety_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('enterprise_type_id', 'Enterprise Type (optional)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('enterprise_type_id', $enterprise_types, old('enterprise_type_id'), ['class' => 'form-control select2','placeholder'=>'--Select--']) !!}    
                                    </div>
                                </div>

                                @if (count($commission_rankings) > 0)
                                    @foreach ($commission_rankings as $ranking)
                                        <input type="hidden" name="commission_rankings[]" value="{{$ranking->id}}">
                                        <div class="form-group mb-3">
                                            {!! Form::label('rankings', '#'.$ranking->order.' '.$ranking->name.' (required)', ['class' => 'col-sm-4 col-form-label']) !!}
                                            <div class="col-sm-5">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                    {!! Form::text('rates[]', old('rates'), ['class' => 'form-control', 'placeholder' => '0']) !!}               
                                                    </div>
                                                    <div class="col-sm-6">
                                                    {!! Form::select('types[]', $computation_types, old('types'), ['class' => 'form-control select2','required' => '']) !!}                
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-danger">No commission rankings found</p>
                                @endif

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

