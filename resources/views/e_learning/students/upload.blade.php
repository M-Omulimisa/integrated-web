@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@php ($module = "Students")

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
                    @can('list_el_students')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.students.index') }}">All Students</a></li>
                    @endcan
                    @can('add_el_students')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.students.create') }}">Add Students</a></li>
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Upload Students</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">

                        {!! Form::open(['method' => 'POST', 'route' => ['e-learning.students.upload.store'], 'files' => true]) !!}

                        <input type="hidden" name="added_by" value="{{ auth()->user()->id }}">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">Required file type: csv</div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('file', 'Choose File*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                           <input class="" type="file" id="file" name="file"/>  
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

              $('#district').change(function(){
                $.get("{{ url('get-subcounties-by-district')}}", 
                    { district_id: $(this).val() }, 
                    function(data) {
                        var model = $('#subcounty');
                        model.empty();
                         model.append("<option value='' selected=''>Select Subcounty</option>");
                        $.each(data, function(index, element) {
                            model.append("<option value='"+ element.id +"'>" + element.name + "</option>");
                        });
                    });
                });

              $('#subcounty').change(function(){
                $.get("{{ url('get-parishes-by-subcounty')}}", 
                    { subcounty_id: $(this).val() }, 
                    function(data) {
                        var model = $('#parish');
                        model.empty();
                         model.append("<option value='' selected=''>Select Parish</option>");
                        $.each(data, function(index, element) {
                            model.append("<option value='"+ element.id +"'>" + element.name + "</option>");
                        });
                    });
                });

          var body = $( 'body' );
        });


    </script>
@endsection


