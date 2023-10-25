@extends('layouts.app')

@section('content')

@include('partials.breadcrumb',[
    'module'        => 'Users',
    '_module'       => route('users.index'),
    'current'       => 'Edit User'
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
                                <li><a href="{{ route('users.show', $data->id) }}">User</a></li>
                            @endcan
                            @can('manage_users')
                                <li class="active"><a href="{{ url()->current() }}">Edit User</a></li>
                            @endcan
                            @can('list_logs')
                                <li><a href="{{ url('user_log/list/'.$data->id) }}">List User Activity Logs</a></li>
                                <li><a href="{{ url('user_session_log/list/'.$data->id) }}">List User Session Logs</a></li>
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

                                    {!! Form::model($data, ['method' => 'PATCH','route' => ['users.update', $data->id], 'class'=>'form-horizontal']) !!}
                                        <div class="form-group mb-3">
                                            <label class="col-lg-4 control-label">Name</label>
                                            <div class="col-lg-8">
                                                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-lg-4 control-label">Email</label>
                                            <div class="col-lg-8">
                                                {!! Form::text('email', null, array('placeholder' => 'example@abc.com','class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-lg-4 control-label">Phone</label>
                                            <div class="col-lg-8">
                                                {!! Form::text('phone', null, array('placeholder' => '256','class' => 'form-control')) !!}
                                            </div>
                                        </div>

                                        @if (in_array(\App\Models\Users\Role::ROLE_INSTN_ADMIN, $userRole) || in_array(\App\Models\Users\Role::ROLE_BRAN_ADMIN, $userRole) || in_array(\App\Models\Users\Role::ROLE_INSTN_USER, $userRole) || in_array(\App\Models\Users\Role::ROLE_BRAN_USER, $userRole))
                                            <input type="hidden" name="roles[]" value="@foreach($userRole as $role) {{ $role }} @endforeach">
                                        @else
                                            <div class="form-group mb-3">
                                                <label class="col-lg-4 control-label">Role</label>
                                                <div class="col-lg-8">
                                                    {!! Form::select('roles[]', $roles,$userRole, array('class' => 'chzn_b form-control','multiple')) !!}
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-group mb-3">
                                            <div class="col-lg-offset-4 col-lg-8">
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </div>
                                    {!! Form::close() !!}
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

    <script>

        $(document).ready(function() {
            //* enhanced select
            gebo_chosen.init();
        });
        
        //* enhanced select elements
        gebo_chosen = {
            init: function(){
                $(".chzn_a").chosen({
                    allow_user_deselect: true
                });
                $(".chzn_b").chosen();
            }
        };
    </script>

@endsection
