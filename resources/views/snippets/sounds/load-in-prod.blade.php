@php
    $key = $this->sound->slug ?? $this->sound->name;
@endphp
var sounds = {
    // ...
    '{{ $key }}': [
        {{ $this->sound->length }},
        @foreach ($this->sound->notes as $note)[{{ $note[0] }}, {{ $note[1] }}, {{ $note[2] }}],
        @endforeach
    ],
};
