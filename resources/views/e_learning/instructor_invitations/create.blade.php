@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@php ($module = "Instructors")

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => '...',
    'menu_group'    => '...',
    'menu_item'     => 'E-Learning',
    'menu_item_url' => '#',
    'current'       => '...'
])
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">

        <div class="card" id="pages">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    @can('list_el_instructors')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.instructors.index') }}">All Instructors</a></li>
                    @endcan
                    @can('list_el_instructor_invitations')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.instructor-invitations.index') }}">Invitations</a></li>
                    @endcan
                    @can('add_el_instructor_invitations')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Invite Instructors</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">
                        
                        {!! Form::open(['method' => 'POST', 'route' => ['e-learning.instructor-invitations.store']]) !!}

                        <div class="form-group mb-3">
                            {!! Form::label('full_name', 'Full Name*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('full_name', old('full_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('email', 'Email*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                            {!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}               
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('role_id', 'Role*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                           {!! Form::select('role_id', $roles, old('role_id'), array('class' => 'form-control select2', 'required' => '')) !!}   
                            </div>
                        </div>

                        <div class="form-buttons-w">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        </div>

                        {!! Form::close() !!}

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


