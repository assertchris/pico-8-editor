<div
    x-data="{
    addSprite() {
      var name = prompt('What should we call it?');
      this.$wire.addSprite(name);
    },
    addSound() {
      var name = prompt('What should we call it?');
      this.$wire.addSound(name);
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
        this.$wire.renameSprite(id, newName);
    },
    editSound(id) {
      this.$wire.editSound(id);
    },
  }"
    class="flex flex-row w-screen h-screen"
>
    <div class="flex flex-col w-1/4 h-screen">
        <div class="p-4 space-y-4 border-gray-100">
            <div class="border border-gray-200 p-2 flex flex-col justify-start">
                <div class="flex flex-row w-full">
                    <h2 class="flex flex-grow">{{ __('Sprites') }}</h2>
                    <button
                        x-on:click="addSprite"
                        class="flex flex-shrink"
                    >
                        {{ __('Add') }}
                    </button>
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
                                        {{ __('Edit') }}
                                    </button>
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
