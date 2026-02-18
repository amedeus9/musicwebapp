@props(['song'])

<div class="group relative flex flex-col gap-1">
    {{-- Cover --}}
    <div class="aspect-square bg-[#1a2730] border border-[#53a1b3]/5 overflow-hidden relative rounded-[3px]">
        @if($song->cover_path)
            <img src="{{ Storage::url($song->cover_path) }}" alt="{{ $song->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center text-[#53a1b3]/10">
                <ion-icon name="musical-notes" class="w-10 h-10"></ion-icon>
            </div>
        @endif
        
        {{-- Play Overlay --}}
        <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition z-20">
            <button class="w-10 h-10 bg-[#e96c4c] rounded-[3px] flex items-center justify-center text-white shadow-lg hover:scale-110 transition"
                    data-play-btn
                    data-audio-id="audio-{{ $song->id }}">
                <ion-icon name="play" class="w-5 h-5 ml-0.5 pointer-events-none"></ion-icon>
            </button>
        </div>
        
        {{-- Global Click Link (Cover click navigates) --}}
        <a href="{{ route('songs.show', $song->slug) }}" class="absolute inset-0 z-10 bg-black/0 group-hover:bg-black/20 transition duration-300"></a>
    </div>

    {{-- Info --}}
    <div class="min-w-0">
        <a href="{{ route('songs.show', $song->slug) }}" class="song-title block text-white text-[11px] uppercase font-normal truncate group-hover:text-[#e96c4c] transition">{{ $song->title }}</a>
        <p class="song-artist text-[#53a1b3]/40 text-[10px] uppercase truncate">{{ $song->artist }}</p>
    </div>

    {{-- Audio Element for Playback --}}
    <audio id="audio-{{ $song->id }}" src="{{ Storage::url($song->file_path) }}" preload="none"></audio>
</div>
