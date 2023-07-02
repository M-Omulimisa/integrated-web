@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@php ($module = $course->code.': '.$course->title)

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

<!-- Row -->
<div class="row">
    <div class="col-12 col-sm-12 col-md-3">
        <div class="card">
            <div class="card-body">
                @include('e_learning.courses.menu')
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-9">
        <div class="card" id="pages">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">                    
                    @can('view_course_contents')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/content/'.$course->id) }}">Content</a></li>
                    @endcan
                    @can('list_el_chapters')
                        @if ($course->lecture_type == "Weekly")
                            <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/weeks/'.$course->id) }}">Weeks</a></li>
                        @endif
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/chapters/'.$course->id) }}">Topics</a></li>
                    @endcan
                    @can('list_el_lectures')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/lectures/'.$course->id) }}">Lectures</a></li>
                    @endcan
                    @can('list_el_lectures')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Chapter Quiz</a></li>
                    @endcan
                    @can('add_el_lectures')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/assignments/'.$course->id.'/create') }}">Add Question</a></li>
                    @endcan
                    @can('list_el_lectures')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/general-assignments/'.$course->id) }}">General Quiz</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">
                        
                        <div class="table-responsive">
                            <table id="dTable" class="table table-striped table-bordered" >
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Chapter</th>
                                        <th>File</th>
                                        <th>Type</th>
                                        <th>Answer</th>
                                        <th>Order of Items</th>
                                        <th>Created By</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Row -->
   
@endsection

@section('styles')    

    <!--datatable css-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.3/css/buttons.dataTables.min.css">

@endsection

@section('scripts')

    <!--datatable js-->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>    
    <script src="https://cdn.datatables.net/buttons/2.3.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function() {
            var oTable = $('#dTable').DataTable({
                "sDom": "<'row'<'col-sm-3'l><'col-sm-3'i><'col-sm-6'f>r>t<'row'<'col-sm-5'i><'col-sm-7'p>>",
                "sScrollX": "100%",
                "sScrollXInner": '100%',
                "bScrollCollapse": true,
                "bProcessing": true,
                "bServerSide": true,
                "aoColumnDefs": [ {
                      // "aTargets": [0],
                      // "orderable": false,
                      // "searchable": false
                      //   "bSortable": false, 
                        
                    } ],
                ajax: {
                    url: '{!! route('e-learning.assignments.list') !!}',
                    data: function (d) {
                            d.course = '{{ $course->id }}';
                         }
                },
                columns: [
                   { data: 'title', name: 'title' },
                    { data: '_chapter', name: '_chapter' },
                    { data: 'audio', name: 'audio' },
                    { data: 'type', name: 'type' },
                    { data: 'answer', name: 'answer' },
                    { data: 'numbering', name: 'numbering' },
                    { data: 'user', name: 'user' },
                    { data: '_status', name: '_status' },
                    { data: 'actions', name: 'actions' }
                ],
                "lengthMenu": {{ DT_LENGTH }},
                "order": [[ 1, 'asc' ]],  
                "aButtons":    [ "csv", "pdf" ]
            });

                var typingTimeout = null;
                  $(".dataTables_filter input").on("keyup", function (event) {          
                    // Clear previous timer
                    clearTimeout(typingTimeout);
                    // Set a new timer
                    var that = this;
                    typingTimeout = setTimeout(function(){
                        oTable.search($(that).val()).draw();
                    }, 200); // Execute the search if user paused for 200 ms
                  });

        });
    </script>

@endsection


