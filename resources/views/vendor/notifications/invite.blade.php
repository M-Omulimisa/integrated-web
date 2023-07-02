@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hello!')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => 'success'])
{{ $actionText }}
@endcomponent

@component('mail::button', ['url' => $rejectUrl, 'color' => 'error'])
{{ $rejectText }} 
@endcomponent

@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Regards'),<br>
{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang("If you’re having trouble clicking the buttons, copy and paste the URL below\n".
    "into your web browser:\n",
    )
@lang("\n:actionText:\n", ['actionText' => $actionText])
<span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>

{{ "\n".$rejectText.": " }}<span class="break-all">[{{ $displayableRejectUrl }}]({{ $rejectUrl }})

@endslot
@endisset
@endcomponent
