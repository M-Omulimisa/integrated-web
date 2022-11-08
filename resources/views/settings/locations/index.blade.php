@inject('set', 'App\Http\Controllers\Settings\LocationController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Locations',
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
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">List of locations</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">Add new location</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                                <table id="dTable" class="table table-striped table-bordered" >
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Admin unit</th>
                                        <th>Parent</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Action</th>
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

    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

@endsection

@section('scripts')

    <!--datatable css-->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

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
                    url: '{!! route('settings.locations.list') !!}',
                    data: function (d) { }
                },
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'admin_unit', name: 'admin_unit'},
                    {data: 'parent', name: 'parent'},
                    {data: 'latitude', name: 'latitude'},
                    {data: 'longitude', name: 'longitude'},
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

