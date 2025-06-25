<x-guest-layout>
    <div class="flex justify-center items-center py-12 bg-pink-50">
        <div class="bg-white p-6 md:p-8 rounded-lg shadow-md w-full max-w-md border border-pink-200">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('img/Logo-Ceria-2.png') }}" alt="Logo" class="w-12 h-12">
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                @csrf

                <!-- Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-pink-600">Email</label>
                    <input id="email" type="email" name="email"
                        value="{{ old('email', request()->get('email')) }}" required autofocus autocomplete="username"
                        class="bg-pink-50 border border-pink-300 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500 block w-full p-2.5" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-pink-600">New Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="bg-pink-50 border border-pink-300 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500 block w-full p-2.5" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-pink-600">Confirm
                        Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        autocomplete="new-password"
                        class="bg-pink-50 border border-pink-300 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500 block w-full p-2.5" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Submit -->
                <x-primary-button class="w-full justify-center bg-pink-600 hover:bg-pink-700 focus:ring-pink-300">
                    {{ __('Reset Password') }}
                </x-primary-button>
            </form>
        </div>
    </div>
</x-guest-layout>
