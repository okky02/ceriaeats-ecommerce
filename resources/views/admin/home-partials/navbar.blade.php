<nav class="fixed top-0 z-50 w-full bg-white shadow-sm">
    <div class="px-4 py-3 lg:px-6 lg:pl-4">
        <div class="flex items-center justify-between">
            <!-- Left side with logo and brand -->
            <div class="flex items-center justify-start space-x-3">
                <!-- Mobile menu button -->
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                    type="button"
                    class="p-2 text-pink-500 rounded-lg hover:bg-pink-50 transition-all duration-200 sm:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fas fa-bars text-lg"></i>
                </button>

                <!-- Logo and brand name -->
                <a href="#" class="flex items-center space-x-2 group">
                    <!-- Logo - replace with your actual logo -->
                    <div class="w-10 h-10">
                        <img src="{{ asset('img/Logo-Ceria-2.png') }}" alt="logo">
                    </div>
                    <span class="text-xl font-semibold text-pink-500">CeriaEats.</span>
                </a>
            </div>

            <!-- Right side with user menu -->
            <div class="flex items-center space-x-4">
                <!-- Notification bell -->
                {{-- <button
                    class="p-2 text-pink-500 hover:text-pink-500 relative hover:bg-pink-50 rounded-full transition-all">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-pink-600 rounded-full"></span>
                </button> --}}

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

            </div>
        </div>
    </div>
</nav>
