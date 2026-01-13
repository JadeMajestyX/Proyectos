<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Mis Proyectos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 dark:text-gray-100 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium">Proyectos disponibles</h3>
                </div>

                @if (session('status'))
                    <div class="mb-4 text-sm text-green-700 bg-green-100 p-3 rounded">{{ session('status') }}</div>
                @endif

                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($projects as $project)
                        <a href="{{ route('user.projects.show', $project) }}" class="block py-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded">
                            <div class="font-semibold">{{ $project->name }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">{{ $project->description }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Creado: {{ $project->created_at->diffForHumans() }}</div>
                        </a>
                    @empty
                        <p class="text-gray-600">Aún no hay proyectos disponibles.</p>
                    @endforelse
                </div>

                <div class="mt-4">{{ $projects->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
