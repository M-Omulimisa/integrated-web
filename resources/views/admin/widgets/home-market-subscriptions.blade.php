<?php 
//importing the model
use App\Models\Utils;

?><div class="list-group list-group-flush m-0 p-0">
    @foreach ($data as $item)
        <div class="list-group-item list-group-item-action flex-column align-items-start  p-0 m-0">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1 d-dark" style="font-weight: 800">{{ $item->name_text  }}</h5>
                <small class="text-muted mt-2">{{ Utils::my_time_ago($item->created_at) }}</small>
            </div>
            <p class="mb-1">{{ $item->frequency }} - ({{ $item->period_paid }})
            </p> 
        </div>
    @endforeach
</div>
