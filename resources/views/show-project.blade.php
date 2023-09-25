<x-layout>
  @if ($project->exists)
    <livewire:editor :project="$project" />
  @else
    <livewire:editor />
  @endif
</x-layout>
