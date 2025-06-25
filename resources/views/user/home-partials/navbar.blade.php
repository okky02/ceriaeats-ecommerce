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
        <a href="{{ route('user.home.index') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="{{ asset('img/Logo-Ceria-2.png') }}" class="h-20" alt="CeriaEats Logo" />
        </a>

        <!-- Right Icons + Hamburger -->
        <div class="flex items-center space-x-4 md:order-2">
            <!-- Cart -->
            <a href="{{ route('user.cart.index') }}"
                class="icon fancy-underline underline-yellow relative px-4 py-2 block 
                    {{ request()->is('user/cart') ? 'text-pink-400 active' : 'text-pink-500 hover:text-pink-500' }}">
                <i data-lucide="shopping-basket"
                    class="w-9 h-9 
                    {{ request()->is('user/cart') ? 'text-pink-500' : 'text-yellow-400 hover:text-pink-500' }}">
                </i>
                <span
                    class="absolute -top-1 -right-1 bg-pink-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                    {{ $cartItemCount }}
                </span>
            </a>

            <!-- Profile Dropdown -->
            <div class="flex items-center ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <div
                            class="flex items-center cursor-pointer text-yellow-400 space-x-2 py-2 group hover:text-pink-500 transition-colors duration-200">
                            @if (Auth::user()->profile_photo)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Profile Photo"
                                    class="w-10 h-10 rounded-full object-cover border border-pink-300" />
                            @else
                                <i class="fas fa-user-circle text-[36px]"></i>
                            @endif

                            <span class="font-medium">{{ Str::limit(Auth::user()->name, 6) }}</span>
                        </div>
                    </x-slot>


                    <x-slot name="content">
                        <div class="px-4 py-2" role="none">
                            <p class="text-sm text-pink-600 font-medium" role="none">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-sm text-pink-500 truncate" role="none">
                                {{ Auth::user()->email }}
                            </p>
                        </div>

                        <hr class="border-pink-200" />

                        @php
                            $profileRoute =
                                Auth::user()->role === 'admin'
                                    ? route('admin.profile.edit')
                                    : route('user.profile.edit');
                        @endphp

                        <!-- Link to profile -->
                        <x-dropdown-link :href="$profileRoute">
                            <i class="fas fa-user-circle mr-2"></i> {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Menu -->
            <button id="hamburgerBtn" type="button"
                class="inline-flex items-center w-10 h-10 justify-center text-pink-500 rounded-lg md:hidden hover:bg-pink-100 focus:outline-none focus:ring-2 focus:ring-pink-200 transition-transform duration-300 hover:scale-110"
                aria-controls="navbar-user" aria-expanded="false">
                <span class="sr-only">Toggle menu</span>
                <i id="icon-menu" class="fas fa-bars text-xl transition-transform duration-300"></i>
                <i id="icon-close" class="fas fa-times text-xl hidden transition-transform duration-300"></i>
            </button>
        </div>

        <!-- Nav Menu -->
        <div id="navbar-user"
            class="overflow-hidden transition-all duration-500 ease-in-out max-h-0 md:max-h-full w-full md:flex md:items-center md:w-auto md:order-1 hidden md:block">
            <ul class="flex flex-col md:flex-row md:space-x-8 mt-4 md:mt-0 font-medium">
                <li><a href="{{ route('user.home.index') }}"
                        class="fancy-underline px-4 py-2 block {{ request()->is('user/home') ? 'text-yellow-400 active' : 'text-pink-500 hover:text-yellow-400' }}">
                        Home</a></li>

                <li><a href="{{ route('user.shop.index') }}"
                        class="fancy-underline px-4 py-2 block {{ request()->is('user/shop') ? 'text-yellow-400 active' : 'text-pink-500 hover:text-yellow-400' }}">
                        Shop</a></li>

                <li><a href="{{ route('user.order.index') }}"
                        class="fancy-underline px-4 py-2 block {{ request()->is('user/order') ? 'text-yellow-400 active' : 'text-pink-500 hover:text-yellow-400' }}">
                        My Orders</a></li>

                <li><a href="{{ route('user.about-master') }}"
                        class="fancy-underline px-4 py-2 block {{ request()->routeIs('user.about-master') ? 'text-yellow-400 active' : 'text-pink-500 hover:text-yellow-400' }}">
                        About</a></li>

                <li><a href="{{ route('user.chat') }}"
                        class="fancy-underline px-4 py-2 block {{ request()->is('user/chat') ? 'text-yellow-400 active' : 'text-pink-500 hover:text-yellow-400' }}">
                        Contact</a></li>

            </ul>

        </div>

    </div>
</nav>

<script>
    // Hamburger Button
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
