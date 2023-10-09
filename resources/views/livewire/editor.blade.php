<div
    x-data="{
        addSprite() {
          var name = prompt('What should we call it?');
          if (name) {
            this.$wire.addSprite(name);
          }
        },
        addSound() {
          var name = prompt('What should we call it?');
          if (name) {
            this.$wire.addSound(name);
          }
        },
        editSprite(id) {
          this.$wire.editSprite(id);
        },
        deleteSprite(id) {
            if (confirm('Are you sure?')) {
                this.$wire.deleteSprite(id);
            }
        },
        renameSprite(id, oldName) {
            var newName = prompt('What should we call it?', oldName);
            if (newName) {
                this.$wire.renameSprite(id, newName);
            }
        },
        editSound(id) {
          this.$wire.editSound(id);
        },
        setCode() {
            var code = prompt('What should the code be? (do not forget it!)');
            if (code) {
                this.$wire.setCode(code);
            }
        },
        unlock() {
            var code = prompt('What is the code?');
            if (code) {
                this.$wire.unlock(code);
            }
        },
    }"
    class="flex flex-row w-screen h-screen"
>
    <div class="flex flex-col w-1/4 h-screen">
        <div class="p-4 space-y-4 border-gray-100">
            @if ($project)
                <div class="border border-gray-200 p-2 flex flex-col justify-start">
                    <div class="flex flex-col w-full">
                        <h2 class="flex flex-grow">{{ __('Access') }}</h2>
                        @if ($this->project->code)
                            <div wire:key="has-code">
                                {{ __('Must have code to edit') }}
                                @if (session()->get('unlocked'))
                                    <div wire:key="unlocked">
                                        {{ __('Unlocked') }}
                                    </div>
                                    <button
                                        x-on:click="setCode"
                                        class="flex flex-shrink"
                                    >
                                        {{ __('Change code') }}
                                    </button>
                                @else
                                    <div wire:key="locked">
                                        {{ __('Locked') }}
                                        <button
                                            x-on:click="unlock"
                                            class="flex flex-shrink"
                                        >
                                            {{ __('Enter code') }}
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div wire:key="missing-code">
                                {{ __('Anyone can edit') }}
                                <button
                                    x-on:click="setCode"
                                    class="flex flex-shrink"
                                >
                                    {{ __('Set code') }}
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            <div class="border border-gray-200 p-2 flex flex-col justify-start">
                <div class="flex flex-row w-full">
                    <h2 class="flex flex-grow">{{ __('Sprites') }}</h2>
                    @if (session()->get('unlocked') || !$this->project)
                        <button
                            x-on:click="addSprite"
                            class="flex flex-shrink"
                        >
                            {{ __('Add') }}
                        </button>
                    @endif
                </div>
                @if ($this->project && $this->project->sprites->count())
                    <div class="flex flex-col w-full">
                        @foreach ($this->project->sprites as $sprite)
                            <div
                                wire:key="sprite-{{ $sprite->id }}"
                                class="flex flex-row w-full"
                            >
                                <span class="flex flex-grow">
                                    {{ $sprite->name }}
                                </span>
                                <span class="flex flex-row flex-shrink space-x-2">
                                    <button
                                        x-on:click="editSprite('{{ $sprite->id }}')"
                                        class="flex"
                                    >
                                        @if (session()->get('unlocked'))
                                            {{ __('Edit') }}
                                        @else
                                            {{ __('View') }}
                                        @endif
                                    </button>
                                    @if (session()->get('unlocked'))
                                        <button
                                            x-on:click="renameSprite('{{ $sprite->id }}', '{{ $sprite->name }}')"
                                            class="flex"
                                        >
                                            {{ __('Rename') }}
                                        </button>
                                        <button
                                            x-on:click="deleteSprite('{{ $sprite->id }}')"
                                            class="flex"
                                        >
                                            {{ __('Delete') }}
                                        </button>
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{ __('No sprites') }}
                @endif
            </div>
{{--            <div class="border border-gray-200 p-2 flex flex-col justify-start">--}}
{{--                <h2 class="flex">{{ __('Sounds') }}</h2>--}}
{{--                <button--}}
{{--                    x-on:click="addSound"--}}
{{--                    class="flex"--}}
{{--                >--}}
{{--                    {{ __('Add sound') }}--}}
{{--                </button>--}}
{{--                @if ($this->project && $this->project->sounds->count())--}}
{{--                    <div class="flex flex-col w-full">--}}
{{--                        @foreach ($this->project->sounds as $sound)--}}
{{--                            <div wire:key="sound-{{ $sound->id }}">--}}
{{--                                {{ $sound->name }}--}}
{{--                                <button--}}
{{--                                    x-on:click="editSound('{{ $sound->id }}')"--}}
{{--                                    class="flex"--}}
{{--                                >--}}
{{--                                    {{ __('Edit sound') }}--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                @else--}}
{{--                    {{ __('No sounds') }}--}}
{{--                @endif--}}
{{--            </div>--}}
        </div>
    </div>
    <div class="flex flex-col w-3/4 h-screen">
        @if ($this->editing instanceof \App\Models\Sprite)
            <livewire:sprite-editor
                wire:key="sprite-editor-{{ $this->editing->id }}"
                :sprite="$this->editing"
            />
        @endif
        @if ($this->editing instanceof \App\Models\Sound)
            <livewire:sound-editor
                wire:key="sound-editor-{{ $this->editing->id }}"
                :sound="$this->editing"
            />
        @endif
    </div>
</div>
