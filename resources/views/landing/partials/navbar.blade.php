<style>
        .fancy-underline {
        position: relative;
        display: inline-block;
    }

    .fancy-underline::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        height: 2px;
        width: 100%;
        background-color: #db2777;
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
    }

    .fancy-underline:hover::after,
    .fancy-underline.active::after {
        transform: scaleX(1);
    }

    .fancy-underline.underline-yellow::after {
        background-color: #facc15;
    }
    
</style>

<nav class="bg-gradient-to-r from-pink-100/60 via-pink-200/60 to-pink-100/60 sticky top-0 z-50 backdrop-blur-md">
    <div class="max-w-screen-xl mx-auto flex flex-wrap items-center justify-between p-4">

        <!-- Logo -->
        <a href="{{ route('landing.index') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="{{ asset('img/Logo-Ceria-2.png') }}" class="h-20" alt="CeriaEats Logo" />
        </a>

        <!-- Right Icons + Hamburger -->
        <div class="flex items-center space-x-4 md:order-2">
            <a href="{{ route('login') }}" class="icon fancy-underline relative py-2 block">
                <i data-lucide="shopping-basket" class="w-10 h-10 text-yellow-400 hover:text-yellow-500"></i>
                <span
                    class="absolute -top-1 -right-1 bg-pink-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                    0
                </span>
            </a>
            <a href="{{ route('login') }}" class="icon fancy-underline py-2 block">
                <i class="fas fa-user-circle text-yellow-400 text-[36px] hover:text-yellow-500"></i>
            </a>

            <!-- Hamburger -->
            <button id="hamburgerBtn" type="button"
                class="inline-flex items-center w-10 h-10 justify-center text-pink-500 rounded-lg md:hidden hover:bg-pink-100 focus:outline-none focus:ring-2 focus:ring-pink-200 transition-transform duration-300 hover:scale-110"
                aria-controls="navbar-user" aria-expanded="false">
                <span class="sr-only">Toggle menu</span>
                <svg id="icon-menu" class="w-6 h-6 transition-transform duration-300" fill="none"
                    stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="icon-close" class="w-6 h-6 hidden transition-transform duration-300" fill="none"
                    stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Nav Menu -->
        <div id="navbar-user"
            class="overflow-hidden transition-all duration-500 ease-in-out max-h-0 md:max-h-full w-full md:flex md:items-center md:w-auto md:order-1 hidden md:block">
            <ul class="flex flex-col md:flex-row md:space-x-8 mt-4 md:mt-0 font-medium">
                <li><a href="{{ route('landing.index') }}"
                        class="fancy-underline text-pink-500 px-4 py-2 block {{ request()->is('/') ? 'text-yellow-400 active' : 'text-pink-500 hover:text-yellow-400' }}">Home</a>
                </li>
                <li><a href="{{ route('login') }}"
                        class="fancy-underline text-pink-500 hover:text-yellow-400 px-4 py-2 block">Shop</a>
                </li>
                <li><a href="{{ route('login') }}"
                        class="fancy-underline text-pink-500 hover:text-yellow-400 px-4 py-2 block">My
                        Orders</a>
                </li>
                <li><a href="{{ route('landing.about-master') }}"
                        class="fancy-underline text-pink-500 px-4 py-2 block {{ request()->is('about') ? 'text-yellow-400 active' : 'text-pink-500 hover:text-yellow-400' }}">About</a>
                </li>
                <li><a href="{{ route('login') }}"
                        class="fancy-underline text-pink-500 hover:text-yellow-400 px-4 py-2 block">Contact</a>
                </li>
            </ul>
        </div>

    </div>
</nav>

<script>
    const btn = document.getElementById('hamburgerBtn');
    const menu = document.getElementById('navbar-user');
    const iconMenu = document.getElementById('icon-menu');
    const iconClose = document.getElementById('icon-close');

    let isOpen = false;

    btn.addEventListener('click', () => {
        if (!isOpen) {
            // BUKA
            menu.classList.remove('hidden');
            setTimeout(() => {
                menu.classList.remove('max-h-0');
                menu.classList.add('max-h-[500px]');
            }, 10);

            // Ganti ikon jadi X
            iconMenu.classList.add('hidden');
            iconClose.classList.remove('hidden');
        } else {
            // TUTUP
            menu.classList.remove('max-h-[500px]');
            menu.classList.add('max-h-0');
            setTimeout(() => {
                menu.classList.add('hidden');
            }, 500);

            // Balik ikon ke hamburger
            iconClose.classList.add('hidden');
            iconMenu.classList.remove('hidden');
        }

        isOpen = !isOpen;
    });
</script>
