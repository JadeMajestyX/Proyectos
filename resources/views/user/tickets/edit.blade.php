<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Editar Ticket — {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 dark:text-gray-100 p-6 shadow sm:rounded-lg">
                <form method="POST" action="{{ route('user.projects.tickets.update', [$project, $ticket]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="title" :value="__('Título')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title',$ticket->title)" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="description" :value="__('Descripción')" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300">{{ old('description',$ticket->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="category" :value="__('Categoría')" />
                        @php($cat = old('category',$ticket->category ?? 'bug'))
                        <select id="category" name="category" class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="bug" @selected($cat==='bug')>Bug</option>
                            <option value="actualizacion" @selected($cat==='actualizacion')>Actualización</option>
                            <option value="novedad" @selected($cat==='novedad')>Novedad</option>
                            <option value="mejora" @selected($cat==='mejora')>Mejora</option>
                            <option value="otro" @selected($cat==='otro')>Otro</option>
                        </select>
                        <x-input-error :messages="$errors->get('category')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="priority" :value="__('Prioridad')" />
                        @php($prio = old('priority',$ticket->priority))
                        <select id="priority" name="priority" class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="low" @selected($prio==='low')>Baja</option>
                            <option value="medium" @selected($prio==='medium')>Media</option>
                            <option value="high" @selected($prio==='high')>Alta</option>
                        </select>
                        <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                    </div>

                    <div class="mt-6">
                        <x-input-label for="media" :value="__('Añadir imágenes/vídeos (opcional)')" />
                        <input id="media" name="media[]" type="file" accept="image/*,video/*" class="mt-1 block w-full" multiple />
                        <x-input-error :messages="$errors->get('media')" class="mt-2" />
                        <x-input-error :messages="collect($errors->get('media.*'))->flatten()->all()" class="mt-2" />
                    </div>

                    @if($ticket->media->count() || $ticket->image_path)
                        <div class="mt-6">
                            <div class="text-sm font-semibold mb-2">Medios actuales</div>
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($ticket->media as $m)
                                    <div class="border rounded p-2">
                                        @if($m->type==='image')
                                            <img src="{{ asset(str_starts_with($m->path,'tickets/') ? $m->path : 'storage/'.$m->path) }}" alt="media" class="w-full h-40 object-cover rounded" />
                                        @else
                                            <video src="{{ asset(str_starts_with($m->path,'tickets/') ? $m->path : 'storage/'.$m->path) }}" controls class="w-full h-40 rounded"></video>
                                        @endif
                                        <div class="mt-1 text-xs text-gray-500 truncate">{{ $m->original_name }}</div>
                                    </div>
                                @endforeach
                                @if($ticket->image_path)
                                    <div class="border rounded p-2">
                                        <img src="{{ asset(str_starts_with($ticket->image_path,'tickets/') ? $ticket->image_path : 'storage/'.$ticket->image_path) }}" alt="imagen" class="w-full h-40 object-cover rounded" />
                                        <div class="mt-1 text-xs text-gray-500 truncate">Imagen legacy</div>
                                    </div>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500 mt-2">(Por ahora no se eliminan medios individuales desde aquí)</div>
                        </div>
                    @endif

                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('user.projects.tickets.show', [$project,$ticket]) }}" onclick="event.preventDefault(); goBackOr(this.href)" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-100 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">Cancelar</a>
                        <div>
                            <x-primary-button class="ms-3">Guardar cambios</x-primary-button>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('user.projects.tickets.destroy', [$project,$ticket]) }}" class="mt-4" onsubmit="return confirm('¿Eliminar este ticket? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <x-danger-button>Eliminar Ticket</x-danger-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
