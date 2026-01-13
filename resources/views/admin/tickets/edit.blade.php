<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Editar Ticket
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 dark:text-gray-100 p-6 shadow sm:rounded-lg">
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

                    <div class="mt-4 grid grid-cols-3 gap-4">
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
                            <x-input-label for="category" :value="__('Categoría')" />
                            <select id="category" name="category" class="mt-1 block w-full rounded-md border-gray-300">
                                @php($cat = $ticket->category ?? 'bug')
                                <option value="bug" @selected($cat==='bug')>Bug</option>
                                <option value="actualizacion" @selected($cat==='actualizacion')>Actualización</option>
                                <option value="novedad" @selected($cat==='novedad')>Novedad</option>
                                <option value="mejora" @selected($cat==='mejora')>Mejora</option>
                                <option value="otro" @selected($cat==='otro')>Otro</option>
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
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
                        <a href="{{ route('admin.tickets.index') }}" onclick="event.preventDefault(); goBackOr(this.href)" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-100 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">Cancelar</a>
                        <x-primary-button class="ms-3">Guardar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
