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

if (! function_exists('pixel_address')) {
    function pixel_address(int $size, int $x, int $y): int
    {
        return ($y * $size) + $x;
    }
}
