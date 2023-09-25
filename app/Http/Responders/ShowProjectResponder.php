<?php

namespace App\Http\Responders;

use App\Models\Project;
use Illuminate\Contracts\View\View;

class ShowProjectResponder
{
    public function __invoke(Project $project): View
    {
        return view('show-project', [
            'project' => $project,
        ]);
    }
}
