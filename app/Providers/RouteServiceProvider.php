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

        Route::bind('asset', function (string $value, \Illuminate\Routing\Route $route) {
            $project_id = request()->project_id;

            if ($route->parameter('type') === 'spr') {
                if ($asset = Sprite::where('slug', '=', $value)->where('project_id', '=', $project_id)->first()) {
                    return $asset;
                }

                if ($asset = Sprite::where('id', '=', $value)->where('project_id', '=', $project_id)->first()) {
                    return $asset;
                }
            }

            if ($route->parameter('type') === 'sfx') {
                if ($asset = Sound::where('slug', '=', $value)->where('project_id', '=', $project_id)->first()) {
                    return $asset;
                }

                if ($asset = Sound::where('id', '=', $value)->where('project_id', '=', $project_id)->first()) {
                    return $asset;
                }
            }

            logger('asset not found with ' . $value);

            abort(404);
        });

        Route::bind('project', function (string $value) {
            $user_id = request()->user_id;

            if ($project = Project::where('slug', '=', $value)->where('user_id', '=', $user_id)->first()) {
                request()->project_id = $project->id;

                return $project;
            }

            if ($project = Project::where('id', '=', $value)->where('user_id', '=', $user_id)->first()) {
                request()->project_id = $project->id;

                return $project;
            }

            logger('project not found with '.$value);

            abort(404);
        });

        Route::bind('user', function (string $value) {
            if ($user = User::where('slug', '=', $value)->first()) {
                request()->user_id = $user->id;

                return $user;
            }

            if ($user = User::where('id', '=', $value)->first()) {
                request()->user_id = $user->id;

                return $user;
            }

            logger('user not found with '.$value);

            abort(404);
        });
    }
}
