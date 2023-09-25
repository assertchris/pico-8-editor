<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Sound;
use App\Models\Sprite;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Editor extends Component
{
    public Project|null $project = null;

    public Sprite|Sound|null $editing = null;

    public function addSprite(string $name): void
    {
        $project = $this->getProject();

        $project->sprites()->create([
            'name' => $name,
        ]);

        if ($project->wasRecentlyCreated) {
            $this->redirectRoute('show-project', $project);
        }
    }

    private function getProject(): Project
    {
        if ($this->project) {
            return $this->project;
        }

        return Project::create([
            'name' => 'Untitled Project',
        ]);
    }

    public function addSound(string $name): void
    {
        $project = $this->getProject();

        $project->sounds()->create([
            'name' => $name,
        ]);

        if ($project->wasRecentlyCreated) {
            $this->redirectRoute('show-project', $project);
        }
    }

    public function editSprite(string $id): void
    {
        $this->editing = Sprite::findOrFail($id);
    }

    public function deleteSprite(string $id): void
    {
        Sprite::findOrFail($id)->delete();
        $this->redirectRoute('show-project', $this->project);
    }

    public function renameSprite(string $id, string $name): void
    {
        $sprite = Sprite::findOrFail($id);
        $sprite->name = $name;
        $sprite->save();
    }

    public function editSound(string $id): void
    {
        $this->editing = Sound::findOrFail($id);
    }

    public function setCode(string $code): void
    {
        $this->project->code = $code;
        $this->project->save();

        session()->put('unlocked', true);
    }

    public function unlock(string $code): void
    {
        session()->put('unlocked', $this->project->code == $code);
        $this->redirectRoute('show-project', $this->project);
    }

    public function render(): View
    {
        return view('livewire.editor');
    }
}
