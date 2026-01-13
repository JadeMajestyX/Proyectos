<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 text-gray-800 dark:text-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('user.projects.index')" :active="request()->routeIs('user.projects.*')">
                        {{ __('Proyectos') }}
                    </x-nav-link>
                    @if (Auth::user() && Auth::user()->is_admin)
                        <x-nav-link :href="route('admin.projects.index')" :active="request()->routeIs('admin.projects.*')">
                            {{ __('Admin Proyectos') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.tickets.index')" :active="request()->routeIs('admin.tickets.*')">
                            {{ __('Admin Tickets') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <button type="button" onclick="toggleTheme()" class="me-4 p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700" title="Alternar modo oscuro">
                    <svg class="h-5 w-5 hidden dark:block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21.64 13.01A9 9 0 1111 2.36a7 7 0 1010.64 10.65z"/></svg>
                    <svg class="h-5 w-5 dark:hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18a6 6 0 100-12 6 6 0 000 12zm0 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM4.22 19.78a1 1 0 011.42 0l.71.7a1 1 0 11-1.41 1.42l-.72-.71a1 1 0 010-1.41zM1 13a1 1 0 110-2H2a1 1 0 110 2H1zm3.22-8.78a1 1 0 010-1.41l.72-.71A1 1 0 016.35 3.5l-.71.71a1 1 0 11-1.42-1.42zM12 1a1 1 0 011-1h0a1 1 0 010 2h0a1 1 0 01-1-1zm8.78 3.22a1 1 0 011.41 0l.71.72A1 1 0 0121.5 6.35l-.71-.71a1 1 0 010-1.42zM23 11a1 1 0 110 2h-1a1 1 0 110-2h1zm-3.22 8.78a1 1 0 010 1.41l-.71.72a1 1 0 11-1.42-1.42l.71-.71a1 1 0 011.42 0z"/></svg>
                </button>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('user.projects.index')" :active="request()->routeIs('user.projects.*')">
                {{ __('Mis Proyectos') }}
            </x-responsive-nav-link>
            @if (Auth::user() && Auth::user()->is_admin)
                <x-responsive-nav-link :href="route('admin.projects.index')" :active="request()->routeIs('admin.projects.*')">
                    {{ __('Admin Proyectos') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
