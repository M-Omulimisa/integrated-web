@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@php ($module = "Default Instructions")

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => '...',
    'menu_group'    => '...',
    'menu_item'     => 'E-Learning',
    'menu_item_url' => '#',
    'current'       => '...'
])
<!-- end page title -->

@section('extra-buttons')
    <div class="ml-auto">
        <div class="input-group">

        @if (auth()->user()->hasRole('instructor'))
          <a href="{{ route('e-learning.instructions.picture.change', $data->id) }}"  class="btn btn-primary btn-icon text-white mr-2" data-toggle="tooltip" title="" data-placement="bottom">
            <span><i class="fe fe-photo"></i> Change Picture</span>
          </a>
      @endif

        </div>
      </div>
@endsection

@section('content')

<div class="row">
    <div class="col-xl-12">

        <div class="card" id="pages">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    @can('list_el_instructions')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.instructions.index') }}">All Default Instructions</a></li>
                    @endcan
                    @can('add_el_instructions')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.instructions.create') }}">Add Default Instructions</a></li>
                    @endcan
                    @can('view_el_instructions')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Default Instruction</a></li>
                    @endcan
                    @can('edit_el_instructions')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.instructions.edit', $data->id) }}">Edit Default Instruction</a></li>
                    @endcan
                    <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.system-out-calls.index') }}">System Out Calls</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.messages.index') }}">Default Messages</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">

                        {{-- <table class="table row table-stripped w-100 m-0">
                            <tbody class="col-lg-12 p-0">
                                <tr><th>
                                    <img src="{{ is_null($data->picture) ? asset('uploads/profile_pics/default.png') : asset('uploads/'.$data->picture) }}" class="avatar-xxl rounded-circle" alt="profile">
                                </th></tr>
                                <tr><th>Full Name </th><td> {{ $data->full_name }}</td></tr>
                                <tr><th>Gender </th><td> {{ $data->gender }}</td></tr>
                                <tr><th>Age Group </th><td> {{ $data->age_group }}</td></tr>
                                <tr><th>Email </th><td> {{ $data->email }}</td></tr>
                                <tr><th>Phone number </th><td> {{ $data->phone_number }}</td></tr>
                                <tr><th>Affiliation </th><td> {{ $data->affiliation }}</td></tr>
                                <tr><th>Qualification </th><td> {{ $data->qualification }}</td></tr>
                                <tr><th>Country </th><td> {{ $data->country }}</td></tr>
                                <tr><th>District </th><td> {{ $data->district->name ?? '' }}</td></tr>
                                <tr><th>Subcounty </th><td> {{ $data->subcounty->name ?? '' }}</td></tr>
                                <tr><th>Parish </th><td> {{ $data->parish ?? '' }}</td></tr>
                                <tr><th>Village </th><td> {{ $data->village }}</td></tr>
                                <tr><th>Organisation </th><td> {{ $data->organisation->name ?? '' }}</td></tr>
                                <tr><th>Business </th><td> {{ $data->business }}</td></tr>
                                <tr><th>Receive email notifications </th><td> {{ $data->email_notifications ? 'Yes' : 'No' }}</td></tr>
                                <tr><th>Receive SMS alerts </th><td> {{ $data->sms_notifications ? 'Yes' : 'No' }}</td></tr>
                                <tr><th>Registration Date </th><td> {{ $data->created_at }}</td></tr>
                            </tbody>
                        </table> --}}

                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>



<!-- End Row -->
   
@endsection

@section('styles')

@endsection

@section('scripts')

@endsection

