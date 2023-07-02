@inject('set', 'App\Http\Controllers\Settings\EnterpriseVarityController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Enterprise varieties',
    'menu_item_url' => route($set->_route.'.index'),
    'current'       => 'Details'
])
<!-- end page title -->

<div class="row">
    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card" id="pages">
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of enterprise varieties</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">Add new enterprise variety</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Enterprise variety details</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.edit',[$enterprise variety->id]) }}">Edit enterprise variety</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
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

