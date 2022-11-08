@inject('set', 'App\Http\Controllers\Insurance\InsuranceSubscriptionController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Subscriptions',
    'menu_item_url' => route($set->_route.'.index'),
    'current'       => 'All'
])
<!-- end page title -->

<div class="row">
    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card" id="pages">
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">List of subscriptions</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create-crops') }}">Subscribe farmer (Crop)</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create-livestock') }}">Subscribe farmer (Livestock)</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                            <table id="dTable" class="table table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>Farmer details</th>
                                        <th>Location</th>
                                        <th>Season</th>
                                        <th>Item details</th>
                                        <th>Sum/Item</th>
                                        <th>Sum Insured</th>
                                        <th>Gov't Subsidy</th>
                                        <th>Basic Premium</th>
                                        <th>IRA Levy</th>
                                        {{-- <th>V.A.T</th> --}}
                                        <th>Net Premium</th>
                                        <th>Commission</th>
                                        <th>Payment Status</th>
                                        {{-- <th>User</th> --}}
                                        {{-- <th>Agent Code</th> --}}
                                        <th>Done By</th>
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

