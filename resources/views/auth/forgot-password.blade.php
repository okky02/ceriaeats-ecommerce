<x-guest-layout>
    <div class="flex-grow flex flex-col justify-center items-center py-12 bg-pink-50">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-6">
            <!-- Logo -->
            <div class="flex justify-center mb-4">
                <img src="{{ asset('img/Logo-Ceria-2.png') }}" alt="Logo" class="w-12 h-12">
            </div>

            <!-- Informasi -->
            <div class="mb-4 text-sm text-gray-600 text-center">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-pink-600" />
                    <x-text-input id="email"
                        class="bg-pink-50 border border-pink-300 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500 block w-full p-2.5 mt-1"
                        type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Submit + Back Button -->
                <div class="flex items-center justify-between mt-4">
                    <a href="{{ route('login') }}"
                        class="text-sm text-pink-600 hover:text-pink-700 hover:underline transition ease-in-out duration-150">
                        ← Go back to login
                    </a>

                    <x-primary-button class="bg-pink-600 hover:bg-pink-700 focus:ring-pink-300">
                        {{ __('Email Password Reset Link') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
