@foreach ($data as $item)
    @include('admin.components.detail-item-2', [
        't' => $item['title'],
        's' => $item['detail'],
    ]) 
@endforeach