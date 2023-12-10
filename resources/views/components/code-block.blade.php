@props([
    'language',
])
<div class="text-xs">
    @php
        print \Spatie\ShikiPhp\Shiki::highlight(
            code: preg_replace('/<!--(.|\s)*?-->/', '', $slot),
            language: $language,
            theme: 'github-light',
        );
    @endphp
</div>
