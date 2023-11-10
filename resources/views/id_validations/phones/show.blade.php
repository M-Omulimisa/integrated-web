@inject('set', 'App\Http\Controllers\IdValidations\PhoneValidationController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Phone Validation',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">Validation History</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">New Validation</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Validation details</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->
                        <table class="nin">
                            <tr>
                                <th>Phone Number</th>
                                <td class="width-60">{{ $phone->phonenumber }}</td>
                            </tr>
                            <tr>
                                <th>Verified by</th>
                                <td>{{ $phone->mno_authority }}</td>
                            </tr>
                            <tr>
                                <th>First Name</th>
                                <td>{{ $phone->phone_firstname }}</td>
                            </tr>
                            <tr>
                                <th>Middle Name</th>
                                <td>{{ $phone->phone_middlename }}</td>
                            </tr>
                            <tr>
                                <th>Surname</th>
                                <td>{{ $phone->phone_surname }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{{ $phone->phone_status }}</td>
                            </tr>
                            <tr>
                                <th>Timestamp</th>
                                <td>{{ $phone->created_at }}</td>
                            </tr>
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

