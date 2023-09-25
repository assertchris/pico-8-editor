<?php

namespace App\Livewire;

use App\Models\Sprite;
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

    public array $pixels = [];

    public array $coloredPixels = [];

    public string|null $selectedColor;

    public Sprite $sprite;

    public function load(): void
    {
        if ($this->sprite->pixels) {
            $this->pixels = $this->sprite->pixels;

            for ($y = 0; $y < 8; $y++) {
                for ($x = 0; $x < 8; $x++) {
                    $index = $this->pixels[$this->address($x, $y)];

                    if ($index == -1) {
                        $color = 'transparent';
                    } else {
                        $color = $this->colors[$index];
                    }

                    $this->coloredPixels[$y][$x] = $color;
                }
            }
        } else {
            for ($y = 0; $y < 8; $y++) {
                for ($x = 0; $x < 8; $x++) {
                    $this->erase($x, $y, save: false);
                }
            }
        }
    }

    public function paint(int $x, int $y): void
    {
        if (!session()->get('unlocked')) {
            return;
        }

        $this->pixels[$this->address($x, $y)] = array_search($this->selectedColor, $this->colors);

        if (!isset($this->coloredPixels[$y])) {
            $this->coloredPixels[$y] = [];
        }

        $this->coloredPixels[$y][$x] = $this->selectedColor;

        $this->save();
    }

    private function save(): void
    {
        $this->sprite->pixels = $this->pixels;
        $this->sprite->save();
    }

    public function erase(int $x, int $y, $save = true): void
    {
        if (!session()->get('unlocked')) {
            return;
        }

        $this->pixels[$this->address($x, $y)] = -1;

        if (!isset($this->coloredPixels[$y])) {
            $this->coloredPixels[$y] = [];
        }

        $this->coloredPixels[$y][$x] = 'transparent';

        if ($save) {
            $this->save();
        }
    }

    private function address(int $x, int $y): int
    {
        return ($y * 8) + $x;
    }

    public function render(): View
    {
        return view('livewire.sprite-editor');
    }
}
