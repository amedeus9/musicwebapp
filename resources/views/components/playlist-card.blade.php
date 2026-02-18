@props(['playlist'])

<a href="{{ route('playlists.show', $playlist->slug) }}" class="group relative flex flex-col gap-1">
    {{-- Cover --}}
    <div class="aspect-square bg-[#1a2730] border border-[#53a1b3]/5 overflow-hidden relative rounded-[3px]">
        @if($playlist->cover_path)
            <img src="{{ Storage::url($playlist->cover_path) }}" alt="{{ $playlist->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center text-[#53a1b3]/10">
                <ion-icon name="list" class="w-10 h-10"></ion-icon>
            </div>
        @endif
        
        {{-- Hover Overlay --}}
        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center z-10">
            <div class="w-10 h-10 bg-[#e96c4c] rounded-[3px] flex items-center justify-center text-white shadow-lg hover:scale-110 transition">
                <ion-icon name="arrow-forward" class="w-5 h-5 ml-0.5"></ion-icon>
            </div>
        </div>
        
        {{-- Private Badge --}}
        @if(!$playlist->is_public)
            <div class="absolute top-2 right-2 bg-black/60 backdrop-blur-sm px-1.5 py-1 rounded-[3px] flex items-center justify-center">
                <ion-icon name="lock-closed" class="w-3 h-3 text-[#e96c4c]"></ion-icon>
            </div>
        @endif
    </div>

    {{-- Info --}}
    <div class="min-w-0">
        <h4 class="text-white text-[11px] uppercase font-normal truncate group-hover:text-[#e96c4c] transition">{{ $playlist->name }}</h4>
        <div class="flex items-center gap-2">
            <p class="text-[#53a1b3]/40 text-[10px] uppercase truncate">{{ $playlist->songs_count ?? $playlist->songs()->count() }} tracks</p>
            <span class="text-[#53a1b3]/20">•</span>
            <p class="text-[#53a1b3]/40 text-[10px] uppercase truncate">{{ $playlist->user->name }}</p>
        </div>
    </div>
</a>
