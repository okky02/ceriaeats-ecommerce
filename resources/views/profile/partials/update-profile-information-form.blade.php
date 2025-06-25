<section>
    <header>
        <h2 class="text-lg font-semibold text-pink-600">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-pink-500">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>


    <form x-data="{ loading: false }" @submit="loading = true" method="post" action="{{ route('profile.update') }}"
        enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="flex justify-center items-center w-40 h-40 rounded-lg border border-pink-300">
            @if (Auth::user()->profile_photo)
                <img id="profile-photo-preview" src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                    alt="Profile Photo" class="w-full h-full object-cover rounded-lg">
            @else
                <img id="profile-photo-preview" src="{{ asset('img/user.png') }}" alt="Profile Photo"
                    class="w-full h-full object-cover rounded-lg p-1">
            @endif
        </div>

        <!-- Image Upload Section -->
        <div>
            <label class="block text-sm font-medium text-pink-600">Photo Profile</label>

            <!-- Input Upload -->
            <div class="flex items-center justify-center w-full">
                <label for="profile_photo"
                    class="flex flex-col w-full h-32 border-2 border-dashed border-pink-300 rounded-lg hover:bg-pink-50 transition duration-300 cursor-pointer relative">
                    <div class="flex flex-col items-center justify-center pt-7 pointer-events-none">
                        <i class="fas fa-cloud-upload-alt text-3xl text-pink-400 mb-2"></i>
                        <p class="text-sm text-pink-500">Click to upload</p>
                    </div>
                    <input id="profile_photo" name="profile_photo" type="file"
                        class="absolute inset-0 opacity-0 cursor-pointer z-10" accept="image/*"
                        onchange="previewImage(event)" />
                </label>
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
        </div>

        <!-- Name -->
        <div>
            <label for="name" class="block mb-2 text-sm font-medium text-pink-600">Name</label>
            <input id="name" name="name" type="text"
                class="bg-pink-50 border border-pink-300 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500 block w-full p-2.5"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-pink-600">Email</label>
            <input id="email" name="email" type="email"
                class="bg-pink-50 border border-pink-300 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500 block w-full p-2.5"
                value="{{ old('name', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-2 text-sm text-yellow-800 bg-yellow-50 border border-yellow-300 rounded-md p-3">
                    {{ __('Your email address is unverified.') }}
                    <button form="send-verification" class="underline text-pink-600 hover:text-pink-800 ml-1">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Phone -->
        <div>
            <label for="phone" class="block mb-2 text-sm font-medium text-pink-600">Phone</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                class="bg-pink-50 border border-pink-300 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500 block w-full p-2.5" />
        </div>

        <!-- Address -->
        <div>
            <label for="address" class="block mb-2 text-sm font-medium text-pink-600">Address</label>
            <textarea id="address" name="address" rows="3"
                class="bg-pink-50 border border-pink-300 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500 block w-full p-2.5">{{ old('address', $user->address) }}</textarea>
        </div>

        <!-- Submit -->
        <div class="flex items-center gap-4">
            <x-spinner-button loading="loading" label="Save" loadingLabel="Save..." bgColor="bg-pink-600"
                hoverColor="hover:bg-pink-700" ringColor="focus:ring-pink-500"
                icon='<i class="fa-solid fa-floppy-disk mr-2"></i>' />

            @if (session('status') === 'profile-updated')
                <x-success-toast title="Success!" message="Profil kamu berhasil diperbarui." />
            @endif

            @if (session('status') === 'error')
                <x-error-toast title="{{ session('error_title', 'Terjadi Kesalahan') }}"
                    message="{{ session('error_message', 'Mohon coba lagi.') }}" />
            @endif

            @if ($errors->any())
                <x-error-toast title="Failed!" message="Ada kesalahan pada form, Mohon periksa kembali." />
            @endif

        </div>
    </form>
</section>

<script>
    // Priview Image
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('profile-photo-preview');

        if (input.files && input.files[0]) {
            const file = input.files[0];

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
