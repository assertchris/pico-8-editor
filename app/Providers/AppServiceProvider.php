<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Torchlight\Middleware\RenderTorchlight;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (env('TORCHLIGHT_ENABLED')) {
            $this->app['router']->pushMiddlewareToGroup('web', RenderTorchlight::class);
        }
    }
}
