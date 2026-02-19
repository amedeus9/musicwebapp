@props(['artist'])

<a href="{{ route('artists.show', ['country' => $artist->country->slug ?? 'global', 'artist' => $artist]) }}" class="group relative flex flex-col gap-2 cursor-pointer">
    {{-- Avatar / Image --}}
    <div class="aspect-square bg-[#1a2730] border border-[#53a1b3]/5 overflow-hidden relative rounded-[3px]">
        @if($artist->image_path)
            <img src="{{ Storage::url($artist->image_path) }}" alt="{{ $artist->name }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-105 transition duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center text-[#53a1b3]/10">
                <ion-icon name="mic" class="w-10 h-10"></ion-icon>
            </div>
        @endif
        
        {{-- Hover Overlay --}}
        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center z-10">
            <div class="w-10 h-10 bg-[#e96c4c] rounded-[3px] flex items-center justify-center text-white shadow-lg hover:scale-110 transition">
                <ion-icon name="arrow-forward" class="w-5 h-5 ml-0.5"></ion-icon>
            </div>
        </div>
    </div>

    {{-- Info --}}
    <div class="min-w-0 text-center">
        <h4 class="text-white text-[11px] uppercase font-normal truncate group-hover:text-[#e96c4c] transition">{{ $artist->name }}</h4>
        <p class="text-[#53a1b3]/40 text-[10px] uppercase truncate">{{ $artist->songs_count }} Tracks</p>
    </div>
</a>
