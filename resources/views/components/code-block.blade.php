@props([
    'language',
])
@if (env('TORCHLIGHT_ENABLED'))
    <x-torchlight-code :language="$language">{{ $slot }}</x-torchlight-code>
@else
    <code class="text-xs whitespace-pre-line w-full overflow-x-scroll">{{ $slot }}</code>
@endif
