@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col gap-8 pb-24">
    
    <!-- Album Header Section -->
    <div class="flex flex-col md:flex-row gap-8 items-center md:items-end">
        <!-- Cover Art -->
        <div class="w-48 h-48 md:w-64 md:h-64 shrink-0 shadow-2xl shadow-black/60 rounded-[3px] overflow-hidden relative group border border-[#53a1b3]/5">
            @if($album->cover_path)
                <img src="{{ Storage::url($album->cover_path) }}" alt="{{ $album->title }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-[#1a2730]/40 flex items-center justify-center text-[#53a1b3]/10">
                    <ion-icon name="musical-notes" class="w-16 h-16"></ion-icon>
                </div>
            @endif
        </div>

        <!-- Meta Info -->
        <div class="flex flex-col items-center md:items-start text-center md:text-left min-w-0">
            <span class="text-[#e96c4c] text-[10px] font-normal uppercase tracking-[0.3em] mb-3">Album Collection</span>
            <h1 class="text-3xl md:text-5xl font-normal text-white uppercase tracking-tight mb-4 leading-none truncate max-w-full">
                {{ $album->title }}
            </h1>
            
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 text-[10px] uppercase tracking-widest text-[#53a1b3]/50">
                @if($album->artistProfile)
                    <a href="{{ route('artists.show', $album->artistProfile) }}" class="flex items-center gap-1.5 text-white hover:text-[#e96c4c] transition duration-300">
                        <div class="w-4 h-4 bg-[#53a1b3]/10 rounded-full flex items-center justify-center overflow-hidden">
                             <ion-icon name="person" class="w-2.5 h-2.5"></ion-icon>
                        </div>
                        {{ $album->artistProfile->name }}
                    </a>
                @else
                    <span class="flex items-center gap-1.5">
                        <ion-icon name="person-outline" class="w-3 h-3"></ion-icon>
                        Unknown Artist
                    </span>
                @endif
                <span class="w-1 h-1 bg-[#53a1b3]/20 rounded-full"></span>
                <span class="flex items-center gap-1.5">
                    <ion-icon name="calendar-outline" class="w-3 h-3"></ion-icon>
                    {{ $album->release_date ? \Carbon\Carbon::parse($album->release_date)->format('Y') : 'Release Date N/A' }}
                </span>
                <span class="w-1 h-1 bg-[#53a1b3]/20 rounded-full"></span>
                <span class="flex items-center gap-1.5">
                    <ion-icon name="musical-notes-outline" class="w-3 h-3"></ion-icon>
                    {{ $album->songs->count() }} Tracks
                </span>
            </div>
        </div>
    </div>

    <!-- Album Action Toolbar -->
    <div class="flex items-center gap-2 border-y border-[#53a1b3]/5 py-4">
        <button onclick="playFirstSong()" class="px-6 py-2 bg-[#e96c4c] text-white text-xs font-normal uppercase tracking-widest rounded-[3px] flex items-center gap-2 hover:bg-[#e96c4c]/90 transition shadow-lg shadow-[#e96c4c]/20 group">
            <ion-icon name="play" class="w-4 h-4"></ion-icon>
            Play All
        </button>

        <button onclick="toggleLike('album', {{ $album->id }}, this)" 
                class="w-[35px] h-[35px] flex items-center justify-center border border-[#53a1b3]/10 text-[#53a1b3]/40 hover:text-red-500 hover:border-red-500/20 transition rounded-[3px] bg-[#1a2730]/20">
            <ion-icon name="{{ $album->likes()->where('user_id', auth()->id())->exists() ? 'heart' : 'heart-outline' }}" 
                      class="w-4 h-4 {{ $album->likes()->where('user_id', auth()->id())->exists() ? 'text-red-500' : '' }}"></ion-icon>
        </button>

        <button class="w-[35px] h-[35px] flex items-center justify-center border border-[#53a1b3]/10 text-[#53a1b3]/40 hover:text-white hover:border-white/20 transition rounded-[3px] bg-[#1a2730]/20">
            <ion-icon name="ellipsis-horizontal" class="w-4 h-4"></ion-icon>
        </button>
    </div>

    <!-- Tracklist Table -->
    <div class="flex flex-col">
        <div class="grid grid-cols-[32px_1fr_80px] md:grid-cols-[40px_1fr_120px_40px] gap-4 px-4 py-2 text-[10px] uppercase font-normal tracking-[0.2em] text-[#53a1b3]/30 border-b border-[#53a1b3]/5">
            <span class="text-center">#</span>
            <span>Title</span>
            <span class="hidden md:block">Duration</span>
            <span class="text-right">
                <ion-icon name="stats-chart-outline" class="w-3 h-3"></ion-icon>
            </span>
        </div>

        <div class="flex flex-col mt-1">
            @forelse($album->songs as $index => $song)
            <div class="group grid grid-cols-[32px_1fr_80px] md:grid-cols-[40px_1fr_120px_40px] gap-4 px-4 py-3 items-center hover:bg-white/5 transition duration-300 rounded-[3px]">
                <span class="text-center text-[#53a1b3]/30 font-mono text-[11px] group-hover:text-white transition">
                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                </span>
                
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-[3px] bg-[#1a2730] flex items-center justify-center shrink-0 border border-white/5 overflow-hidden">
                        @if($song->file_path)
                            <audio id="audio-{{ $song->id }}" src="{{ Storage::url($song->file_path) }}" preload="none"></audio>
                            <button class="w-full h-full flex items-center justify-center text-white/20 group-hover:text-[#e96c4c] transition" 
                                onclick="playSong('audio-{{ $song->id }}', '{{ addslashes($song->title) }}', '{{ addslashes($song->artist ?? $album->artistProfile->name) }}', '{{ $song->cover_path ? Storage::url($song->cover_path) : ( $album->cover_path ? Storage::url($album->cover_path) : '' ) }}', this)">
                                <ion-icon name="play" class="w-3 h-3"></ion-icon>
                            </button>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white/10">
                                <ion-icon name="lock-closed" class="w-3 h-3"></ion-icon>
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-col min-w-0">
                        <a href="{{ route('songs.show', $song) }}" class="text-white text-xs font-normal uppercase tracking-wide truncate group-hover:text-[#e96c4c] transition">
                            {{ $song->title }}
                        </a>
                        <span class="text-[#53a1b3]/30 text-[9px] uppercase tracking-wider truncate mt-0.5">
                            {{ $song->artist ?? $album->artistProfile->name ?? 'Unknown' }}
                        </span>
                    </div>
                </div>

                <div class="hidden md:flex items-center text-[10px] text-[#53a1b3]/30 font-mono">
                    {{ floor($song->duration / 60) }}:{{ str_pad($song->duration % 60, 2, '0', STR_PAD_LEFT) }}
                </div>

                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition duration-300">
                    <button class="text-[#53a1b3]/30 hover:text-white">
                        <ion-icon name="ellipsis-vertical" class="w-3 h-3"></ion-icon>
                    </button>
                </div>
            </div>
            @empty
                <div class="py-12 border border-dashed border-[#53a1b3]/5 rounded-[3px] flex flex-col items-center justify-center text-center">
                    <ion-icon name="musical-notes-outline" class="w-10 h-10 text-[#53a1b3]/10 mb-2"></ion-icon>
                    <p class="text-[10px] text-[#53a1b3]/30 uppercase tracking-widest">Tracklist is empty</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Album Comments & Community Section -->
    <div class="mt-8">
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-[#53a1b3]/5">
            <h3 class="text-white text-[11px] font-normal uppercase tracking-[0.2em] flex items-center gap-2">
                <ion-icon name="chatbubbles-outline" class="w-4 h-4 text-[#e96c4c]"></ion-icon>
                Listener Insights ({{ $album->comments->count() }})
            </h3>
            <div class="flex items-center gap-4 text-[10px] uppercase tracking-widest text-[#53a1b3]/30">
                <span class="flex items-center gap-1.5">
                     <ion-icon name="heart" class="w-3 h-3 text-red-500/40"></ion-icon>
                     {{ $album->likes->count() }} Recommendations
                </span>
            </div>
        </div>

        <div class="max-w-3xl">
            <x-comments
                type="album"
                :model-id="$album->id"
                :comments="$album->comments()->with('user')->latest()->get()"
                list-id="comments-list-album"
                placeholder="Share your thoughts on this masterpiece..."
            />
        </div>
    </div>

</div>

<script>
    let currentAudio = null;
    let currentButton = null;

    function playSong(audioId, title, artist, cover, btnElement) {
        const audio = document.getElementById(audioId);
        if (!audio) return;

        if (currentAudio && currentAudio !== audio) {
            currentAudio.pause();
            currentAudio.currentTime = 0;
            if (currentButton) {
                const icon = currentButton.querySelector('ion-icon');
                if (icon) icon.setAttribute('name', 'play');
            }
        }

        const icon = btnElement.querySelector('ion-icon');

        if (audio.paused) {
            audio.play();
            if (icon) icon.setAttribute('name', 'pause');
            currentAudio = audio;
            currentButton = btnElement;

            if (window.globalPlayer) {
                window.globalPlayer.show(audio, { title, artist, cover }, btnElement);
            }
        } else {
            audio.pause();
            if (icon) icon.setAttribute('name', 'play');
        }

        audio.onended = () => {
            if (icon) icon.setAttribute('name', 'play');
        };
    }

    function playFirstSong() {
        const firstAudio = document.querySelector('audio[id^="audio-"]');
        if (firstAudio) {
            const btn = firstAudio.parentElement.querySelector('button');
            if (btn) btn.click();
        }
    }
</script>
@endsection

