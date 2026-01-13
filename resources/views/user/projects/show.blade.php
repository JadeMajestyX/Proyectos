<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Proyecto: {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <div class="text-gray-700">{{ $project->description }}</div>
                <div class="text-xs text-gray-500 mt-2">Creado por: {{ $project->owner->name }} — {{ $project->created_at->diffForHumans() }}</div>
            </div>

            <div class="bg-white p-6 shadow sm:rounded-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Tickets</h3>
                    <a href="{{ route('user.projects.tickets.create', $project) }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md text-sm">Nuevo Ticket</a>
                </div>

                {{-- Leyenda de prioridades --}}
                <div class="flex items-center gap-4 text-xs text-gray-600 mb-4">
                    <div class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full bg-red-500"></span> Alta</div>
                    <div class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full bg-amber-500"></span> Media</div>
                    <div class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full bg-green-500"></span> Baja</div>
                </div>

                @if($myAssigned->count())
                    <h4 class="text-sm font-semibold text-gray-800 mb-2">Mis tickets asignados</h4>
                    <div class="divide-y mb-6">
                        @foreach($myAssigned as $ticket)
                            <a href="{{ route('user.projects.tickets.show', [$project, $ticket]) }}" class="block py-3 pl-3 border-l-4 hover:bg-gray-50 rounded" @class([
                                'border-red-500' => $ticket->priority==='high',
                                'border-amber-500' => $ticket->priority==='medium',
                                'border-green-500' => $ticket->priority==='low',
                            ])>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="font-medium">{{ $ticket->title }}
                                            <span class="ml-2 text-xs px-2 py-0.5 rounded-full"
                                                  @class([
                                                    'bg-red-100 text-red-700' => $ticket->priority==='high',
                                                    'bg-amber-100 text-amber-700' => $ticket->priority==='medium',
                                                    'bg-green-100 text-green-700' => $ticket->priority==='low',
                                                  ])>
                                                {{ ucfirst($ticket->priority) }}
                                            </span>
                                            <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">{{ str_replace('_',' ', ucfirst($ticket->status)) }}</span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">Creado por {{ $ticket->creator->name }} — {{ $ticket->created_at->diffForHumans() }}</div>
                                    </div>
                                    @if($ticket->image_path)
                                        <img src="{{ asset('storage/'.$ticket->image_path) }}" alt="img" class="w-14 h-14 object-cover rounded border"/>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif

                <h4 class="text-sm font-semibold text-gray-800 mb-2">Todos los tickets</h4>
                <div class="divide-y">
                    @forelse($tickets as $ticket)
                        <a href="{{ route('user.projects.tickets.show', [$project, $ticket]) }}" class="block py-3 pl-3 border-l-4 hover:bg-gray-50 rounded" @class([
                            'border-red-500' => $ticket->priority==='high',
                            'border-amber-500' => $ticket->priority==='medium',
                            'border-green-500' => $ticket->priority==='low',
                        ])>
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="font-medium">{{ $ticket->title }}
                                        <span class="ml-2 text-xs px-2 py-0.5 rounded-full"
                                              @class([
                                                'bg-red-100 text-red-700' => $ticket->priority==='high',
                                                'bg-amber-100 text-amber-700' => $ticket->priority==='medium',
                                                'bg-green-100 text-green-700' => $ticket->priority==='low',
                                              ])>
                                            {{ ucfirst($ticket->priority) }}
                                        </span>
                                        <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">{{ str_replace('_',' ', ucfirst($ticket->status)) }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">Creado por {{ $ticket->creator->name }} @if($ticket->assignee) — Asignado a {{ $ticket->assignee->name }} @endif — {{ $ticket->created_at->diffForHumans() }}</div>
                                </div>
                                @if($ticket->image_path)
                                    <img src="{{ asset('storage/'.$ticket->image_path) }}" alt="img" class="w-14 h-14 object-cover rounded border"/>
                                @endif
                            </div>
                        </a>
                    @empty
                        <p class="text-gray-600">Aún no hay tickets en este proyecto.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
