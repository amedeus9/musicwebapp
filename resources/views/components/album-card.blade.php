@props(['album'])

<div class="group relative flex flex-col gap-1">
    {{-- Cover --}}
    <a href="{{ route('albums.show', $album->slug) }}" class="aspect-square bg-[#1a2730] border border-[#53a1b3]/5 overflow-hidden relative rounded-[3px] block">
        @if($album->cover_path)
            <img src="{{ Storage::url($album->cover_path) }}" alt="{{ $album->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center text-[#53a1b3]/10">
                <ion-icon name="disc" class="w-10 h-10"></ion-icon>
            </div>
        @endif
        
        {{-- Hover Overlay --}}
        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center z-10">
            <div class="w-10 h-10 bg-[#e96c4c] rounded-[3px] flex items-center justify-center text-white shadow-lg hover:scale-110 transition">
                <ion-icon name="arrow-forward" class="w-5 h-5 ml-0.5"></ion-icon>
            </div>
        </div>

        {{-- Year Badge --}}
        @if($album->release_date)
            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition duration-300">
                <span class="text-[9px] bg-black/60 backdrop-blur-sm text-white/70 px-1.5 py-0.5 rounded-[2px] border border-white/10 font-mono tracking-tighter shadow-sm">
                    {{ \Carbon\Carbon::parse($album->release_date)->format('Y') }}
                </span>
            </div>
        @endif
    </a>

    {{-- Info --}}
    <div class="min-w-0">
        <a href="{{ route('albums.show', $album->slug) }}" class="block text-white text-[11px] uppercase font-normal truncate group-hover:text-[#e96c4c] transition">{{ $album->title }}</a>
        <div class="flex items-center gap-1">
            @if($album->artist)
                <p class="text-[#53a1b3]/40 text-[10px] uppercase truncate">{{ $album->artist->name }}</p>
            @else
                 <p class="text-[#53a1b3]/40 text-[10px] uppercase truncate">Unknown Artist</p>
            @endif
        </div>
    </div>
</div>
