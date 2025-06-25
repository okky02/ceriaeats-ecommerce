@props([
    'type' => 'submit',
    'loading' => 'loading',
    'label' => 'Save',
    'loadingLabel' => 'Save...',
    'bgColor' => 'bg-pink-600',
    'hoverColor' => 'hover:bg-pink-700',
    'ringColor' => 'focus:ring-pink-500',
    'icon' => '<i class="fa-solid fa-floppy-disk mr-2"></i>',
    'textSize' => 'text-sm',
    'padding' => 'px-4 py-2',
    'rounded' => 'rounded-md',
    'fullWidth' => false,
])

<button type="{{ $type }}"
    class="{{ $fullWidth === 'true' ? 'w-full justify-center' : '' }} inline-flex items-center {{ $padding}} {{ $bgColor }} border border-transparent {{ $rounded }} font-semibold {{$textSize}} text-white {{ $hoverColor }} focus:outline-none focus:ring-2 {{ $ringColor }} focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-75"
    :disabled="{{ $loading }}">
    <i x-show="{{ $loading }}" class="fas fa-spinner fa-spin mr-2 text-white"></i>
    <span x-show="!{{ $loading }}">{!! $icon !!}</span>
    <span x-text="{{ $loading }} ? '{{ $loadingLabel }}' : '{{ $label }}'"></span>
</button>
