<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="flex-grow flex flex-col justify-center items-center bg-pink-50 py-12">
        <div class="bg-white p-6 md:p-8 rounded-lg shadow-md w-full max-w-md border border-pink-200">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('img/Logo-Ceria-2.png') }}" alt="Logo" class="w-12 h-12">
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-pink-600">Email</label>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                        class="bg-pink-50 border border-pink-300 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500 block w-full p-2.5" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-pink-600">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="bg-pink-50 border border-pink-300 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500 block w-full p-2.5" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me & Forgot -->
                <div class="flex justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="w-4 h-4 text-pink-600 border border-pink-300 rounded-sm bg-pink-50 focus:ring-2 focus:ring-pink-300 focus:border-pink-500" />
                        <label for="remember_me" class="ms-2 text-sm text-gray-600">Remember me</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a class="text-sm text-pink-600 hover:underline" href="{{ route('password.request') }}">
                            Forgot your password?
                        </a>
                    @endif
                </div>

                <!-- Submit -->
                <x-primary-button class="w-full justify-center bg-pink-600 hover:bg-pink-700 focus:ring-pink-300">
                    {{ __('Log in') }}
                </x-primary-button>

                <!-- Register -->
                <div class="text-sm font-medium text-gray-500 text-center">
                    Not registered? <a href="{{ route('register') }}" class="text-pink-600 hover:underline">Create account</a>
                </div>

                <!-- Google login -->
                <div class="flex items-center justify-center mt-4">
                    <a href="{{ route('google.login') }}" class="flex items-center gap-2 w-full justify-center bg-white border border-gray-300 hover:bg-gray-100 text-gray-800 font-medium rounded-lg text-sm px-5 py-2.5">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google Logo">
                        Login with Google
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
