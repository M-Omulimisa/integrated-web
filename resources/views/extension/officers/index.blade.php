@inject('set', 'App\Http\Controllers\Extension\ExtensionOfficerController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Extension Officers',
    'menu_item_url' => route($set->_route.'.index'),
    'current'       => 'List'
])
<!-- end page title -->

<div class="row">
    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card" id="pages">
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">List of extension officers</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">Add new extension officer</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                            <table id="dTable" class="table table-striped table-bordered" >
                                <thead>
                                    <tr>
                                        <th>Bio-Data</th>
                                        <th>Profile</th>
                                        <th>Languages</th>
                                        <th>Supervision</th>
                                        <th>Contacts</th>
                                        <th>Address</th>
                                        <th>Added by</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>
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

    <style>
        table tr td {
            white-space: nowrap;
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
                    url: '{!! route('extension-officers.officers.list') !!}',
                    data: function (d) { }
                },
                columns: [
                    {data: 'bio', name: 'bio'},
                    {data: 'profile', name: 'profile'},
                    {data: 'languages', name: 'languages'},
                    {data: 'grouping', name: 'grouping'},
                    {data: 'contact', name: 'contact'},
                    {data: 'address', name: 'address'},
                    {data: 'created', name: 'created'},
                    {data: 'action', name: 'action'},
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

