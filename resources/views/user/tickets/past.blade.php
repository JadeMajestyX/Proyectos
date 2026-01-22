<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Tickets pasados — {{ $project->name }}
            </h2>
            <a href="{{ route('user.projects.show', $project) }}"
               class="inline-flex items-center px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-100 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                Volver al proyecto
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 dark:text-gray-100 p-6 shadow sm:rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold">Completados</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Aquí se muestran los tickets con estado "Completado".</p>
                    </div>
                    <a href="{{ route('user.projects.tickets.create', $project) }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md text-sm">Nuevo Ticket</a>
                </div>

                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($tickets as $ticket)
                        <a href="{{ route('user.projects.tickets.show', [$project, $ticket]) }}"
                           class="block py-3 pl-3 border-l-4 hover:bg-gray-50 dark:hover:bg-gray-700 rounded"
                           style="@if($ticket->priority==='high') border-color: rgb(239, 68, 68); @elseif($ticket->priority==='medium') border-color: rgb(217, 119, 6); @else border-color: rgb(34, 197, 94); @endif">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="font-medium">{{ $ticket->title }}
                                        <span class="ml-2 text-xs px-2 py-0.5 rounded-full"
                                              style="@if($ticket->priority==='high') background-color: rgb(254, 226, 226); color: rgb(220, 38, 38); @elseif($ticket->priority==='medium') background-color: rgb(254, 243, 224); color: rgb(180, 83, 9); @else background-color: rgb(220, 252, 231); color: rgb(22, 163, 74); @endif">
                                            {{ ucfirst($ticket->priority) }}
                                        </span>
                                        @if($ticket->category)
                                            <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">{{ ucfirst($ticket->category) }}</span>
                                        @endif
                                        <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">Completado</span>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Creado por {{ $ticket->creator->name }}
                                        @if($ticket->assignee) — Asignado a {{ $ticket->assignee->name }} @endif
                                        — Actualizado {{ $ticket->updated_at->diffForHumans() }}
                                    </div>
                                </div>
                                @if($ticket->image_path)
                                    <img src="{{ asset('storage/'.$ticket->image_path) }}" alt="img" class="w-14 h-14 object-cover rounded border cursor-zoom-in" data-preview/>
                                @endif
                            </div>
                        </a>
                    @empty
                        <p class="text-gray-600 dark:text-gray-300">No hay tickets completados en este proyecto.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
