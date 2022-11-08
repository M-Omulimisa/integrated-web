@inject('set', 'App\Http\Controllers\Alerts\OutbreakController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Outbreaks',
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
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">List of outbreaks</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">Add new outbreaks</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                            <div class="table-responsive">
                                <table id="dTable" class="table table-striped table-bordered" >
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox"  id="bulkDelete"  /></th>
                                            <th>Image</th>
                                            <th>Affected Enterprise</th>
                                            <th>Description</th>
                                            <th>Location</th>
                                            <th>Reporting</th> 
                                            <th>Status</th>                                    
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
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

