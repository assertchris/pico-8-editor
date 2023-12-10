@props([
    'language',
])
@php
    print \Spatie\ShikiPhp\Shiki::highlight(
        code: preg_replace('/<!--(.|\s)*?-->/', '', $slot),
        language: $language,
        theme: 'github-light',
    );
@endphp
