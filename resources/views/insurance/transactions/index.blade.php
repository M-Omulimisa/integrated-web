@inject('set', 'App\Http\Controllers\Insurance\InsuranceTransactionController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Transactions',
    'menu_item_url' => route($set->_route.'.index'),
    'current'       => 'Logs'
])
<!-- end page title -->

<div class="row">
    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card" id="pages">
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Farmer subscription payments</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Agent commission disbursements</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Farmer compensations disbursements</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                            <table id="dTable" class="table table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>DateTime</th>
                                        <th>Payment Account</th>
                                        <th>Reference</th>
                                        <th>MNO</th>
                                        <th>Amount</th>
                                        <th>Payment status</th>
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

@endsection

@section('scripts')

@endsection

