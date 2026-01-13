<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Ticket
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <div class="mb-4">
                    <div class="font-semibold">{{ $ticket->title }}</div>
                    <div class="text-sm text-gray-600">Proyecto: {{ $ticket->project->name }} | Creador: {{ $ticket->creator->name }}</div>
                    @if($ticket->image_path)
                        <div class="mt-3">
                            <img src="{{ asset('storage/'.$ticket->image_path) }}" alt="Imagen del ticket" class="max-h-64 rounded border"/>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('admin.tickets.update', $ticket) }}">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="assigned_to" :value="__('Asignar a')" />
                        <select id="assigned_to" name="assigned_to" class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="">— Sin asignar —</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(optional($ticket->assignee)->id === $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('assigned_to')" class="mt-2" />
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="status" :value="__('Estado')" />
                            <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300">
                                @foreach(['open' => 'Abierto', 'in_progress' => 'En progreso', 'done' => 'Finalizado'] as $value => $label)
                                    <option value="{{ $value }}" @selected($ticket->status === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="priority" :value="__('Prioridad')" />
                            <select id="priority" name="priority" class="mt-1 block w-full rounded-md border-gray-300">
                                @foreach(['low' => 'Baja', 'medium' => 'Media', 'high' => 'Alta'] as $value => $label)
                                    <option value="{{ $value }}" @selected($ticket->priority === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button as="a" href="{{ route('admin.tickets.index') }}">Cancelar</x-secondary-button>
                        <x-primary-button class="ms-3">Guardar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
