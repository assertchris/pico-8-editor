@php
    $key = $this->sprite->slug ?? $this->sprite->name;
@endphp
spr('{{ $key }}', 10, 20)
