<?php

use App\Models\Sound;
use Livewire\Volt\Component;

new class extends Component
{
    public int $length = 1;

    public array $notes = [];

    public int $instrument = 1;

    public Sound $sound;

    public function mount(): void
    {
        for ($i = 0; $i < 32; $i++) {
            if (!$this->sound->notes) {
                $this->sound->notes = $this->notes;
            }

            if (!$this->sound->length) {
                $this->sound->length = $this->length;
            }

            if (!isset($this->sound->notes[$i])) {
                $this->notes[$i] = [0, 0.8, $this->instrument];
            } else {
                $this->notes[$i] = $this->sound->notes[$i];
            }
        }
    }

    public function save(): void
    {
        $this->sound->length = $this->length;
        $this->sound->notes = $this->notes;
        $this->sound->save();
    }
};

?>
<div
    x-data="{
        length: @entangle('length').live,
        notes: @entangle('notes').live,
        instrument: @entangle('instrument').live,
        currentButton: null,
        frequencyClear(event, i) {
            event.preventDefault();
            this.notes[i][0] = 0;
        },
        frequencyMousedown(event, i) {
            event.preventDefault();

            if (event.button === 0) {
                this.currentButton = 'left';
            }

            if (event.button === 2) {
                this.currentButton = 'right';
            }

            this.frequencyMousemove(event, i)
        },
        frequencyMousemove(event, i) {
            if (this.currentButton) {
                var percent = 100 - Math.round((event.pageY - event.target.offsetTop) / 400 * 100);
                this.notes[i][0] = percent;
            }
        },
        volumeClear(event, i) {
            event.preventDefault();
            this.notes[i][1] = 0;
        },
        volumeMousedown(event, i) {
            event.preventDefault();

            if (event.button === 0) {
                this.currentButton = 'left';
            }

            if (event.button === 2) {
                this.currentButton = 'right';
            }

            this.volumeMousemove(event, i)
        },
        volumeMousemove(event, i) {
            if (this.currentButton) {
                var percent = 100 - Math.round((event.pageY - event.target.offsetTop) / 100 * 100);
                this.notes[i][1] = percent / 100;
            }
        },
        stop() {
            this.currentButton = null;
            this.$wire.save();
        },
        play() {
            var instance = new window.Engine();

            var sounds = {
                '{{ $this->sound->slug ?? $this->sound->name }}': [
                    this.length,
                    ...this.notes,
                ],
            };

            var options = {
                sprites: {},
                sounds,
                init: function() {
                    this.sfx('{{ $this->sound->slug ?? $this->sound->name }}');
                },
                update: () => {},
                draw: () => {},
                target: document.querySelector('.instance'),
            };

            instance.start(options);
        },
    }"
>
    <div class="flex flex-col h-[500px] w-2/3 select-text space-y-4">
        <div class="flex flex-row min-h-[400px] w-full">
            @for ($i = 0; $i < 32; $i++)
                <div
                    x-on:contextmenu="frequencyClear($event, {{ $i }})"
                    x-on:mousedown="frequencyMousedown($event, {{ $i }})"
                    x-on:mousemove="frequencyMousemove($event, {{ $i }})"
                    x-on:mouseup="stop"
                    class="flex h-full bg-gray-100 w-[3.125%] relative"
                >
                    <div
                        class="absolute w-full bg-gray-200 bottom-0 pointer-events-none"
                        x-bind:style="{ height: notes[{{ $i }}][0] + '%' }"
                    >
                        <div class="bg-red-300 h-[3px] w-full">
                            &nbsp;
                        </div>
                    </div>
                </div>
            @endfor
        </div>
        <div class="flex flex-row min-h-[100px] w-full">
            @for ($i = 0; $i < 32; $i++)
                <div
                    x-on:contextmenu="volumeClear($event, {{ $i }})"
                    x-on:mousedown="volumeMousedown($event, {{ $i }})"
                    x-on:mousemove="volumeMousemove($event, {{ $i }})"
                    x-on:mouseup="stop"
                    class="flex h-full bg-gray-100 w-[3.125%] relative"
                >
                    <div
                        class="absolute w-full bg-gray-200 bottom-0 pointer-events-none"
                        x-bind:style="{ height: (notes[{{ $i }}][1] * 100) + '%' }"
                    >
                        <div class="bg-red-300 h-[3px] w-full">
                            &nbsp;
                        </div>
                    </div>
                </div>
            @endfor
        </div>
        <div class="flex flex-col w-full">
            <div class="instance hidden"></div>
            <button x-on:click="play">play</button>
            <div>
                <h2>{{ __('Preload in dev') }}</h2>
                <x-code-block language="js">var sounds = [
                    // ...
                    '{{ $this->sound->slug ?? $this->sound->name }}': preload('{{ $this->sound->url }}'),
                ]</x-code-block>
            </div>
            <div>
                <h2>{{ __('Load in prod') }}</h2>
                <x-code-block language="js">{{ $this->sound->pretty }}</x-code-block>
            </div>
            <div>
                <h2>{{ __('Use') }}</h2>
                <x-code-block language="js">sfx('{{ $this->sound->slug ?? $this->sound->name }}', 15, 20)</x-code-block>
            </div>
        </div>
    </div>
</div>
