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
<ul class="flex flex-col [&_li]:flex space-y-4 px-4 py-3">
    @foreach ($projects as $project)
        <li>
            <a
                href="{{ $project->url }}"
                class="flex w-full underline text-blue-500"
            >
                {{ $project->name }}
            </a>
        </li>
    @endforeach
</ul>
