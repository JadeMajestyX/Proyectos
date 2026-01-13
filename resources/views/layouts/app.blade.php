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

        <!-- Bootstrap CSS (solo para modal) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

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
        <!-- Bootstrap Image Preview Modal -->
        <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content bg-transparent border-0">
                    <div class="modal-body p-0 d-flex justify-content-center">
                        <img id="imagePreviewModalImg" src="" alt="preview" class="img-fluid rounded" style="max-height:80vh;"/>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
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
                // Bootstrap Image Preview Modal
                const modalEl = document.getElementById('imagePreviewModal');
                const modalImg = document.getElementById('imagePreviewModalImg');
                let bsModal = null;
                if (window.bootstrap && modalEl) {
                    bsModal = new bootstrap.Modal(modalEl, { keyboard: true });
                }
                document.addEventListener('click', function(e){
                    const target = e.target;
                    if (target instanceof HTMLElement && target.matches('img[data-preview]')){
                        e.preventDefault();
                        const src = target.getAttribute('data-src') || target.getAttribute('src');
                        if (src && modalImg && bsModal){
                            modalImg.src = src;
                            bsModal.show();
                        }
                    }
                });
            })();
        </script>
    </body>
</html>
