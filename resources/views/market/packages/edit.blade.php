@inject('set', 'App\Http\Controllers\MarketInformation\MarketPackageController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Packages',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of packages</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">Add new package</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.show',[$id]) }}">Package details</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Edit package</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            {!! Form::model($package, ['class'=>'form-horizontal', 'method' => 'PATCH','route' => ['market.packages.update', $package->id]]) !!}

                                <div class="form-group mb-3">
                                    {!! Form::label('country_id', 'Country (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                   {!! Form::select('country_id', $countries, old('country_id'), ['class' => 'form-control select2','required' => '','placeholder'=>'--Select--']) !!}    
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('name', 'Name (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    {!! Form::label('name', 'Menu Display (required)', ['class' => 'col-sm-4 col-form-label']) !!}                
                                    <div class="col-sm-5">
                                    {!! Form::text('menu', old('menu'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                                    </div>
                                </div>  

                              <div class="row mb-3">
                                {!! Form::label('activity', 'Choose Enterprise (required)', ['class' => 'col-sm-12 form-label']) !!}
                                @if (isset($enterprises) && count($enterprises) > 0)
                                    @foreach ($enterprises as $enterprise)
                                    <div class="col-lg-12 col-md-12">
                                      <label class="form-check mb-2">
                                        <input class="form-check-input" name="enterprises[]" type="checkbox" value="{{ $enterprise->id }}" {{ $package->getPackageEnterpriseDetail($enterprise->id, 'enterprise_id') == $enterprise->id ? 'checked=""' : '' }}><span class="custom-control-label">{{ $enterprise->name }}</span>
                                      </label>                   
                                    </div><!-- col-3 -->
                                    @endforeach
                                @endif
                              </div><!-- row -->                              

                              <div class="row mb-3">
                                {!! Form::label('region', 'Choose Region (required)', ['class' => 'col-sm-12 form-label']) !!}
                                @if (isset($regions) && count($regions) > 0)
                                    @foreach ($regions as $region)
                                    <div class="col-lg-12 col-md-12">
                                      <label class="form-check mb-2">
                                        <input class="form-check-input" name="regions[]" type="checkbox" value="{{ $region->id }}" {{ $package->getPackageRegionDetail($region->id, 'region_id') == $region->id ? 'checked=""' : '' }}><span class="custom-control-label">{{ $region->name }}</span>
                                      </label>                   
                                    </div><!-- col-3 -->
                                    @endforeach
                                @endif
                              </div><!-- row -->

                              {!! Form::label('languages', 'Languages (required)', ['class' => 'col-sm-12 form-label']) !!}

                              @if (count($languages) > 0)
                                  @foreach ($languages as $language)
                                    <div class="row mb-3">
                                        <div class="col-md-1">
                                            {{ $language->name }}
                                            {{ Form::hidden('languages[]', $language->id) }}
                                        </div>
                                        <div class="col-md-1">
                                            {!! Form::text('language_menu[]', old('language_menus') ?? $package->getPackageLanguageDetail($language->id, 'menu'), ['class' => 'form-control', 'placeholder' => '#Menu Display']) !!}
                                        </div>
                                        {{ Form::hidden('message[]', $package->getPackageLanguageDetail($language->id, 'message')) }}
                                    </div>         
                                  @endforeach
                              @endif

                              {!! Form::label('pricing', 'Pricing (required)', ['class' => 'col-sm-12 form-label']) !!}

                              @if (count($frequencies) > 0)
                                  @foreach ($frequencies as $frequency)
                                    <div class="row mb-3">
                                        <div class="col-md-1">
                                            {{ $frequency }}
                                            {{ Form::hidden('frequency[]', $frequency) }}
                                        </div>
                                        <div class="col-md-1">
                                            {!! Form::text('frequency_menus[]', old('frequency_menus') ?? $package->getPackagePricingDetail($frequency, 'menu'), ['class' => 'form-control', 'placeholder' => 'Menu']) !!}                                         
                                        </div>
                                        <div class="col-md-1">
                                            {!! Form::text('num_message[]', old('num_messages') ?? $package->getPackagePricingDetail($frequency, 'messages'), ['class' => 'form-control', 'placeholder' => '#SMS']) !!}                                         
                                        </div>
                                        <div class="col-md-1">{!! Form::text('cost[]', old('costs') ?? $package->getPackagePricingDetail($frequency, 'cost'), ['class' => 'form-control', 'placeholder' => 'Cost']) !!}                                        
                                        </div>
                                    </div>         
                                  @endforeach
                              @endif

                                <div class="form-group mb-3">
                                    {!! Form::label('name', 'Display on Menu (required)', ['class' => 'col-sm-4 col-form-label']) !!}             
                                    <div class="col-sm-5">
                                        {!! Form::select('status', [0=>'Off', 1 => 'On'], old('status'), array('class' => 'chzn_a form-control')) !!}           
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

