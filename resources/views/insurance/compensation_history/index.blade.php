@inject('set', 'App\Http\Controllers\Insurance\InsuranceCompensationHistoryController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Farmers',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route('insurance.farmer-compensations.index') }}">Farmer compensation summary</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Farmer compensation list</a></li>
                    {{-- <li class="nav-item"><a class="nav-link" href="{{ route('insurance.farmer-compensations.create') }}">Compensate farmer</a></li> --}}
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                            <table id="dTable" class="table table-striped table-bordered" >
                                <thead>
                                    <tr>
                                        <th>DateTime</th>
                                        <th>Farmer Details</th>
                                        <th>Season</th>
                                        <th>Items</th>
                                        <th>Location</th>
                                        <th>Sum Insured</th>
                                        <th>%Loss</th>
                                        <th>Compensation Amount</th>
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

