    <div class="ml-auto">
        <div class="input-group" style="display: inline;">

        @if (isset($view_rights))
      @can($view_rights)
      <a href="{{ route($entity.'.show', [str_singular($entity) => $id])  }}" class="btn btn-sm btn-info btn-icon text-white mr-2" data-toggle="tooltip" title="" data-placement="bottom">
          <span>
              {{ $view_rename ?? 'View' }}
          </span>
        </a>
    @endcan
    @endif
    
    @if (isset($edit_rights))
      @can($edit_rights)
      <a href="{{ route($entity.'.edit',[str_singular($entity) => $id])  }}" class="btn btn-primary btn-icon btn-sm text-white mr-2" data-toggle="tooltip" title="" data-placement="bottom">
          <span>
              {{ $edit_rename ?? 'Edit' }}
          </span>
        </a>
      @endcan
    @endif

    @if (isset($delete_rights))
    @can($delete_rights)
    {!! Form::open( ['method' => 'delete', 'url' => route($entity.'.destroy', ['user' => $id]), 'style' => 'display: inline', 'onSubmit' => 'return confirm("'.trans("global.app_are_you_sure").'")']) !!}
        <button type="submit" class="btn btn-danger btn-icon btn-sm text-white mr-2" data-toggle="tooltip" title="" data-placement="bottom">
            <span>
              {{ $delete_rename ?? 'Delete' }}
          </span>
        </button>
    {!! Form::close() !!}
  @endcan
    @endif

    @if (isset($add_topics))
      @can($add_topics)
      <a href="{{ url('e-learning/courses/weeks/'.$courseId.'/'.$id.'/add-topic')  }}" class="btn btn-warning btn-icon btn-sm text-white mr-2" data-toggle="tooltip" title="" data-placement="bottom">
          <span>
              Add Topic
          </span>
        </a>
      @endcan
    @endif

      </div>


      
