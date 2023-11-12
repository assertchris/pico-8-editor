<?php

use function \Laravel\Folio\name;

name('pages.show-project');

?>
<x-layout>
    <livewire:navigation />
    <livewire:editor :project="$project" />
</x-layout>
