<?php

use App\Http\Responders\ShowProjectAssetDataResponder;
use App\Http\Responders\ShowProjectResponder;
use Illuminate\Support\Facades\Route;

Route::get('/{project?}', ShowProjectResponder::class)
    ->name('show-project');
Route::get('/{project?}/{asset}.json', ShowProjectAssetDataResponder::class)
    ->name('show-project-asset-data');
