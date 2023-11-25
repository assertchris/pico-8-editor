@props([
    'language',
])
@if (env('TORCHLIGHT_ENABLED'))
    <pre><x-torchlight-code :language="$language">{{ $slot }}</x-torchlight-code></pre>
@else
    <code class="text-xs whitespace-pre-line">{{ $slot }}</code>
@endif
