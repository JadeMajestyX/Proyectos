<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nuevo Proyecto
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('user.projects.store') }}">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Nombre del proyecto')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="description" :value="__('Descripción')" />
                        <textarea id="description" name="description" class="mt-1 block w-full rounded-md border-gray-300" rows="4">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button as="a" href="{{ route('user.projects.index') }}">Cancelar</x-secondary-button>
                        <x-primary-button class="ms-3">Crear</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
