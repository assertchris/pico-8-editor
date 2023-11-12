<?php

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;

middleware('auth');
name('pages.dashboard');

?>
<x-layout>
    <livewire:navigation />
    <livewire:project-list />
</x-layout>
