<?php

use App\Models\Sprite;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public array $colors = [
        "#000000",
        "#1D2B53",
        "#7E2553",
        "#008751",
        "#AB5236",
        "#5F574F",
        "#C2C3C7",
        "#FFF1E8",
        "#FF004D",
        "#FFA300",
        "#FFEC27",
        "#00E436",
        "#29ADFF",
        "#83769C",
        "#FF77A8",
        "#FFCCAA",
    ];

    public Sprite $sprite;

    public array $pixels = [];

    public array $errors = [];

    public int $size = 8;

    public array $flags = [
        0 => false,
        1 => false,
        2 => false,
        3 => false,
        4 => false,
        5 => false,
        6 => false,
        7 => false,
    ];

    public function mount(): void
    {
        $this->size = $this->sprite->size;

        for ($y = 0; $y < $this->size; $y++) {
            for ($x = 0; $x < $this->size; $x++) {
                $address = pixel_address($this->size, $x, $y);
                $this->pixels[$address] = $this->sprite->pixels[$address] ?? -1;

                if ($this->pixels[$address] === null) {
                    $this->pixels[$address] = -1;
                }
            }
        }

        $this->flags = $this->sprite->flags;
    }

    public function save(): void
    {
        $this->sprite->pixels = $this->pixels;
        $this->sprite->size = $this->size;
        $this->sprite->save();
    }

    public function changeSlug(string $newSlug): void
    {
        $validator = validator()->make([
            'newSlug' => $newSlug,
        ], [
            'newSlug' => Rule::unique('sprites', 'slug')
                ->ignore($this->sprite)
                ->where('project_id', $this->sprite->project->id),
        ]);

        $this->errors['slug'] = null;

        if (!$validator->fails()) {
            $this->sprite->slug = $newSlug;
            $this->sprite->save();
        } else {
            $this->errors['slug'] = __('This slug is already taken');
        }
    }

    public function changeFlag(int $value): void
    {
        $this->flags[$value] = !$this->flags[$value];

        $this->sprite->flags = $this->flags;
        $this->sprite->save();
    }
};

?>
<div
    x-data="{
        colors: @entangle('colors'),
        selectedColor: 0,
        pixels: @entangle('pixels'),
        size: @entangle('size').live,
        flags: @entangle('flags').live,
        currentButton: null,
        init() {
            window.NProgress.done();
            setTimeout(() => window.NProgress.remove(), 500);
            this.updateSizeInput();
        },
        @if (user() && user()->is($this->sprite->project->user))
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
            this.$wire.save();
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
        changeSize(i) {
            this.size = [8, 16, 32][i];
            this.$wire.save();

            this.updateSizeInput();
        },
        updateSizeInput() {
            this.$refs.size.value = 0;

            if (this.size == 16) {
                this.$refs.size.value = 1;
            }

            if (this.size == 32) {
                this.$refs.size.value = 2;
            }
        },
        @endif
        address(x, y) {
            return (y * this.size) + x;
        }
    }"
    class="flex w-full"
>
    <div class="flex w-full h-full p-4 pl-0 space-x-4 items-start">
        <div class="flex flex-col w-2/3 space-y-4">
            <div class="grid grid-cols-{{ $this->size }} w-full aspect-square pattern-checkered-gray-200/100 pattern-checkered-scale-[2.5] divide-x divide-y divide-blue-200 border-r border-b border-blue-200">
                @for ($y = 0; $y < $this->size; $y++)
                    @for ($x = 0; $x < $this->size; $x++)
                        <button
                            wire:key="pixel-{{$x}}-{{ $y }}"
                            @if (user() && user()->is($this->sprite->project->user))
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
            <div class="flex flex-col w-full">
                <details>
                    <summary>{{ __('Preload in development (dynamic)') }}</summary>
                    <x-code-block language="js">@include('snippets.sprites.load-in-dev')</x-code-block>
                </details>
                <details>
                    <summary>{{ __('Load in production (static)') }}</summary>
                    <x-code-block language="js">@include('snippets.sprites.load-in-prod')</x-code-block>
                </details>
                <details>
                    <summary>{{ __('Use in code') }}</summary>
                    <x-code-block language="js">@include('snippets.sprites.use')</x-code-block>
                </details>
            </div>
        </div>
        @if (user() && user()->is($this->sprite->project->user))
            <div class="flex flex-col w-1/3 space-y-4">
                <div class="border border-gray-200 p-2 flex flex-col justify-start space-y-4">
                    <div class="flex flex-col">
                        {{ __('Slug') }}:
                        <input
                            type="text"
                            value="{{ $this->sprite->slug }}"
                            wire:keyup="changeSlug($event.target.value)"
                        >
                        @if(!empty($this->errors['slug']))
                            <div class="text-red">{{ $this->errors['slug'] }}</div>
                        @endif
                    </div>
                </div>
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
                <div class="border border-gray-200 p-2 flex flex-col justify-start space-y-4">
                    <div class="flex flex-row items-center space-x-2">
                        <span>{{ __('Flags') }}:</span>
                        @for ($i = 0; $i < 8; $i++)
                            <input
                                type="checkbox"
                                value="{{ $i }}"
                                wire:change="changeFlag($event.target.value)"
                                @if ($this->flags[$i])
                                    checked
                                @endif
                                class="flex"
                            />
                        @endfor
                    </div>
                </div>
                <div class="border border-gray-200 p-2 flex flex-col justify-start space-y-4">
                    <span>{{ __('Size') }}:</span>
                    <input
                        type="range"
                        min="0"
                        max="2"
                        x-ref="size"
                        x-on:change="changeSize($event.target.value)"
                    >
                </div>
            </div>
        @endif
    </div>
</div>

