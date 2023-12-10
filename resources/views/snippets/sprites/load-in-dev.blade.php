@php
    $key = $this->sprite->slug ?? $this->sprite->name;
@endphp
var sprites = {
    // ...
    '{{ $key }}': preload('{{ $this->sprite->url }}'),
};
