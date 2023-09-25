<div
    x-data="{
        selectedColor: @entangle('selectedColor').live,
        mode: 'paint',
        async init() {
            await this.$wire.load();
        },
        selectPixel(x, y) {
            if (this.mode == 'paint' && this.selectedColor) {
                this.$wire.paint(x, y);
            }

            if (this.mode == 'erase') {
                this.$wire.erase(x, y);
            }
        },
    }"
    class="flex w-full"
>
    <div class="flex w-full h-full p-4 pl-0 space-x-4 items-start">
        <div class="flex flex-col w-2/3 space-y-4">
            <div class="flex flex-row flex-wrap w-full aspect-square pattern-checkered-gray-200/100 pattern-checkered-scale-[2.5]">
                @for ($y = 0; $y < 8; $y++)
                    @for ($x = 0; $x < 8; $x++)
                        <button
                            wire:key="pixel-{{$x}}-{{ $y }}"
                            @if (session()->get('unlocked'))
                                x-on:click="selectPixel({{ $x }}, {{ $y }})"
                            @endif
                            style="background-color: {{ $this->coloredPixels[$y][$x] ?? 'transparent' }}"
                            class="flex flex-row w-[12.5%] aspect-square"
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
                                x-on:click="selectedColor = '{{ $color }}'"
                                x-bind:class="{
                                    'border-8 border-white': selectedColor == '{{ $color }}',
                                    'border-8 border-transparent': selectedColor != '{{ $color }}'
                                }"
                                class="flex w-[25%] aspect-square text-white items-center justify-center" style="background-color: {{ $color }}"
                            >
                                <span class="bg-black bg-opacity-50 rounded px-2 py-1 pointer-events-none">{{ $i }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
                @if (session()->get('unlocked'))
                    <div class="flex flex-col space-y-4">
                        <button
                            x-on:click="mode = 'paint'"
                            x-bind:class="{
                                'bg-gray-500 text-white': mode == 'paint',
                                'bg-gray-100 text-black': mode != 'paint',
                            }"
                            class="px-4 py-2"
                        >
                            {{ __('Paint') }}
                        </button>
                        <button
                            x-on:click="mode = 'erase'"
                            x-bind:class="{
                                'bg-gray-500 text-white': mode == 'erase',
                                'bg-gray-100 text-black': mode != 'erase',
                            }"
                            class="px-4 py-2"
                        >
                            {{ __('Erase') }}
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
