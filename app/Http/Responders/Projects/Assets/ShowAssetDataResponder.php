<?php

namespace App\Http\Responders\Projects\Assets;

use App\Models\Project;
use App\Models\Sound;
use App\Models\Sprite;
use App\Models\User;

class ShowAssetDataResponder
{
    public function __invoke(User $user, Project $project, Sprite|Sound $asset): array
    {
        if ($asset instanceof Sprite) {
            return [
                $asset->pixels,
                $asset->flags,
            ];
        }

        return [
            $asset->length,
            ...$asset->notes,
        ];
    }
}
