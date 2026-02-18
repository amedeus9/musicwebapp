@props([
    'path'        => null,    // Storage path (nullable)
    'alt'         => '',
    'fallbackIcon'=> 'musical-notes-outline',
    'size'        => 'w-full h-full',   // tailwind size classes
    'iconSize'    => 'w-10 h-10',
    'iconColor'   => 'text-[#53a1b3]/20',
    'bg'          => 'bg-[#0f1319]',
    'hover'       => false,   // whether to add scale hover effect on image
])

@if($path)
    <img
        src="{{ Storage::url($path) }}"
        alt="{{ $alt }}"
        class="{{ $size }} object-cover{{ $hover ? ' group-hover:scale-105 transition duration-500' : '' }}"
    >
@else
    <div class="{{ $size }} {{ $bg }} flex items-center justify-center">
        <ion-icon name="{{ $fallbackIcon }}" class="{{ $iconSize }} {{ $iconColor }}"></ion-icon>
    </div>
@endif
