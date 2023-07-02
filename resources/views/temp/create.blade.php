@extends('layouts.app')

@section('content')

@include('partials.breadcrumb',[
    'module'        => 'Users',
    '_module'       => route('users.index'),
    'current'       => 'Create Users'
])

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="w-box" id="w_sort07">
            <div class="w-box-header">&nbsp;</div>
            <div class="w-box-content">
                <div class="tabbable clearfix">
                    <ul class="nav nav-tabs">
                        @can('list_users')
                            <li><a href="{{ route('users.index') }}">List Users</a></li>
                        @endcan
                        @can('manage_users')
                            <li class="active"><a href="{{ route('users.create') }}">Create Users</a></li>
                        @endcan
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active">

                            <!-- start content -->

                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    
                                    {!! Form::open(array('route' => 'users.store','method'=>'POST', 'class'=>'form-horizontal')) !!}
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
                                        <div class="form-group mb-3">
                                            <label class="col-lg-4 control-label">Password</label>
                                            <div class="col-lg-8">
                                                {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-lg-4 control-label">Confirm Password</label>
                                            <div class="col-lg-8">
                                                {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-lg-4 control-label">Role</label>
                                            <div class="col-lg-8">
                                                {!! Form::select('roles[]', [null=>'Please select']+$roles,[], array('class' => 'chzn_b form-control','multiple')) !!}
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-lg-4 control-label">Status</label>
                                            <div class="col-lg-8">
                                                {!! Form::select('status', [null=>'Please select']+$statuses,old('status'), array('class' => 'chzn_a form-control')) !!}
                                            </div>
                                        </div>

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