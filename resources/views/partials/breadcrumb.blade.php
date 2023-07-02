<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">
                @isset ($page_title)
                    {{ $page_title }}
                @endisset
            </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    {{-- <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li> --}}
                    <li class="breadcrumb-item">
                        @if (isset($menu_group))
                            <a href="javascript: void(0);">{{ $menu_group }}</a>
                        @else
                            <a href="#" class="text-danger">Menu !@#$%%^&*</a>
                        @endif
                    </li>

                    @if (isset($menu_item) && isset($menu_item_url))
                        <li class="breadcrumb-item"><a href="{{ $menu_item_url }}">{{ $menu_item }}</a></li>
                    @endif

                    <li class="breadcrumb-item active">
                        @if (isset($current))
                            {{ $current }}
                        @else
                            Current !@#$%%^&*
                        @endif
                    </li>
                </ol>
            </div>

        </div>
    </div>
</div>

