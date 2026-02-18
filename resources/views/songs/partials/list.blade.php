@foreach($songs as $song)
<div class="flex items-center gap-3 py-1 group transition">

    <!-- Cover with Play Button Overlay -->
    <div class="relative w-12 h-12 shrink-0 ml-1">
        @if($song->cover_path)
            <img src="{{ Storage::url($song->cover_path) }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-[#213042] flex items-center justify-center">
                <ion-icon name="musical-notes-outline" class="w-5 h-5 text-[#53a1b3]"></ion-icon>
            </div>
        @endif

        <!-- Play Button Overlay -->
        <button type="button" class="absolute inset-0 w-full h-full flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition text-white hover:text-[#e96c4c]" data-play-btn data-audio-id="audio-{{ $song->id }}">
            <ion-icon name="play" class="w-5 h-5"></ion-icon>
        </button>
    </div>

    <!-- Info & Link -->
    <a href="{{ route('songs.show', $song) }}" class="flex-1 min-w-0">
        <h3 class="font-normal text-white text-sm mb-0.5 truncate group-hover:text-[#e96c4c] transition">{{ $song->title }}</h3>
        <div class="flex items-center gap-2 mt-0.5">
            <p class="text-[11px] text-[#53a1b3] truncate max-w-[100px]">{{ $song->artist }}</p>

            <!-- Downloads Badge -->
            <div class="flex items-center h-4 border border-[#53a1b3]/20 overflow-hidden">
                <div class="bg-[#141e24] px-1.5 h-full flex items-center justify-center border-r border-[#53a1b3]/10">
                    <ion-icon name="download-outline" class="w-2 h-2 text-[#53a1b3]"></ion-icon>
                </div>
                <div class="bg-[#53a1b3]/10 px-1.5 h-full flex items-center justify-center text-[9px] font-normal text-[#53a1b3] font-mono">
                    {{ number_format($song->downloads) }}
                </div>
            </div>

            <!-- Likes Badge -->
            <div class="flex items-center h-4 border border-[#53a1b3]/20 overflow-hidden">
                <div class="bg-[#141e24] px-1.5 h-full flex items-center justify-center border-r border-[#53a1b3]/10">
                    <ion-icon name="heart-outline" class="w-2 h-2 text-[#53a1b3]"></ion-icon>
                </div>
                <div class="bg-[#53a1b3]/10 px-1.5 h-full flex items-center justify-center text-[9px] font-normal text-[#53a1b3] font-mono">
                    {{ number_format($song->likes->count()) }}
                </div>
            </div>
        </div>
    </a>

    <!-- Action Buttons -->
    <div class="flex items-center gap-2 shrink-0">
        <!-- Like Button -->
        <button onclick="toggleLike('song', {{ $song->id }}, this)" class="w-8 h-8 flex items-center justify-center transition {{ $song->likes()->where('user_id', auth()->id())->exists() ? 'text-red-500' : 'text-[#53a1b3]/40 hover:text-red-500' }}">
            <ion-icon name="{{ $song->likes()->where('user_id', auth()->id())->exists() ? 'heart' : 'heart-outline' }}" class="w-4 h-4 like-icon"></ion-icon>
        </button>

        <!-- Share Button -->
        <button type="button" onclick="shareSong('{{ $song->title }}', '{{ $song->artist }}', '{{ route('songs.show', $song) }}')" class="w-8 h-8 flex items-center justify-center text-[#53a1b3]/40 hover:text-white transition">
            <ion-icon name="share-social-outline" class="w-4 h-4"></ion-icon>
        </button>

        <!-- Download Button -->
        <a href="{{ route('songs.download', $song) }}" class="w-8 h-8 flex items-center justify-center text-[#53a1b3]/40 hover:text-[#e96c4c] transition">
            <ion-icon name="cloud-download-outline" class="w-4 h-4"></ion-icon>
        </a>
    </div>

    <audio id="audio-{{ $song->id }}" src="{{ Storage::url($song->file_path) }}"></audio>
</div>
@endforeach
