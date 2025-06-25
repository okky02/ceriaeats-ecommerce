<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('img/Logo-Ceria-2.png') }}" type="image/png">
    <title>{{ config('app.name', 'CeriaEats') }}</title>

    <!-- js, css -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-gray-800">

    @include('landing.partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('landing.partials.footer')

    <!-- Back to Top Button -->
    <a href="#" id="backToTop"
        class="opacity-0 scale-90 pointer-events-none fixed bottom-5 right-5 z-50 w-12 h-12 rounded-full bg-pink-500 hover:bg-yellow-500 flex items-center justify-center text-white shadow-lg transition-all duration-300 ease-in-out">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- Loading Spinner -->
    <div id="pageLoader"
        class="fixed inset-0 bg-pink-300 bg-opacity-80 flex flex-col items-center justify-center z-50 hidden ">
        <div class="relative w-28 h-28 mb-4">
            <div class="absolute inset-0 border-4 border-pink-500 border-t-transparent rounded-full animate-spin">
            </div>
            <img src="{{ asset('img/Logo-Ceria-2.png') }}" alt="Logo" class="absolute inset-0 m-auto w-20 h-20 object-contain">
        </div>
    </div>

    <script>
        // Scroll
        const backToTop = document.getElementById('backToTop');

        window.addEventListener('scroll', function() {
            if (window.scrollY > 200) {
                backToTop.classList.remove('opacity-0', 'scale-90', 'pointer-events-none');
                backToTop.classList.add('opacity-100', 'scale-100', 'pointer-events-auto');
            } else {
                backToTop.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
                backToTop.classList.add('opacity-0', 'scale-90', 'pointer-events-none');
            }
        });

        backToTop.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Loading
        document.addEventListener('click', function(e) {
            const target = e.target;

            const link = target.closest('a[href]');
            if (link) {
                const href = link.getAttribute('href');

                if (
                    link.target === '_blank' ||
                    href.startsWith('javascript:') ||
                    href.startsWith('#') ||
                    href.startsWith('mailto:') ||
                    href.startsWith('tel:')
                ) {
                    return;
                }

                document.getElementById('pageLoader')?.classList.remove('hidden');
                return;
            }

            const clickable = target.closest('[onclick]');
            const onclick = clickable?.getAttribute('onclick');
            if (onclick && onclick.includes('window.location')) {
                document.getElementById('pageLoader')?.classList.remove('hidden');
            }
        });
    </script>

</body>

</html>
