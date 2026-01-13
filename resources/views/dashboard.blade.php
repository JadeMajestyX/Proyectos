<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 dark:text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 dark:text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-2">Mis tickets asignados</h3>
                    <div class="text-xs text-gray-600 dark:text-gray-300 mb-3 flex gap-4">
                        <span class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full bg-red-500"></span> Alta</span>
                        <span class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full bg-amber-500"></span> Media</span>
                        <span class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full bg-green-500"></span> Baja</span>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($assigned ?? [] as $ticket)
                            <a href="{{ route('user.projects.tickets.show', [$ticket->project, $ticket]) }}" class="block py-3 pl-3 border-l-4 hover:bg-gray-50 dark:hover:bg-gray-700 rounded"
                               @class([
                                    'border-red-500' => $ticket->priority==='high',
                                    'border-amber-500' => $ticket->priority==='medium',
                                    'border-green-500' => $ticket->priority==='low',
                               ])>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="font-medium">{{ $ticket->title }}
                                            <span class="ml-2 text-xs px-2 py-0.5 rounded-full" @class([
                                                'bg-red-100 text-red-700' => $ticket->priority==='high',
                                                'bg-amber-100 text-amber-700' => $ticket->priority==='medium',
                                                'bg-green-100 text-green-700' => $ticket->priority==='low',
                                            ])>{{ ucfirst($ticket->priority) }}</span>
                                            <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">{{ str_replace('_',' ', ucfirst($ticket->status)) }}</span>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Proyecto: {{ $ticket->project->name }} — {{ $ticket->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <p class="text-sm text-gray-600 dark:text-gray-300">No tienes tickets asignados.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 dark:text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-2">Tickets creados por mí</h3>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($created ?? [] as $ticket)
                            <a href="{{ route('user.projects.tickets.show', [$ticket->project, $ticket]) }}" class="block py-3 pl-3 border-l-4 hover:bg-gray-50 dark:hover:bg-gray-700 rounded"
                               @class([
                                    'border-red-500' => $ticket->priority==='high',
                                    'border-amber-500' => $ticket->priority==='medium',
                                    'border-green-500' => $ticket->priority==='low',
                               ])>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="font-medium">{{ $ticket->title }}
                                            <span class="ml-2 text-xs px-2 py-0.5 rounded-full" @class([
                                                'bg-red-100 text-red-700' => $ticket->priority==='high',
                                                'bg-amber-100 text-amber-700' => $ticket->priority==='medium',
                                                'bg-green-100 text-green-700' => $ticket->priority==='low',
                                            ])>{{ ucfirst($ticket->priority) }}</span>
                                            <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">{{ str_replace('_',' ', ucfirst($ticket->status)) }}</span>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Proyecto: {{ $ticket->project->name }} — {{ $ticket->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <p class="text-sm text-gray-600 dark:text-gray-300">Aún no has creado tickets.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
