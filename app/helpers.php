<?php

use App\Models\User;

if (! function_exists('user')) {
    function user(): ?User
    {
        /** @var User $user */
        $user = auth()->user();

        return $user;
    }
}
