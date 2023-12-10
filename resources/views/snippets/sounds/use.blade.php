@php
    $key = $this->sound->slug ?? $this->sound->name;
@endphp
sfx('{{ $key }}')
