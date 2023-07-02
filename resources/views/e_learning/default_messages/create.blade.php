@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@php ($module = "Default Messages")

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => '...',
    'menu_group'    => '...',
    'menu_item'     => 'E-Learning',
    'menu_item_url' => '#',
    'current'       => '...'
])
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">

        <div class="card" id="pages">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    @can('list_el_instructions')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.instructions.index') }}">Default Instructions</a></li>
                    @endcan
                    <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.system-out-calls.index') }}">System Out Calls</a></li>
                    @can('list_el_instructions')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.messages.index') }}">All Default Messages</a></li>
                    @endcan
                    @can('add_el_instructions')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Add Default Messages</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">

                        {!! Form::open(['method' => 'POST', 'files' => true, 'route' => ['e-learning.messages.store']]) !!}

                        <div class="form-group mb-3">
                            {!! Form::label('default_message', 'Message*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::textarea('default_message', null, ['class' => 'form-control','rows'=>2]) !!}
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('numbering', 'Numbering*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('numbering', old('numbering'), ['class' => 'form-control', 'placeholder' => '1', 'required' => '']) !!}          
                            </div>
                        </div>

                        <div class="form-buttons-w">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        </div>

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>

        </div>
        
    </div>
</div>
   
@endsection

@section('styles')

@endsection

@section('scripts')
    

    <script type="text/javascript">

        jQuery(document).ready(function($){
          //you can now use $ as your jQuery object.
            $('.select2').select2();

          var body = $( 'body' );
        });


    </script>
@endsection


