@inject('set', 'App\Http\Controllers\InputLoan\ServiceProviderController')
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of service providers</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add new service providers</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            {!! Form::open(['method' => 'POST', 'route' => [$set->_route.'.store']]) !!}

                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                <div class="form-group mb-3">
                    {!! Form::label('name', 'Name*', ['class' => 'col-sm-4 col-form-label']) !!}                
                    <div class="col-sm-5">
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!} 
                </div>
                </div>

                <div class="form-group mb-3">
                    {!! Form::label('contact_person', 'Contact Person*', ['class' => 'col-sm-4 col-form-label']) !!}                
                    <div class="col-sm-5">
                    {!! Form::text('contact_person', old('contact_person'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!} 
                </div>
                </div>

                <div class="form-group mb-3">
                    {!! Form::label('telephone', 'Telephone(optional)', ['class' => 'col-sm-4 col-form-label']) !!}
                <div class="col-sm-5">
                    {!! Form::text('telephone', old('telephone'), ['class' => 'form-control', 'placeholder' => 'start with 256']) !!}
                </div> 
                    </div> 

                <div class="row">
                    <div class="col-md-4">
                        <label class="custom-control custom-checkbox">
                            <input class="custom-control-input" name="does_livestock" type="checkbox" value="1" {{ old('does_livestock') ? 'checked=""' : '' }}><span class="custom-control-label">Does Livestock?</span>
                        </label> 
                    </div>
                    <div class="col-md-4">
                        <label class="custom-control custom-checkbox">
                            <input class="custom-control-input" name="does_crops" type="checkbox" value="1" {{ old('does_crops') ? 'checked=""' : '' }}><span class="custom-control-label">Does Crops?</span>
                        </label> 
                    </div>
                </div>

                  <div class="row">
                    {!! Form::label('service', 'Choose services*', ['class' => 'col-sm-12 col-form-label']) !!}
                    @if (isset($service_categories) && count($service_categories) > 0)

                        @foreach($service_categories as $category)
                        <div class="col-lg-12 col-md-12">
                            <strong>{{$category->name}}</strong>
                            @foreach ($category->services as $service)
                            <div class="col-lg-12 col-md-12">
                            <label class="custom-control custom-checkbox">
                                <input class="custom-control-input" name="service[]" type="checkbox" value="{{ $service->id }}" {{ old('service') ? 'checked=""' : '' }}><span class="custom-control-label">{{ $service->name }}</span>
                            </label>                     
                            </div><!-- col-3 -->
                            @endforeach
                        </div>
                        @endforeach
                        
                    @endif
                  </div><!-- row -->

                <div class="form-group mb-3">
                    {!! Form::label('subcounty_id', 'Subcounty*', ['class' => 'col-sm-4 col-form-label']) !!}                
                    <div class="col-sm-5">
                   {!! Form::select('subcounty_id', $subcounties ?? [], old('subcounty_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!}    
                    </div>
                </div>

                <div class="form-group mb-3">
                    {!! Form::label('venue', 'GPS*', ['class' => 'col-sm-4 col-form-label']) !!}
                    <div class="row" style="margin:10px 0 0 0px;">
                        <div class="col-sm-5">
                            <div class="row">
                                <div class="col-sm-6">
                                {!! Form::text('longitude', old('longitude'), ['class' => 'form-control', 'placeholder' => 'Longitude']) !!}               
                                </div>
                                <div class="col-sm-6">
                                {!! Form::text('latitude', old('latitude'), ['class' => 'form-control', 'placeholder' => 'Latitude']) !!}               
                                </div> 
                            </div>
                        </div>
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

