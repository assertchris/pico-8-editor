<?php

use App\Models\Project;
use App\Models\Sound;
use App\Models\Sprite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public Project|null $project = null;

    public Sprite|Sound|null $editing = null;

    public array $errors = [];

    public function addSprite(string $name): void
    {
        $this->project->sprites()->create([
            'name' => $name,
        ]);
    }

    public function addSound(string $name): void
    {
        $this->project->sounds()->create([
            'name' => $name,
        ]);
    }

    public function editSprite(string $id): void
    {
        $this->editing = Sprite::findOrFail($id);
    }

    public function editSound(string $id): void
    {
        $this->editing = Sound::findOrFail($id);
    }

    public function deleteSprite(string $id): void
    {
        Sprite::findOrFail($id)->delete();
        $this->redirect($this->project->url);
    }

    public function deleteSound(string $id): void
    {
        Sound::findOrFail($id)->delete();
        $this->redirect($this->project->url);
    }

    public function renameSprite(string $id, string $name): void
    {
        $sprite = Sprite::findOrFail($id);
        $sprite->name = $name;
        $sprite->save();
    }

    public function renameSound(string $id, string $name): void
    {
        $sound = Sound::findOrFail($id);
        $sound->name = $name;
        $sound->save();
    }

    public function changeName(string $newName): void
    {
        $this->project->name = $newName;
        $this->project->save();
    }

    public function changeSlug(string $newSlug): void
    {
        $validator = validator()->make([
            'newSlug' => $newSlug,
        ], [
            'newSlug' => Rule::unique('projects', 'slug')->ignore($this->project),
        ]);

        $this->errors['slug'] = null;

        if (!$validator->fails()) {
            $this->project->slug = $newSlug;
            $this->project->save();

            $this->redirect($this->project->url);
        } else {
            $this->errors['slug'] = __('This slug is already taken');
        }
    }
};

?>

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
        editSound(id) {
          this.$wire.editSound(id);
        },
        deleteSprite(id) {
            if (confirm('Are you sure?')) {
                this.$wire.deleteSprite(id);
            }
        },
        deleteSound(id) {
            if (confirm('Are you sure?')) {
                this.$wire.deleteSound(id);
            }
        },
        renameSprite(id, oldName) {
            var newName = prompt('What should we call it?', oldName);
            if (newName) {
                this.$wire.renameSprite(id, newName);
            }
        },
        renameSound(id, oldName) {
            var newName = prompt('What should we call it?', oldName);
            if (newName) {
                this.$wire.renameSound(id, newName);
            }
        },
    }"
    class="flex flex-row w-screen h-screen"
>
    <div class="flex flex-col w-1/4 h-screen">
        <div class="p-4 space-y-4 border-gray-100">
            @if (user() && user()->is($this->project->user) || !$this->project)
                <div class="border border-gray-200 p-2 flex flex-col justify-start space-y-4">
                    <div class="flex flex-col">
                        {{ __('Name') }}:
                        <input
                            type="text"
                            value="{{ $this->project->name }}"
                            wire:change="changeName($event.target.value)"
                        />
                    </div>
                </div>
            @else
                <h1>
                    {{ $this->project->name }}
                </h1>
            @endif
            @if (user() && user()->is($this->project->user) || !$this->project)
                <div class="border border-gray-200 p-2 flex flex-col justify-start space-y-4">
                    <div class="flex flex-col">
                        {{ __('Slug') }}:
                        <input
                            type="text"
                            value="{{ $this->project->slug }}"
                            wire:change="changeSlug($event.target.value)"
                        />
                        @if(!empty($this->errors['slug']))
                            <div class="text-red">{{ $this->errors['slug'] }}</div>
                        @endif
                    </div>
                </div>
            @endif
            <div class="border border-gray-200 p-2 flex flex-col justify-start">
                <div class="flex flex-row w-full">
                    <h2 class="flex flex-grow">{{ __('Sprites') }}</h2>
                    @if (user() && user()->is($this->project->user) || !$this->project)
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
                                        @if (user() && user()->is($this->project->user))
                                            {{ __('Edit') }}
                                        @else
                                            {{ __('View') }}
                                        @endif
                                    </button>
                                    @if (user())
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
                <div class="flex flex-row w-full">
                    <h2 class="flex flex-grow">{{ __('Sounds') }}</h2>
                    @if (user() && user()->is($this->project->user) || !$this->project)
                        <button
                            x-on:click="addSound"
                            class="flex flex-shrink"
                        >
                            {{ __('Add') }}
                        </button>
                    @endif
                </div>
                @if ($this->project && $this->project->sounds->count())
                    <div class="flex flex-col w-full">
                        @foreach ($this->project->sounds as $sound)
                            <div
                                wire:key="sound-{{ $sound->id }}"
                                class="flex flex-row w-full"
                            >
                                <span class="flex flex-grow">
                                    {{ $sound->name }}
                                </span>
                                <span class="flex flex-row flex-shrink space-x-2">
                                    <button
                                        x-on:click="editSound('{{ $sound->id }}')"
                                        class="flex"
                                    >
                                        @if (user()->is($this->project->user))
                                            {{ __('Edit') }}
                                        @else
                                            {{ __('View') }}
                                        @endif
                                    </button>
                                    @if (user())
                                        <button
                                            x-on:click="renameSound('{{ $sound->id }}', '{{ $sound->name }}')"
                                            class="flex"
                                        >
                                            {{ __('Rename') }}
                                        </button>
                                        <button
                                            x-on:click="deleteSound('{{ $sound->id }}')"
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
                    {{ __('No sounds') }}
                @endif
            </div>
        </div>
    </div>
    <div class="flex flex-col w-3/4 h-screen">
        @if ($this->editing instanceof \App\Models\Sprite)
            <livewire:sprite-editor
                wire:key="sprite-editor-{{ $this->editing->id }}"
                :sprite="$this->editing"
            />
        @endif
    </div>
</div>
