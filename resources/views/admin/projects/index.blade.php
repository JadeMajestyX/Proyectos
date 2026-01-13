<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Administración de Proyectos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium">Listado</h3>
                    <a href="{{ route('admin.projects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">Nuevo proyecto</a>
                </div>

                @if (session('status'))
                    <div class="mb-4 text-sm text-green-700 bg-green-100 p-3 rounded">{{ session('status') }}</div>
                @endif

                <div class="divide-y">
                    @forelse ($projects as $project)
                        <div class="py-3">
                            <div class="font-semibold">{{ $project->name }}</div>
                            <div class="text-sm text-gray-600">{{ $project->description }}</div>
                            <div class="text-xs text-gray-500 mt-1">Creado por: {{ $project->owner->name }} — {{ $project->created_at->diffForHumans() }}</div>
                        </div>
                    @empty
                        <p class="text-gray-600">Aún no hay proyectos.</p>
                    @endforelse
                </div>

                <div class="mt-4">{{ $projects->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
