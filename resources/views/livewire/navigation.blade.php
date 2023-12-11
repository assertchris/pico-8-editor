<?php

use App\Models\Project;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(): void
    {
        $user = user();
        $user->provider_token = null;
        $user->save();

        auth()->logout();

        $this->redirectRoute('pages.index');
    }

    public function create(): void
    {
        $project = Project::create([
            'name' => 'Untitled project',
            'user_id' => user()->id,
        ]);

        $this->redirectRoute('projects.show-project', ['user' => user()->segment, 'project' => $project->segment]);
    }
};

?>
<nav>
    <ul class="flex flex-row [&_li]:flex space-x-4 px-4 py-3">
        @guest
            <li>
                <a
                    href="{{ route('auth.redirect-to-github') }}"
                    class="underline text-blue-500"
                >
                    {{ __('Login with GitHub') }}
                </a>
            </li>
        @endguest
        @auth
            <li>
                <a
                    href="{{ route('pages.dashboard') }}"
                    class="underline text-blue-500"
                >
                    {{ __('Dashboard') }}
                </a>
            </li>
            <li>
                <a
                    href="#"
                    wire:click.prevent="create"
                    class="underline text-blue-500"
                >
                    {{ __('Create project') }}
                </a>
            </li>
            <li>
                <a
                    href="#"
                    wire:click.prevent="logout"
                    class="underline text-blue-500"
                >
                    {{ __('Logout') }}
                </a>
            </li>
        @endauth
    </ul>
</nav>
