<section>
    <header>
        <h2 class="text-lg font-semibold text-pink-600">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form x-data="{ loading: false }" @submit="loading = true" method="post" action="{{ route('password.update') }}"
        class="mt-6 space-y-6">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div>
            <label for="update_password_current_password" class="block mb-2 text-sm font-medium text-pink-600">
                {{ __('Current Password') }}
            </label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="bg-pink-50 border border-pink-300 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500 block w-full p-2.5"
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <!-- New Password -->
        <div>
            <label for="update_password_password" class="block mb-2 text-sm font-medium text-pink-600">
                {{ __('New Password') }}
            </label>
            <input id="update_password_password" name="password" type="password"
                class="bg-pink-50 border border-pink-300 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500 block w-full p-2.5"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="update_password_password_confirmation" class="block mb-2 text-sm font-medium text-pink-600">
                {{ __('Confirm Password') }}
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="bg-pink-50 border border-pink-300 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500 block w-full p-2.5"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit -->
        <div class="flex items-center gap-4">
            <x-spinner-button loading="loading" 
            label="Save" 
            loadingLabel="Save..."  
            bgColor="bg-pink-600"
            hoverColor="hover:bg-pink-700"
            ringColor="focus:ring-pink-500"
            icon='<i class="fa-solid fa-floppy-disk mr-2"></i>'/>

            @if (session('status') === 'password-updated')
                <x-success-toast title="Password Diperbarui!" message="Password kamu berhasil diubah." />
            @endif

        </div>
    </form>
</section>
