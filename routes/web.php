<?php

use App\Http\Responders\Auth\HandleGithubResponseResponder;
use App\Http\Responders\Auth\RedirectToGithubResponder;
use App\Http\Responders\Projects\Assets\ShowAssetDataResponder;
use App\Http\Responders\Projects\ShowProjectResponder;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/auth/redirect-to-github', RedirectToGithubResponder::class)
    ->name('auth.redirect-to-github');

Route::get('/auth/handle-github-response', HandleGithubResponseResponder::class)
    ->name('auth.handle-github-response');

Route::get('/{user}/{project}/{asset}.json', ShowAssetDataResponder::class)
    ->name('projects.assets.show-data');

Route::get('/{user}/{project}', ShowProjectResponder::class)
    ->name('projects.show-project')
    ->where('project', '\b(?!dashboard\b)[0-9a-zA-Z-]+\b');
