@props([
    'icon'    => 'alert-circle-outline',
    'title'   => 'Nothing here yet',
    'message' => null,
    'actionLabel' => null,
    'actionUrl'   => null,
    'iconColor'   => 'text-[#e96c4c]',
])

<div class="p-8 flex flex-col items-center justify-center gap-3 text-center">
    <div class="w-16 h-16 flex items-center justify-center">
        <ion-icon name="{{ $icon }}" class="w-10 h-10 {{ $iconColor }}"></ion-icon>
    </div>
    <h4 class="text-white font-normal text-base">{{ $title }}</h4>
    @if($message)
        <p class="text-[#53a1b3] text-sm max-w-xs">{{ $message }}</p>
    @endif
    @if($actionLabel && $actionUrl)
        <a href="{{ $actionUrl }}"
            class="mt-2 bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white px-4 py-2 text-xs font-normal uppercase tracking-wider transition rounded-[3px]">
            {{ $actionLabel }}
        </a>
    @endif
    {{ $slot }}
</div>
