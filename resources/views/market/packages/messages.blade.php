@inject('set', 'App\Http\Controllers\MarketInformation\MarketPackageController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Packages',
    'menu_item_url' => route($set->_route.'.index'),
    'current'       => 'Messages'
])
<!-- end page title -->

<div class="row">
    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card" id="pages">
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of packages</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">Add new packages</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Package Messages</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                            {!! Form::open(['method' => 'POST', 'route' => ['market.packages.messages.store']]) !!}

                                {{ Form::hidden('package_id', $package->id) }}

                                {!! Form::label('package', 'Package: '.$package->name, ['class' => 'col-sm-12 form-label']) !!}

                                @if (count($package->messages) > 0)
                                    @foreach ($package->messages as $message)
                                        {{ Form::hidden('languages[]', $message->language_id) }}
                                        <div class="form-group mb-3">
                                            {!! Form::label('name', $message->language->name, ['class' => 'col-sm-4 col-form-label']) !!}
                                            <div class="col-sm-6">
                                            {!! Form::textarea('message[]', old('messages') ?? $message->message, ['class' => 'form-control', 'id' => 'textarea-'.$message->menu, 'placeholder' => '', 'rows' => 3]) !!}               
                                            </div>
                                            <div id="the-count-{{ $message->menu }}">
                                                <span id="current-{{ $message->menu }}">
                                                    @if (!is_null(old('messages')))
                                                        {{ strlen(old('messages')) }}
                                                    @else
                                                        {{ strlen($message->message) }}                                                        
                                                    @endif
                                                </span>
                                                <span id="maximum-{{ $message->menu }}">/ 160</span>
                                            </div>
                                        </div>
                                    @endforeach
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

    <script>
        @if (count($package->messages) > 0)
            @foreach ($package->messages as $message)
                
                $('#textarea-'+'{{ $message->menu }}').keyup(function() {

                      var characterCount = $(this).val().length,
                          current = $('#current-'+'{{ $message->menu }}'),
                          maximum = $('#maximum-'+'{{ $message->menu }}'),
                          theCount = $('#the-count-'+'{{ $message->menu }}');
                        
                      current.text(characterCount);
                });

            @endforeach
        @endif  
    </script>

@endsection

