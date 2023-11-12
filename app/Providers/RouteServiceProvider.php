<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\Sound;
use App\Models\Sprite;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/dashboard';

    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        Route::bind('asset', function (string $value) {
            if ($asset = Sprite::where('slug', '=', $value)->first()) {
                return $asset;
            }

            if ($asset = Sound::where('slug', '=', $value)->first()) {
                return $asset;
            }

            if ($asset = Sprite::where('id', '=', $value)->first()) {
                return $asset;
            }

            if ($asset = Sound::where('id', '=', $value)->first()) {
                return $asset;
            }

            logger('asset not found with ' . $value);

            abort(404);
        });

        Route::bind('project', function (string $value) {
            if ($project = Project::where('slug', '=', $value)->first()) {
                return $project;
            }

            if ($project = Project::where('id', '=', $value)->first()) {
                return $project;
            }

            logger('project not found with ' . $value);

            abort(404);
        });

        Route::bind('user', function (string $value) {
            if ($project = User::where('slug', '=', $value)->first()) {
                return $project;
            }

            if ($project = User::where('id', '=', $value)->first()) {
                return $project;
            }

            logger('user not found with ' . $value);

            abort(404);
        });
    }
}
