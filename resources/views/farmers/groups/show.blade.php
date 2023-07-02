@inject('set', 'App\Http\Controllers\Farmers\FarmerGroupController')
@extends('layouts.app')

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => $set->_page_title,
    'menu_group'    => $set->_menu_group,
    'menu_item'     => 'Groups',
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
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.index') }}">List of farmer groups</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.create') }}">Add new farmer group</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Farmer group details</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route($set->_route.'.edit',[$group->id]) }}">Edit farmer group</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('farmers/groups/add-farmer/'.$group->id) }}">Add farmer</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active">
                        <!-- content starts here -->

                         {{--
        'registration_year',
        'registration_certificate',
        'status', --}}

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card bs-card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">Farmer Group details</h5>
                                            <div class="row">
                                                <div class="col-6 col-md-4">
                                                    <span class="text-muted">Group name: </span> {{ $group->name }}<br>
                                                    <span class="text-muted">Group code: </span> {{ $group->code }}<br>
                                                    <span class="text-muted">Establishment year: </span> {{ $group->establishment_year }}<br><br>
                                                    <span class="text-muted">Organisation: </span> {{ $group->organisation_id }}<br>
                                                    <span class="text-muted">Managed by: </span> {{ $group->managed_by->name ?? '' }}<br>
                                                </div>
                                                <!--end col-->
                                                <div class="col-6 col-md-4">
                                                    <span class="text-muted">Group leader: </span> {{ $group->group_leader }}<br>
                                                    <span class="text-muted">Group leader contact: </span> {{ $group->group_leader_contact }}<br>
                                                    <span class="text-muted">Meeting venue: </span> {{ $group->meeting_venue }}<br>
                                                    <span class="text-muted">Meeting days: </span> {{ $group->meeting_days }}<br>
                                                    <span class="text-muted">Meeting time: </span> {{ $group->meeting_time }}<br>
                                                    <span class="text-muted">Meeting frequency: </span> {{ $group->meeting_frequency }}<br>
                                                    <span class="text-muted">Last cycle savings: </span> {{ $group->last_cycle_savings }}<br>
                                                </div>
                                                <!--end col-->
                                                <div class="col-6 col-md-4">
                                                    <span class="text-muted">Location: </span> {{ $group->country_id }}, {{ $group->location_id }}<br>
                                                    <span class="text-muted">Address: </span> {{ $group->address }}<br>
                                                    <span class="text-muted">Long, Lat: </span> {{ $group->longitude }}, {{ $group->latitude }}<br><br>
                                                    <span class="text-muted">Added by: </span> {{ $group->added_by_user->name ?? '' }} {{ $group->added_by_agent->name ?? '' }}<br>
                                                    <span class="text-muted">Added at: </span> {{ $group->created_at }}<br>
                                                </div>
                                            </div>
                                            <!--end row-->
                                            
                                        </div>
                                        <!--end card-body-->
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card bs-card">
                                        <div class="card-header align-items-center d-flex">
                                            <h4 class="card-title mb-0  me-2">Farmers</h4>
                                        </div>
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row border border-dashed gx-2 p-2 mb-2">   

                                                        <table id="dTable" class="table table-striped table-bordered" >
                                                            <thead>
                                                                <tr>
                                                                    <th>Bio-Data</th>
                                                                    <th>Profile</th>
                                                                    <th>Activities</th>
                                                                    <th>Grouping</th>
                                                                    <th>Contact</th>
                                                                    <th>Address</th>
                                                                    <th>Registration</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                        </table>

                                                    </div>
                                                    <!--end row-->
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
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

