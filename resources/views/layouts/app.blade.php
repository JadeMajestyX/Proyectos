<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 dark:text-gray-100 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <!-- Image Preview Modal -->
        <div id="imageModal" class="fixed inset-0 z-50 hidden">
            <div class="absolute inset-0 bg-black/80" data-modal-close></div>
            <div class="relative w-full h-full flex items-center justify-center p-4">
                <img id="imageModalImg" src="" alt="preview" class="max-h-[90vh] max-w-[95vw] rounded shadow-lg" />
                <button type="button" class="absolute top-4 right-4 text-white bg-black/50 hover:bg-black/70 rounded-full p-2" title="Cerrar" data-modal-close>
                    ✕
                </button>
            </div>
        </div>
        <script>
            (function(){
                const pref = localStorage.getItem('theme');
                if (pref === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
                window.toggleTheme = function(){
                    const isDark = document.documentElement.classList.toggle('dark');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                }
                window.goBackOr = function(url){
                    if (document.referrer && document.referrer !== window.location.href) {
                        history.back();
                    } else {
                        window.location.href = url;
                    }
                }

                // Simple Image Preview Modal
                const modal = document.getElementById('imageModal');
                const modalImg = document.getElementById('imageModalImg');
                function openModal(src){
                    modalImg.src = src;
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                }
                function closeModal(){
                    modal.classList.add('hidden');
                    modalImg.src = '';
                    document.body.classList.remove('overflow-hidden');
                }
                document.addEventListener('click', function(e){
                    const target = e.target;
                    if (target instanceof HTMLElement && target.matches('img[data-preview]')){
                        e.preventDefault();
                        const src = target.getAttribute('data-src') || target.getAttribute('src');
                        if (src) openModal(src);
                    }
                    if (target instanceof HTMLElement && target.closest('[data-modal-close]')){
                        closeModal();
                    }
                });
                document.addEventListener('keydown', function(e){
                    if (e.key === 'Escape' && !modal.classList.contains('hidden')){
                        closeModal();
                    }
                });
            })();
        </script>
    </body>
</html>
