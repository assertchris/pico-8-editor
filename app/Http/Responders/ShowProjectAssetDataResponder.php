<?php

namespace App\Http\Responders;

use App\Models\Project;
use App\Models\Sound;
use App\Models\Sprite;

class ShowProjectAssetDataResponder
{
    public function __invoke(Project $project, Sprite|Sound $asset)
    {
        if ($asset instanceof Sprite) {
            return $asset->pixels;
        }

        return [
            $asset->length,
            ...$asset->notes,
        ];
    }
}
