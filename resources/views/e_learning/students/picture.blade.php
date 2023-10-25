@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@php ($module = "Parishes")

@section('title', $module)

@section('breadcrumb')
    @include('partials.breadcrumb',['link1'=> 'Upload', 'active_link'=> $module])
@endsection

@section('extra-buttons')
    <div class="ml-auto">
        <div class="input-group">

        @can('view_parishes')
          <a href="{{ route('parish.index') }}"  class="btn btn-primary btn-icon text-white mr-2" data-toggle="tooltip" title="" data-placement="bottom">
            <span>
              <i class="fe fe-list"></i> View {{ $module }}
            </span>
          </a>
        @endcan

        </div>
      </div>
@endsection

@section('content')

<div class="row">
    <div class="col-xl-12">

        <div class="card" id="pages">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    @can('list_el_students')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.students.index') }}">All Students</a></li>
                    @endcan
                    @can('add_el_students')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.students.create') }}">Add Students</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.students.upload') }}">Upload Students</a></li>
                    @endcan
                    @can('view_el_students')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.students.show', $data->id) }}">Student Profile</a></li>
                    @endcan
                    @can('edit_el_students')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.students.edit', $data->id) }}">Edit Student Profile</a></li>
                    @endcan
                    @if(auth()->user()->hasRole('student'))
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Update Student Picture</a></li>
                    @endif
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">

                        {!! Form::open(['method' => 'POST', 'files' => true, 'route' => ['e-learning.students.picture.store']]) !!}

                        <input type="hidden" name="student_id" value="{{ $data->id }}">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">Required file type: jpg,png,jpeg<br/>Must not exceed 2MBs</div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('picture', 'Choose File*', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-5">
                           <input class="" type="file" id="picture" name="picture"/>  
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

