<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public function with(): array
    {
        return [
            'projects' => user()->projects()->paginate(),
        ];
    }
};

?>
<div class="flex flex-col">
    @foreach ($projects as $project)
        <a
            href="{{ $project->url }}"
            class="flex w-full"
        >
            {{ $project->name }}
        </a>
    @endforeach
</div>
