<x-guest-layout>
    <div class="flex-grow flex flex-col justify-center items-center py-12 bg-pink-50">
        <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md">
            <!-- Header with Logo -->
            <div class="flex justify-center mb-4">
                <img src="{{ asset('img/Logo-Ceria-2.png') }}" alt="Logo" class="w-12 h-12">
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-pink-600">Full Name</label>
                    <input id="name" type="text" name="name" :value="old('name')" required autofocus
                        autocomplete="name"
                        class="bg-pink-50 border border-pink-300 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500 block w-full p-2.5">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-pink-600">Email</label>
                    <input id="email" type="email" name="email" :value="old('email')" required
                        autocomplete="username"
                        class="bg-pink-50 border border-pink-300 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500 block w-full p-2.5">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-pink-600">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="bg-pink-50 border border-pink-300 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500 block w-full p-2.5">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-pink-600">Confirm
                        Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        autocomplete="new-password"
                        class="bg-pink-50 border border-pink-300 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500 block w-full p-2.5">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full text-white bg-pink-600 hover:bg-pink-700 focus:ring-4 focus:outline-none focus:ring-pink-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    {{ __('Register') }}
                </button>

                <!-- Link to login -->
                <div class="text-sm font-medium text-gray-500 text-center">
                    {{ __('Already registered?') }}
                    <a href="{{ route('login') }}" class="text-pink-600 hover:underline">{{ __('Login here') }}</a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
