<x-guest-layout>
    <div>
        <h1 class="text-2xl font-semibold text-center">Accede a la plataforma de tickets</h1>
        <p class="mt-2 text-center text-sm text-gray-600">Inicia sesión o crea tu cuenta para gestionar tickets de proyectos.</p>
    </div>

    <div class="mt-6">
        <div class="flex gap-2 bg-gray-100 p-1 rounded-md">
            <button id="tab-login" class="flex-1 py-2 text-sm font-medium rounded-md bg-white shadow">Iniciar sesión</button>
            <button id="tab-register" class="flex-1 py-2 text-sm font-medium rounded-md text-gray-600">Crear cuenta</button>
        </div>

        <div class="mt-4">
            <!-- Login form -->
            <form id="panel-login" method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Correo electrónico')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Contraseña')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">Recuérdame</span>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>

                    <x-primary-button class="ms-3">
                        Entrar
                    </x-primary-button>
                </div>
            </form>

            <!-- Register form -->
            <form id="panel-register" method="POST" action="{{ route('register') }}" class="hidden">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Nombre')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="reg_email" :value="__('Correo electrónico')" />
                    <x-text-input id="reg_email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="reg_password" :value="__('Contraseña')" />
                    <x-text-input id="reg_password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button>
                        Crear cuenta
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Tabs toggling without external dependencies
        const tabLogin = document.getElementById('tab-login');
        const tabRegister = document.getElementById('tab-register');
        const panelLogin = document.getElementById('panel-login');
        const panelRegister = document.getElementById('panel-register');

        function activate(tab) {
            const activeClasses = ['bg-white','shadow','text-gray-900'];
            const inactiveClasses = ['text-gray-600'];

            if (tab === 'login') {
                panelLogin.classList.remove('hidden');
                panelRegister.classList.add('hidden');

                tabLogin.classList.add(...activeClasses);
                tabLogin.classList.remove(...inactiveClasses);
                tabRegister.classList.remove(...activeClasses);
                tabRegister.classList.add(...inactiveClasses);
            } else {
                panelRegister.classList.remove('hidden');
                panelLogin.classList.add('hidden');

                tabRegister.classList.add(...activeClasses);
                tabRegister.classList.remove(...inactiveClasses);
                tabLogin.classList.remove(...activeClasses);
                tabLogin.classList.add(...inactiveClasses);
            }
        }

        tabLogin?.addEventListener('click', () => activate('login'));
        tabRegister?.addEventListener('click', () => activate('register'));

        // Si hubo errores de validación al registrar, muestra la pestaña de registro
        @if ($errors->has('name') || $errors->has('password_confirmation'))
            activate('register');
        @else
            activate('login');
        @endif
    </script>
</x-guest-layout>
