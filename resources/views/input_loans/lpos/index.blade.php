@inject('set', 'App\Http\Controllers\InputLoan\PurchaseOrderController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Input requests',
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
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">List of LPOs</a></li>
                    {{-- <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">Add new LPOs</a></li> --}}
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                            
                <div class="table-responsive">
                    <table id="dTable" class="table table-striped table-bordered" >
                        <thead>
                            <tr>
                                <th>LPO No</th>
                                <th>Distributor</th>
                                <th>Total</th>
                                <th>Delivery Status</th>
                                <th>Actions</th>
                                {{-- <th>Actions</th> --}}
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

