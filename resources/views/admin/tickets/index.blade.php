<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Tickets (Administración)
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 dark:text-gray-100 p-6 shadow sm:rounded-lg">
                @if (session('status'))
                    <div class="mb-4 text-sm text-green-700 bg-green-100 p-3 rounded">{{ session('status') }}</div>
                @endif

                @php
                    $tab = $tab ?? request()->query('tab', 'open');
                    $counts = $counts ?? [];
                    $tabs = [
                        'unassigned' => 'Sin asignar',
                        'assigned' => 'Asignados',
                        'done' => 'Completos',
                        'in_progress' => 'En progreso',
                        'open' => 'Abiertos',
                    ];
                @endphp

                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($tabs as $key => $label)
                        <a href="{{ route('admin.tickets.index', ['tab' => $key]) }}"
                           class="inline-flex items-center px-3 py-2 rounded-md text-sm border transition
                                {{ $tab === $key ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-100 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600' }}">
                            {{ $label }}
                            <span class="ml-2 text-xs px-2 py-0.5 rounded-full {{ $tab === $key ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-700' }}">
                                {{ $counts[$key] ?? 0 }}
                            </span>
                        </a>
                    @endforeach
                </div>

                {{-- Leyenda de prioridades --}}
                <div class="flex items-center gap-4 text-xs text-gray-600 mb-4">
                    <div class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full bg-red-500"></span> Alta</div>
                    <div class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full bg-amber-500"></span> Media</div>
                    <div class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full bg-green-500"></span> Baja</div>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($tickets as $ticket)
                        <div class="py-3 pl-3 border-l-4" style="@if($ticket->priority==='high') border-color: rgb(239, 68, 68); @elseif($ticket->priority==='medium') border-color: rgb(217, 119, 6); @else border-color: rgb(34, 197, 94); @endif">
                            <div class="flex justify-between">
                                <div>
                                    <div class="font-semibold">{{ $ticket->title }}
                                        <span class="ml-2 text-xs px-2 py-0.5 rounded-full"
                                              style="@if($ticket->priority==='high') background-color: rgb(254, 226, 226); color: rgb(220, 38, 38); @elseif($ticket->priority==='medium') background-color: rgb(254, 243, 224); color: rgb(180, 83, 9); @else background-color: rgb(220, 252, 231); color: rgb(22, 163, 74); @endif">
                                            {{ ucfirst($ticket->priority) }}</span>
                                        @if($ticket->category)
                                            <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">{{ ucfirst($ticket->category) }}</span>
                                        @endif
                                        <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">{{ str_replace('_',' ', ucfirst($ticket->status)) }}</span>
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300">Proyecto: {{ $ticket->project->name }} — Creador: {{ $ticket->creator->name }}</div>
                                    @if($ticket->assignee)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Asignado a: {{ $ticket->assignee->name }}</div>
                                    @endif
                                </div>
                                <div class="space-x-3 flex items-center">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-gray-700 hover:underline text-sm">Ver</a>
                                    <a href="{{ route('admin.tickets.edit', $ticket) }}" class="text-indigo-600 hover:underline text-sm">Asignar / Editar</a>
                                    <form method="POST" action="{{ route('admin.tickets.destroy', $ticket) }}" onsubmit="return confirm('¿Eliminar este ticket?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-sm">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600">No hay tickets.</p>
                    @endforelse
                </div>

                <div class="mt-4">{{ $tickets->appends(['tab' => $tab])->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
