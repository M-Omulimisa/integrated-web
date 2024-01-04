<div class="btn-toolbar">
  <div class="btn-group">
    @if (isset($view))
      @if($view)
      <a href="{{ route($route.'.show', [$id])  }}" class="btn btn-default btn-sm">
          <span class="text-gebo">View</span></a>
    @endif
    @endif
    
    @if (isset($manage))
      @if($manage)
      <a href="{{ route($route.'.edit', [$id])  }}" class="btn btn-default btn-sm">
          <span class="text-primary">Update</span></a>
      @endif
    @endif
  
  </div>

  <div class="btn-group">
  @if (isset($delete))
    @if($delete)
    {!! Form::open( ['method' => 'delete', 'url' => route($route.'.destroy', [$id]), 'style' => 'display: inline', 'onSubmit' => 'return confirm("'.trans("strings.are_you_sure_delete").'")']) !!}
        <button type="submit" class="btn btn-default btn-sm mr-2">
            <span class="text-danger">{{ $delete_name ?? 'Delete' }}</span>
        </button>
    {!! Form::close() !!}
    @endif
  @endif

</div>
</div>