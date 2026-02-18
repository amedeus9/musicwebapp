@props([
    'title',
    'scrollId'  => null,   // if set, shows left/right scroll arrows
    'viewAll'   => null,   // optional URL for "View All" link
    'viewLabel' => 'View All',
])

<div class="flex items-center justify-between mb-3 px-1">
    <h2 class="text-[#53a1b3] font-normal text-xs uppercase tracking-wider">{{ $title }}</h2>

    <div class="flex items-center gap-1">
        @if($viewAll)
            <a href="{{ $viewAll }}" class="text-[10px] font-normal text-[#e96c4c] uppercase tracking-wider hover:text-white transition">
                {{ $viewLabel }}
            </a>
        @endif

        @if($scrollId)
            <button onclick="sideScroll('{{ $scrollId }}', 'left')"
                class="w-6 h-6 border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3] hover:text-white hover:border-[#e96c4c] transition">
                <ion-icon name="chevron-back-outline" class="w-3 h-3"></ion-icon>
            </button>
            <button onclick="sideScroll('{{ $scrollId }}', 'right')"
                class="w-6 h-6 border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3] hover:text-white hover:border-[#e96c4c] transition">
                <ion-icon name="chevron-forward-outline" class="w-3 h-3"></ion-icon>
            </button>
        @endif
    </div>
</div>
