@props([
    'message' => 'Terjadi kesalahan. Silakan coba lagi.',
    'title' => 'Gagal!',
    'show' => 'show',
])

<div x-data="{ {{ $show }}: true }" x-init="setTimeout(() => {{ $show }} = false, 3000)" x-show="{{ $show }}"
    x-transition:enter="transition transform ease-out duration-300" x-transition:enter-start="-translate-x-full opacity-0"
    x-transition:enter-end="translate-x-0 opacity-100" x-transition:leave="transition transform ease-in duration-500"
    x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0"
    class="fixed top-6 right-6 md:right-6 md:w-[360px] w-[90%] bg-red-100 border border-red-300 text-red-900 px-6 py-5 shadow-xl rounded-xl backdrop-blur-sm z-50">

    <div class="flex items-start gap-4">
        <div class="mt-1 text-red-600">
            <i class="fas fa-circle-xmark text-2xl"></i>
        </div>
        <div class="flex-1">
            <h3 class="text-base font-bold">{{ $title }}</h3>
            <p class="text-sm mt-1">{{ $message }}</p>
        </div>
        <button @click="{{ $show }} = false"
            class="text-red-700 hover:text-red-900 text-xl font-semibold">&times;</button>
    </div>

    <div class="mt-4 relative h-1 w-full bg-red-200 rounded overflow-hidden">
        <div class="absolute right-0 top-0 h-full bg-red-600 animate-progress-bar rounded"></div>
    </div>

    <style>
        @keyframes progress-bar {
            0% {
                width: 100%;
            }

            100% {
                width: 0%;
            }
        }

        .animate-progress-bar {
            animation: progress-bar 3s linear forwards;
        }
    </style>
</div>
