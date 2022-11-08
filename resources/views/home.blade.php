@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-3">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Users</p>
                        <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ $dashboard->system_users }}">{{ $dashboard->system_users }}</span></h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                {{-- <i class="feather feather-activity text-info"></i> --}}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-md-3">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Roles</p>
                        <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ $dashboard->roles }}">{{ $dashboard->roles }}</span></h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                {{-- <i class="feather feather-activity text-info"></i> --}}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-md-3">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Modules</p>
                        <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ $dashboard->system_modules }}">{{ $dashboard->system_modules }}</span></h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                {{-- <i class="feather feather-activity text-info"></i> --}}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-md-3">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Countries</p>
                        <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ $dashboard->countries }}">{{ $dashboard->countries }}</span></h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                {{-- <i class="feather feather-activity text-info"></i> --}}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Locations</p>
                        <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ $dashboard->locations }}">{{ $dashboard->locations }}</span></h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                {{-- <i class="feather feather-activity text-info"></i> --}}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-md-3">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Seasons</p>
                        <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ $dashboard->seasons }}">{{ $dashboard->seasons }}</span></h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                {{-- <i class="feather feather-activity text-info"></i> --}}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-md-3">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Enterprises</p>
                        <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ $dashboard->enterprises }}">{{ $dashboard->enterprises }}</span></h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                {{-- <i class="feather feather-activity text-info"></i> --}}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-md-3">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Languages</p>
                        <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ $dashboard->language }}">{{ $dashboard->language }}</span></h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                {{-- <i class="feather feather-activity text-info"></i> --}}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Organisations</p>
                        <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ $dashboard->organisations }}">{{ $dashboard->organisations }}</span></h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                {{-- <i class="feather feather-activity text-info"></i> --}}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-md-3">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Organisation Users</p>
                        <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ $dashboard->organisation_users }}">{{ $dashboard->organisation_users }}</span></h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                {{-- <i class="feather feather-activity text-info"></i> --}}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-md-3">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Microfinances</p>
                        <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="0">0</span></h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                {{-- <i class="feather feather-activity text-info"></i> --}}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-md-3">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Microfinance Users</p>
                        <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="0">0</span></h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                {{-- <i class="feather feather-activity text-info"></i> --}}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">All Farmers</p>
                        <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="0">0</span></h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                {{-- <i class="feather feather-activity text-info"></i> --}}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->
    <div class="col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Farmer Groups</p>
                        <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="0">0</span></h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                {{-- <i class="feather feather-activity text-info"></i> --}}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Individual Farmers</p>
                        <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="0">0</span></h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                {{-- <i class="feather feather-activity text-info"></i> --}}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<div class="row">
    <div class="col-md-3">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">All Village Agents</p>
                        <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="0">0</span></h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                {{-- <i class="feather feather-activity text-info"></i> --}}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-md-3">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Village Agents (Supervisors)</p>
                        <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="0">0</span></h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                {{-- <i class="feather feather-activity text-info"></i> --}}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-md-3">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Village Agents (Supervised)</p>
                        <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="0">0</span></h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                {{-- <i class="feather feather-activity text-info"></i> --}}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-md-3">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Village Agents (Individuals)</p>
                        <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="0">0</span></h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                {{-- <i class="feather feather-activity text-info"></i> --}}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>


@endsection

    <!-- jsvectormap css -->
    {{-- <link href="{{ asset('assets/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" /> --}}

    <!--Swiper slider css-->
    {{-- <link href="{{ asset('assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" /> --}}



    <!-- apexcharts -->
    {{-- <script src="{{ asset('path') }}"></script> --}}

    <!-- Vector map-->
    {{-- <script src="{{ asset('assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jsvectormap/maps/world-merc.js') }}"></script> --}}

    <!--Swiper slider js-->
    {{-- <script src="{{ asset('assets/libs/swiper/swiper-bundle.min.js') }}"></script> --}}

    <!-- Dashboard init -->
    {{-- <script src="{{ asset('assets/js/pages/dashboard-ecommerce.init.js') }}"></script> --}}
