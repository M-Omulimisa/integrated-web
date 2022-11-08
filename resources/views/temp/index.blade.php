@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => 'Users',
    'menu_group'    => 'User Management',
    'menu_item'     => 'Users',
    'menu_item_url' => route('user-management.users.index'),
    'current'       => 'List'
])
<!-- end page title -->


<div class="row">
    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card" id="pages">
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active">List of users</a></li>
                    <li class="nav-item"><a class="nav-link">Add new user</a></li>
                    <li class="nav-item"><a class="nav-link">User details</a></li>
                    <li class="nav-item"><a class="nav-link">Edit user</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <p class="mb-0">
                            They all have something to say beyond the words on the page. They can come across as casual or neutral, exotic or graphic. That's why it's important to think about your message, then choose a font that fits. Cosby sweater eu banh mi, qui irure terry richardson ex squid.
                        </p>
                    </div>
                </div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
    <!--end col-->

</div>

{{-- <table class="table table-striped table-condensed" id="dTable"> --}}
<table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Roles</th>
            <th>Status</th>
            <th>Last session</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody></tbody>        
  </table>

@endsection

@section('styles')

    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

@endsection

@section('scripts')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!--datatable js-->
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
    document.addEventListener("DOMContentLoaded", function () {
        new DataTable("#scroll-horizontal", { 
            sDom: "<'row'<'col-sm-3'l><'col-sm-3'i><'col-sm-6'f>r>t<'row'<'col-sm-5'i><'col-sm-7'p>>",
            sScrollX: "100%",
            sScrollXInner: '100%',
            bScrollCollapse: true,
            bProcessing: true,
            bServerSide: true, 
            // aoColumnDefs: [ {
                  // "targets": [0],
                  // "orderable": true,
                  // "searchable": false
                    
                // } ],
            oLanguage: {
                sLengthMenu: "_MENU_ records per page"
            },
            lengthMenu: {{ DT_LENGTH }}, 
            ajax: "{{ route('user-management.users.list') }}",
            aoColumns: [
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'roles', name: 'roles'},
                {data: 'status', name: 'status'},
                {data: 'last_session', name: 'last_session'},
                {data: 'action', name: 'action'},
            ]
        });
    });
</script>

@endsection

