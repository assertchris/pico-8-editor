<?php

namespace App\Http\Responders\Auth;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class HandleGithubResponseResponder
{
    public function __invoke(): RedirectResponse
    {
        $data = Socialite::driver('github')->user();

        $user = User::firstOrCreate([
            'email' => $data->email,
            'provider_name' => 'github',
        ], [
            'name' => $data->nickname,
        ]);

        $user->name = $data->nickname;
        $user->provider_token = $data->token;
        $user->save();

        auth()->login($user);

        return redirect()->route('pages.dashboard');
    }
}
