@props([
    'song',
    'index' => 0,
    'songs' => null, // Although index is no longer shown, we keep props for compatibility
    'showAlbum' => true,
    'showDuration' => true,
    'showStats' => true
])

<div class="group relative flex items-center lg:grid lg:grid-cols-[1fr_20%_15%_200px] gap-4 transition duration-300">
    
    <!-- Track Info -->
    <div class="flex items-center gap-3 min-w-0 flex-1 lg:flex-none">
        <!-- Cover Art (48px) -->
        <div class="relative w-12 h-12 shrink-0 bg-[#1a2730]/40 rounded-[3px] overflow-hidden border border-white/5">
            @if($song->cover_path)
                <img src="{{ Storage::url($song->cover_path) }}" class="w-full h-full object-cover transition duration-500">
            @else
                <div class="w-full h-full flex items-center justify-center text-[#53a1b3]/10">
                    <ion-icon name="musical-notes" class="w-5 h-5"></ion-icon>
                </div>
            @endif

            <!-- Play Trigger Overlay (Always active on hover) -->
            <button type="button" 
                    class="absolute inset-0 w-full h-full flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition text-white" 
                    data-play-btn 
                    data-audio-id="audio-{{ $song->id }}">
                <ion-icon name="play" class="w-4 h-4"></ion-icon>
            </button>
        </div>

        <div class="flex flex-col min-w-0">
            <a href="{{ route('songs.show', $song) }}" class="song-title text-white text-[13px] lg:text-sm font-normal uppercase tracking-wide truncate group-hover:text-[#e96c4c] transition duration-300">
                {{ $song->title }}
            </a>
            <div class="flex items-center gap-2 mt-0.5">
                <span class="song-artist text-[#53a1b3]/50 text-[10px] uppercase tracking-widest truncate">
                    {{ $song->artist }}
                </span>
                <!-- Mobile only badges -->
                <div class="flex lg:hidden items-center gap-2">
                    <x-stat-badge icon="download-outline" :value="$song->downloads" color="#53a1b3" />
                </div>
            </div>
        </div>
    </div>

    <!-- Album (Desktop) -->
    <div class="hidden lg:flex items-center min-w-0">
        @if($showAlbum)
            @if($song->album)
                <a href="{{ route('albums.show', $song->album) }}" class="text-[#53a1b3]/50 text-[10px] uppercase tracking-widest truncate hover:text-white transition">
                    {{ $song->album->title }}
                </a>
            @else
                <span class="text-[#53a1b3]/20 text-[10px] uppercase tracking-widest">— Single</span>
            @endif
        @endif
    </div>

    <!-- Duration (Desktop) -->
    <div class="hidden lg:flex items-center">
        @if($showDuration)
            <span class="text-[#53a1b3]/50 text-[10px] uppercase font-mono tracking-widest flex items-center gap-1.5">
                <ion-icon name="time-outline" class="w-2.5 h-2.5"></ion-icon>
                {{ floor($song->duration / 60) }}:{{ str_pad($song->duration % 60, 2, '0', STR_PAD_LEFT) }}
            </span>
        @endif
    </div>

    <!-- Stats & Actions -->
    <div class="flex items-center justify-end lg:justify-end gap-1 lg:gap-3 shrink-0 pr-1">
        <!-- Desktop Stats -->
        <div class="hidden lg:flex items-center gap-2 mr-4">
            @if($showStats)
                <x-stat-badge icon="download-outline" :value="$song->downloads" color="#53a1b3" />
                <x-stat-badge icon="heart-outline" :value="$song->likes->count()" color="#53a1b3" />
                <x-stat-badge icon="chatbubble-outline" :value="$song->comments->count()" color="#53a1b3" />
            @endif
        </div>

        <!-- Action Menu (Always Visible) -->
        <div class="flex items-center gap-1">
            <!-- Like Button -->
            <button onclick="toggleLike('song', {{ $song->id }}, this)" 
                    class="w-8 h-8 flex items-center justify-center rounded-[3px] hover:bg-red-500/10 {{ $song->likes()->where('user_id', auth()->id())->exists() ? 'text-red-500' : 'text-[#53a1b3]/40 hover:text-red-500' }} transition">
                <ion-icon name="{{ $song->likes()->where('user_id', auth()->id())->exists() ? 'heart' : 'heart-outline' }}" class="w-4 h-4"></ion-icon>
            </button>
            
            <!-- Share Button -->
            <button onclick="shareSong('{{ addslashes($song->title) }}', '{{ addslashes($song->artist) }}', '{{ route('songs.show', $song) }}')" 
                    class="w-8 h-8 flex items-center justify-center text-[#53a1b3]/40 hover:text-white transition rounded-[3px] hover:bg-white/5">
                <ion-icon name="share-social-outline" class="w-4 h-4"></ion-icon>
            </button>

            <!-- Options (Mobile: Open Bottom Sheet) -->
            <div class="relative group/menu">
                <button onclick="triggerSongDetails({{ $song->id }})" class="w-8 h-8 flex items-center justify-center text-[#53a1b3]/40 hover:text-white transition rounded-[3px] hover:bg-white/5">
                    <ion-icon name="ellipsis-vertical" class="w-4 h-4"></ion-icon>
                </button>
            </div>
        </div>
    </div>

    <!-- Hidden Template for Bottom Sheet Content (Mobile) -->
    <template id="song-details-{{ $song->id }}">
        <div class="flex flex-col gap-6 pt-2">
            <!-- Header with Image -->
            <div class="flex items-center gap-4 pb-6 border-b border-[#53a1b3]/5">
                <div class="w-[70px] h-[70px] bg-[#1a2730]/40 rounded-[3px] overflow-hidden border border-white/5 shrink-0">
                    <img src="{{ $song->cover_path ? Storage::url($song->cover_path) : '' }}" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="text-white text-sm font-normal uppercase tracking-wide truncate">{{ $song->title }}</h2>
                    <p class="text-[#53a1b3]/60 text-[10px] uppercase tracking-[0.2em] mt-1">{{ $song->artist }}</p>
                </div>
            </div>

            <!-- Metadata Grid -->
            <div class="grid grid-cols-2 gap-y-5 gap-x-4">
                <div class="flex flex-col gap-1.5">
                    <span class="text-[9px] text-[#53a1b3]/40 uppercase tracking-[0.2em]">Album</span>
                    <span class="text-white text-[10px] uppercase tracking-widest truncate">{{ $song->album->title ?? 'Single' }}</span>
                </div>
                <div class="flex flex-col gap-1.5">
                    <span class="text-[9px] text-[#53a1b3]/40 uppercase tracking-[0.2em]">Duration</span>
                    <span class="text-white text-[10px] font-mono tracking-widest">{{ floor($song->duration / 60) }}:{{ str_pad($song->duration % 60, 2, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="flex flex-col gap-1.5">
                    <span class="text-[9px] text-[#53a1b3]/40 uppercase tracking-[0.2em]">Release Date</span>
                    <span class="text-white text-[10px] uppercase tracking-widest">{{ $song->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex flex-col gap-1.5">
                    <span class="text-[9px] text-[#53a1b3]/40 uppercase tracking-[0.2em]">Status</span>
                    <span class="text-[#e96c4c] text-[10px] uppercase tracking-widest">Premium Track</span>
                </div>
            </div>

            <!-- Quick Actions List (Focused) -->
            <div class="flex flex-col gap-2">
                <a href="{{ route('songs.show', $song) }}" 
                    class="flex items-center gap-3 px-4 py-2 bg-white/5 text-white text-xs uppercase tracking-widest hover:bg-white/10 transition rounded-[3px]">
                    <ion-icon name="arrow-forward-outline" class="w-4 h-4 text-[#e96c4c]"></ion-icon>
                    Go to Track
                </a>
                <button onclick="closeBottomSheet(); shareSong('{{ addslashes($song->title) }}', '{{ addslashes($song->artist) }}', '{{ route('songs.show', $song) }}')" 
                        class="flex items-center gap-3 px-4 py-2 bg-white/5 text-white text-xs uppercase tracking-widest hover:bg-white/10 transition rounded-[3px]">
                    <ion-icon name="share-social-outline" class="w-4 h-4 text-[#53a1b3]"></ion-icon>
                    Share Track
                </button>
            </div>
        </div>
    </template>

    <script>
        if (typeof triggerSongDetails !== 'function') {
            function triggerSongDetails(songId) {
                // Only trigger on mobile/tablet (less than 1024px)
                if (window.innerWidth >= 1024) return;
                
                const template = document.getElementById('song-details-' + songId);
                if (template) {
                    openBottomSheet(template.innerHTML);
                }
            }
        }
    </script>

    <!-- Hidden Audio Element -->
    <audio id="audio-{{ $song->id }}" src="{{ Storage::url($song->file_path) }}" preload="none"></audio>
</div>
