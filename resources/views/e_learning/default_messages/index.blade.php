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
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">All Default Messages</a></li>
                    @endcan
                    @can('add_el_instructions')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.messages.create') }}">Add Default Messages</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">
                        
                        <div class="table-responsive">
                            <table id="dTable" class="table table-striped table-bordered" >
                                <thead>
                                    <tr>
                                        <th>Message</th>
                                        <th>Numbering</th>
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
   
@endsection

@section('styles')   

    <style type="text/css">
        .table td:nth-child(1) {
            max-width: 360px !important;
            white-space: normal !important;
        }
    </style> 

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
                    url: '{!! route('e-learning.messages.list') !!}',
                    data: function (d) { }
                },
                columns: [
                    { data: '_message', name: '_message' },
                    { data: 'numbering', name: 'numbering' },
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


