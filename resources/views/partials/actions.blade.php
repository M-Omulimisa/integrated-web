<div class="btn-toolbar">
  <div class="btn-group">
    @if (isset($view))
      @can($view)
      <a href="{{ route($route.'.show', [$id])  }}" class="btn btn-default btn-sm">
          {{-- <i class="fa fa-eye"></i> --}} <span class="text-gebo">View</span></a>
    @endcan
    @endif
    
    @if (isset($manage))
      @can($manage)
      <a href="{{ route($route.'.edit', [$id])  }}" class="btn btn-default btn-sm">
          {{-- <i class="fa fa-edit"></i> --}} <span class="text-primary">Update</span></a>
      @endcan
    @endif
  
  </div>

  <div class="btn-group">
  @if (isset($delete))
    @can($delete)
    {!! Form::open( ['method' => 'delete', 'url' => route($route.'.destroy', [$id]), 'style' => 'display: inline', 'onSubmit' => 'return confirm("'.trans("strings.are_you_sure_delete").'")']) !!}
        <button type="submit" class="btn btn-default btn-sm mr-2">
            <span class="text-danger">{{ $delete_name ?? 'Delete' }}</span>
        </button>
    {!! Form::close() !!}
    @endcan
  @endif

</div>
</div>