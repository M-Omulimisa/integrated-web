<?php
use App\Models\Utils;
?>
<style>
    .my-table th {
        border: 2px solid black !important;
    }

    .my-table td,
    .my-table tr {
        border: 2px solid rgb(184, 203, 204) !important;
    }
</style>
<div class="container bg-white p-1 p-md-5">
    <div class="d-md-flex justify-content-between">
        <div>
            <h2 class="m-0 p-0 text-dark h3 text-uppercase"><b>Farmer's profile</b></h2>
        </div>
        <div class="mt-3 mt-md-0">
            @isset($_SERVER['HTTP_REFERER'])
                <a href="{{ $_SERVER['HTTP_REFERER'] }}" class="btn btn-secondary btn-sm"><i class="fa fa-chevron-left"></i>
                    BACK FARMERS</a>
            @endisset
            <a href="{{ admin_url('farmers/' . $s->id . '/edit') }}" class="btn btn-warning btn-sm"><i
                    class="fa fa-edit"></i>
                EDIT</a>
            <a href="#" onclick="window.print();return false;" class="btn btn-primary btn-sm"><i
                    class="fa fa-print"></i> PRINT</a>
        </div>
    </div>
    <hr class="my-3 my-md-4">
    <div class="row">
        <div class="col-3 col-md-2">
            <div class="border border-1 rounded bg-">
                <img class="img-fluid" src="{{ url('storage/' . $s->photo) }}">
            </div>
        </div>
        <div class="col-9 col-md-5">
            <h3 class="text-uppercase h4 p-0 m-0"><b>BIO DATA</b></h3>
            <hr class="my-1 my-md-3">

            @include('admin.components.detail-item', [
                't' => 'name',
                's' => $s->first_name . ' ' . $s->middle_name . ' ' . $s->last_name,
            ])
            @include('admin.components.detail-item', ['t' => 'Gender', 's' => $s->gender])
            @include('admin.components.detail-item', [
                't' => 'Age group',
                's' => $s->age_group,
            ])
            @include('admin.components.detail-item', [
                't' => 'Country',
                's' => $s->country ? $s->country->name : $s->country_id,
            ])

            @include('admin.components.detail-item', [
                't' => 'Language',
                's' => $s->language ? $s->language->name : $s->language_id,
            ])
            @include('admin.components.detail-item', [
                't' => 'Phone Number',
                's' => $s->phone,
            ])
            @include('admin.components.detail-item', [
                't' => 'Email Address',
                's' => $s->email,
            ])

        </div>
        <div class="pt-3 pt-md-0 col-md-5">
            <div class=" border border-primary p-3">
                <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Summary</b></h3>
                <hr class="border-primary mt-3">
                <div style="font-family: monospace; font-size: 16px;">
                    <p class="py-1 my-0 "><b>DATE REGISTERED:</b>
                        {{ Utils::to_date_time($s->created_at) }}</p>
                    <p class="py-1 my-0 "><b>Organisation:</b> {{ $s->organisation ? $s->organisation->name : '-' }}</p>
                    <p class="py-1 my-0 "><b>Farmer group:</b> {{ $s->farmer_group ? $s->farmer_group->name : '-' }}</p>
                    <p class="py-1 my-0 "><b>Farmer's address:</b> {{ $s->address }}</p>
                    <p class="py-1 my-0 "><b>No. of Trainings attended:</b> {{ $s->address }}</p>
                </div>
            </div>
        </div>
    </div>


    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Location Information</b></h3>
    <hr class="m-0 pt-2 mt-2 mb-3">

    <div class="row pt-2">
        <div class="col-md-6 pl-5 pl-md-5">
            @include('admin.components.detail-item', [
                't' => 'Farmer\'s address',
                's' => $s->address,
            ])

            @include('admin.components.detail-item', [
                't' => 'GPS',
                's' => $s->latitude . ',' . $s->longitude,
            ])


        </div>
        <div class="col-md-6 border-left pl-2 pl-5">
            @include('admin.components.detail-item', [
                't' => 'village',
                's' => $s->village,
            ])

            @include('admin.components.detail-item', [
                't' => 'House number',
                's' => $s->house_number,
            ])
        </div>
    </div>



    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Farming Details</b></h3>
    <hr class="m-0 pt-0 mb-3">


    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Business Planning and Risks</b></h3>
    <hr class="m-0 pt-0">

    <div class="row pt-2">
        <div class="col-md-6 pl-5 pl-md-5">
            @include('admin.components.detail-item', [
                't' => 'Ever received credit?',
                's' => $s->ever_received_credit,
            ])

            @include('admin.components.detail-item', [
                't' => 'Poverty level',
                's' => $s->poverty_level,
            ])


        </div>
        <div class="col-md-6 border-left pl-2 pl-5">
            @include('admin.components.detail-item', [
                't' => 'Food security level',
                's' => $s->food_security_level,
            ])
            @include('admin.components.detail-item', [
                't' => 'Farming scale',
                's' => $s->farming_scale,
            ])
        </div>
    </div>




    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Economic and Social Profile</b></h3>
    <hr class="m-0 pt-0">





    <hr class="my-5">
    <h3 class="text-uppercase h4 p-0 m-0 mb-2 text-center  mt-3 mt-md-5"><b>Farm Workforce and Assets</b></h3>
    <div class="row">
        <div class="col-12">
            <table class="table table-striped table-hover my-table">
                <thead class="bg-primary">
                    <tr>
                        {{-- <th scope="col">ID</th> --}}
                        <th scope="col">Photos</th>
                        <th scope="col">Category</th>
                        <th scope="col">Quantity (KGs) & No. of Pieces</th>
                        <th scope="col">Description</th>
                        {{--                         <th scope="col">Action</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ([] as $e)
                        @include('components/exhibit-item', ['e' => $e])
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>



    <hr class="my-5">
    <h3 class="text-uppercase h4 p-0 m-0 mb-2 text-center  mt-3 mt-md-5"><b>Insurance Details</b>
    </h3>



</div>
<style>
    .content-header {
        display: none;
    }
</style>
