<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nuevo Ticket — {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <form method="POST" action="{{ route('user.projects.tickets.store', $project) }}" enctype="multipart/form-data">
                    @csrf

                    <div>
                        <x-input-label for="title" :value="__('Título')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="description" :value="__('Descripción')" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="priority" :value="__('Prioridad')" />
                        <select id="priority" name="priority" class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="low" @selected(old('priority')==='low')>Baja</option>
                            <option value="medium" @selected(old('priority','medium')==='medium')>Media</option>
                            <option value="high" @selected(old('priority')==='high')>Alta</option>
                        </select>
                        <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="image" :value="__('Imagen (opcional)')" />
                        <input id="image" name="image" type="file" accept="image/*" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button as="a" href="{{ route('user.projects.show', $project) }}">Cancelar</x-secondary-button>
                        <x-primary-button class="ms-3">Crear Ticket</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
