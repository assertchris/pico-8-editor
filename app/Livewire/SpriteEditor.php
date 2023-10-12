<?php

namespace App\Livewire;

use App\Models\Sprite;
use Exception;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SpriteEditor extends Component
{
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

    public int $size = 8;

    public function mount(): void
    {
        for ($y = 0; $y < $this->size; $y++) {
            for ($x = 0; $x < $this->size; $x++) {
                $address = $this->address($x, $y);
                $this->pixels[$address] = $this->sprite->pixels[$address] ?? -1;
            }
        }
    }

    private function address(int $x, int $y): int
    {
        return ($y * $this->size) + $x;
    }

    public function updating(): void
    {
        if (!session()->get('unlocked')) {
            throw new Exception;
        }

        $this->sprite->pixels = $this->pixels;
        $this->sprite->save();
    }

    public function render(): View
    {
        return view('livewire.sprite-editor');
    }
}
