<?php

namespace App\Http\Responders\Projects;

use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\View\View;

class ShowProjectResponder
{
    public function __invoke(User $user, Project $project): View
    {
        return view('projects.show-project', [
            'project' => $project,
        ]);
    }
}
