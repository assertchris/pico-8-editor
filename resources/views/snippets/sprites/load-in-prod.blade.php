@php
    $key = $this->sprite->slug ?? $this->sprite->name;
@endphp
var sprites = {
    // ...
    '{{ $key }}': [
        [
            @for ($y = 0; $y < $this->sprite->size; $y++)@for ($x = 0; $x < $this->sprite->size; $x++){{ str_pad($this->sprite->pixels[pixel_address($this->sprite->size, $x, $y)], 2, ' ', STR_PAD_LEFT) }}, @endfor
            @endfor
        ],
        [
            @for ($i = 0; $i < count($this->sprite->flags); $i++){{ $this->sprite->flags[$i] ? 'true' : 'false' }},
            @endfor
        ],
    ],
};
