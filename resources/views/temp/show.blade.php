@extends('layouts.app')

@section('content')

@include('partials.breadcrumb',[
    'module'        => 'Users',
    '_module'       => route('users.index'),
    'current'       => 'User Details'
])

<div class="row">
    <div class="col-sm-12 col-md-12">

        <div class="w-box" id="w_sort07">
                <div class="w-box-header">
                    &nbsp;
                </div>
                <div class="w-box-content">
                    <div class="tabbable clearfix">
                        <ul class="nav nav-tabs">
                            @can('list_users')
                                <li><a href="{{ route('users.index') }}">List Users</a></li>
                            @endcan
                            @can('manage_users')
                                <li><a href="{{ route('users.create') }}">Create Users</a></li>
                            @endcan
                            @can('view_users')
                                <li class="active"><a href="{{ url()->current() }}">User</a></li>
                            @endcan
                            @can('list_logs')
                                <li><a href="{{ url('activity-log/list/'.$data->id) }}">List User Activity Logs</a></li>
                                <li><a href="{{ url('access-log/list/'.$data->id) }}">List User Session Logs</a></li>
                            @endcan
                            @can('manage_users')
                                @if ($data->status == 'Active')
                                    <div class="r-nav-tabs">
                                        <li><a class="text-danger" href="{{ url('user/block-unblock/'.$data->id) }}">Block</a></li>
                                    </div>
                                @elseif ($data->status == 'Inactive')
                                    <div class="r-nav-tabs">
                                        <li><a class="text-success" href="{{ url('user/block-unblock/'.$data->id) }}">Unblock</a></li>
                                    </div>
                                @endif
                            @endcan
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active">

                            <!-- start content -->

                            <div class="row">
                                <div class="col-sm-6 col-md-6">

                                    <div class="form-horizontal">

                                        <div class="form-group mb-3">
                                            <label class="col-lg-4 control-label">Name</label>
                                            <div class="col-lg-8">{{ $data->name }}</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="col-lg-4 control-label">Email</label>
                                            <div class="col-lg-8">{{ $data->email }}</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="col-lg-4 control-label">Phone</label>
                                            <div class="col-lg-8">{{ $data->phone }}</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="col-lg-4 control-label">Status</label>
                                            <div class="col-lg-8">{{ $data->status }}</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="col-lg-4 control-label">Roles</label>
                                            <div class="col-lg-8">
                                                @if(!empty($data->getRoleNames()))
                                                    @foreach($data->getRoleNames() as $v)
                                                        <label class="badge badge-success">{{ $v }}</label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="col-lg-4 control-label">Email Verified At</label>
                                            <div class="col-lg-8">{{ $data->email_verified_at }}</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="col-lg-4 control-label">Last Login At</label>
                                            <div class="col-lg-8">{{ $data->last_login_at }}</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="col-lg-4 control-label">Created At</label>
                                            <div class="col-lg-8">{{ $data->created_at }}</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="col-lg-4 control-label">Last Update</label>
                                            <div class="col-lg-8">{{ $data->updated_at }}</div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- end content -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
    </div>
</div>

@endsection

@section('styles')

@endsection

@section('scripts')

@endsection