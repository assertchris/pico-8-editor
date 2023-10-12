<div
    x-data="{
        colors: @entangle('colors'),
        selectedColor: 0,
        pixels: @entangle('pixels').live,
        size: @entangle('size').live,
        mode: 'paint',
        currentButton: null,
        mousedown(event, x, y) {
            event.preventDefault();

            if (event.button === 0) {
                this.currentButton = 'left';
            }

            if (event.button === 2) {
                this.currentButton = 'right';
            }

            this.selectPixel(x, y);
        },
        mouseup(event) {
            this.currentButton = null;
        },
        mouseover(x, y) {
            if (this.currentButton) {
                this.selectPixel(x, y);
            }
        },
        selectPixel(x, y) {
            if (this.currentButton === 'left') {
                this.pixels[this.address(x, y)] = this.selectedColor;
            }

            if (this.currentButton === 'right') {
                this.pixels[this.address(x, y)] = -1;
            }
        },
        address(x, y) {
            return (y * this.size) + x;
        }
    }"
    class="flex w-full"
>
    <div class="flex w-full h-full p-4 pl-0 space-x-4 items-start">
        <div class="flex flex-col w-2/3 space-y-4">
            <div class="flex grid grid-cols-8 w-full aspect-square pattern-checkered-gray-200/100 pattern-checkered-scale-[2.5] divide-x divide-y divide-blue-200 border-r border-b border-blue-200">
                @for ($y = 0; $y < 8; $y++)
                    @for ($x = 0; $x < 8; $x++)
                        <button
                            wire:key="pixel-{{$x}}-{{ $y }}"
                            @if (session()->get('unlocked'))
                                x-on:contextmenu="$event.preventDefault()"
                                x-on:mouseup="mouseup($event)"
                                x-on:mousedown="mousedown($event, {{ $x }}, {{ $y }})"
                                x-on:mousemove="mouseover({{ $x }}, {{ $y }})"
                            @endif
                            x-bind:style="{ backgroundColor: pixels[address({{ $x }}, {{ $y }})] === -1 ? 'transparent' : colors[pixels[address({{ $x }}, {{ $y }})]] }"
                            class="
                                flex flex-grow aspect-square
                                @if ($x === 0 && $y === 0)
                                    border-t border-l border-blue-200
                                @endif
                            "
                        ></button>
                    @endfor
                @endfor
            </div>
            <div>
                <h2>{{ __('Preload in dev') }}</h2>
                <pre class="text-xs overflow-x-scroll bg-gray-100 p-2">var sprites = [
    // ...
    '{{ $this->sprite->name }}': preload('{{ $this->sprite->url }}'),
]</pre>
            </div>
            @if ($this->pixels)
            <div>
                    <h2>{{ __('Load in prod') }}</h2>
                    <pre class="text-xs overflow-x-scroll bg-gray-100 p-2">var sprites = [
    // ...
    '{{ $this->sprite->name }}': {{ json_encode($this->pixels) }},
]</pre>
                </div>
            @endif
            <div>
                <h2>{{ __('Use') }}</h2>
                <pre class="text-xs whitespace-pre-line bg-gray-100 p-2">spr('{{ $this->sprite->name }}', 15, 20)</pre>
            </div>
        </div>
        <div class="flex flex-col w-1/3">
            <div class="border border-gray-200 p-2 flex flex-col justify-start space-y-4">
                <div class="flex flex-col">
                    <div class="flex flex-row flex-wrap w-full aspect-square">
                        @foreach ($this->colors as $i => $color)
                            <button
                                x-on:click="selectedColor = {{ $i }}"
                                x-bind:class="{
                                    'border-8 border-white': selectedColor == {{ $i }},
                                    'border-8 border-transparent': selectedColor != {{ $i }},
                                }"
                                class="flex w-[25%] aspect-square text-white items-center justify-center" style="background-color: {{ $color }}"
                            >
                                <span class="bg-black bg-opacity-50 rounded px-2 py-1 pointer-events-none">{{ $i }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
