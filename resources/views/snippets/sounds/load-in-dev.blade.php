@php
    $key = $this->sound->slug ?? $this->sound->name;
@endphp
var sounds = {
    // ...
    '{{ $key }}': preload('{{ $this->sound->url }}'),
};
