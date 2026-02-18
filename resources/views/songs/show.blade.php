@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col overflow-y-auto pb-24 h-screen scroll-smooth">

    <!-- Header Section (Jamendo Layout) -->
    <div class="w-full flex flex-row gap-4 px-0 py-0 relative overflow-hidden group">
        <!-- Abstract Background Glow -->
        <!-- Abstract Background Glow Removed -->

        <!-- Track Cover (Left - Responsive) -->
        <div class="flex-shrink-0 w-24 h-24 md:w-[240px] md:h-[240px] relative shadow-lg md:shadow-[0_20px_50px_rgba(0,0,0,0.5)] z-10">
            @if($song->cover_path)
                <img src="{{ Storage::url($song->cover_path) }}" alt="{{ $song->title }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-[#1a2730] flex items-center justify-center border border-[#53a1b3]/10">
                    <ion-icon name="musical-notes-outline" class="w-16 h-16 text-[#53a1b3]/10"></ion-icon>
                </div>
            @endif
        </div>

        <!-- Track Data (Right) -->
        <div class="flex-1 md:flex-1 flex flex-col justify-start pt-1 md:pt-3 z-10 min-w-0">
            <span class="hidden md:block text-[10px] font-normal text-[#53a1b3]/40 uppercase">TRACK</span>
            <h1 class="text-[24px] font-normal text-white uppercase tracking-tighter leading-tight truncate">{{ $song->title }}</h1>
            <div class="flex items-center gap-3">
                <span class="text-[14px] font-normal text-white/90 uppercase">{{ $song->artist }}</span>
                <ion-icon name="checkmark-seal" class="w-5 h-5 text-[#e96c4c]"></ion-icon>
            </div>

            <!-- Audio Element -->
            <audio id="audio-player" src="{{ Storage::url($song->file_path) }}"></audio>

            <!-- Genre Tags -->
            <div class="flex flex-wrap gap-2.5 opacity-30">
                @php $tags = ['#bongo', '#vibes', '#premium', '#afrobeat', '#tz', '#new']; @endphp
                @foreach($tags as $tag)
                    <span class="text-[10px] font-normal text-[#53a1b3] uppercase hover:text-white transition cursor-default">{{ $tag }}</span>
                @endforeach
            </div>

            @if($song->album)
            <div class="flex items-center gap-2">
                <span class="text-[10px] text-[#53a1b3]/30 uppercase">From the album:</span>
                <a href="{{ route('albums.show', $song->album) }}" class="text-[10px] text-[#e96c4c] uppercase hover:underline hover:text-[#e96c4c]/80 transition">{{ $song->album->title }}</a>
            </div>
            @endif

            <!-- Player Interaction -->
            <div class="hidden md:flex flex-wrap items-center gap-2 mt-auto">
                <!-- Play Button -->
                <button id="play-btn-header" onclick="togglePlay()" class="px-4 py-2 bg-[#e96c4c] text-white flex items-center justify-center gap-2 hover:bg-[#e96c4c]/90 transition-all rounded-[3px] text-xs uppercase">
                    <ion-icon id="play-status-icon" name="play" class="w-4 h-4"></ion-icon>
                    <span class="pt-px hidden md:inline">PLAY</span>
                </button>

                <!-- Download Button -->
                <a href="{{ route('songs.download', $song) }}" class="px-4 py-2 bg-[#1a2730] border border-[#53a1b3]/10 text-[#53a1b3]/70 flex items-center justify-center gap-2 hover:bg-[#53a1b3]/5 hover:text-white transition-all rounded-[3px] text-xs uppercase">
                    <ion-icon name="cloud-download-outline" class="w-4 h-4"></ion-icon>
                    <span class="pt-px hidden md:inline">FREE DOWNLOAD</span>
                </a>

                <!-- Icons Group -->
                <div class="flex items-center gap-2 ml-0 md:ml-2">
                    <form action="{{ route('interactions.like', ['type' => 'song', 'id' => $song->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 flex items-center justify-center bg-[#1a2730] rounded-[3px] border border-[#53a1b3]/5 text-[#53a1b3]/40 hover:text-red-500 hover:border-red-500/20 transition-all">
                            <ion-icon name="{{ $song->likes()->where('user_id', auth()->id())->exists() ? 'heart' : 'heart-outline' }}" class="w-4 h-4 {{ $song->likes()->where('user_id', auth()->id())->exists() ? 'text-red-500' : '' }}"></ion-icon>
                        </button>
                    </form>

                    <button onclick="shareTrack()" class="px-4 py-2 flex items-center justify-center bg-[#1a2730] rounded-[3px] border border-[#53a1b3]/5 text-[#53a1b3]/40 hover:text-white hover:border-white/10 transition-all">
                        <ion-icon name="share-social-outline" class="w-4 h-4"></ion-icon>
                    </button>

                    @auth
                    <button onclick="openPlaylistModal()" class="px-4 py-2 flex items-center justify-center bg-[#1a2730] rounded-[3px] border border-[#53a1b3]/5 text-[#53a1b3]/40 hover:text-white hover:border-white/10 transition-all">
                        <ion-icon name="add-outline" class="w-4 h-4"></ion-icon>
                    </button>
                    @endauth

                    @if(auth()->check() && $song->user_id === auth()->id())
                        <form action="{{ route('songs.destroy', $song) }}" method="POST" onsubmit="return confirm('Delete this track?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 flex items-center justify-center bg-[#1a2730] rounded-[3px] border border-[#53a1b3]/5 text-[#53a1b3]/40 hover:text-red-500 hover:border-red-500/20 transition-all">
                                <ion-icon name="trash-outline" class="w-4 h-4"></ion-icon>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Buttons (Bottom) -->
    <div class="flex md:hidden flex-wrap items-center gap-2 px-0 mt-2">
        <!-- Play Button -->
        <button id="play-btn-header-mobile" onclick="togglePlay()" class="px-4 py-2 bg-[#e96c4c] text-white flex items-center justify-center hover:bg-[#e96c4c]/90 transition-all rounded-[3px]">
            <ion-icon id="play-status-icon-mobile" name="play" class="w-4 h-4"></ion-icon>
        </button>

        <!-- Download Button -->
        <a href="{{ route('songs.download', $song) }}" class="px-4 py-2 bg-[#1a2730] border border-[#53a1b3]/10 text-[#53a1b3]/70 flex items-center justify-center hover:bg-[#53a1b3]/5 hover:text-white transition-all rounded-[3px]">
            <ion-icon name="cloud-download-outline" class="w-4 h-4"></ion-icon>
        </a>

        <!-- Icons Group Mobile -->
        <div class="flex items-center gap-2 ml-auto">
            <form action="{{ route('interactions.like', ['type' => 'song', 'id' => $song->id]) }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 flex items-center justify-center bg-[#1a2730] rounded-[3px] border border-[#53a1b3]/5 text-[#53a1b3]/40 hover:text-red-500 hover:border-red-500/20 transition-all">
                    <ion-icon name="{{ $song->likes()->where('user_id', auth()->id())->exists() ? 'heart' : 'heart-outline' }}" class="w-4 h-4 {{ $song->likes()->where('user_id', auth()->id())->exists() ? 'text-red-500' : '' }}"></ion-icon>
                </button>
            </form>

            <button onclick="shareTrack()" class="px-4 py-2 flex items-center justify-center bg-[#1a2730] rounded-[3px] border border-[#53a1b3]/5 text-[#53a1b3]/40 hover:text-white hover:border-white/10 transition-all">
                <ion-icon name="share-social-outline" class="w-4 h-4"></ion-icon>
            </button>

            @auth
            <button onclick="openPlaylistModal()" class="px-4 py-2 flex items-center justify-center bg-[#1a2730] rounded-[3px] border border-[#53a1b3]/5 text-[#53a1b3]/40 hover:text-white hover:border-white/10 transition-all">
                <ion-icon name="add-outline" class="w-4 h-4"></ion-icon>
            </button>
            @endauth

            @if(auth()->check() && $song->user_id === auth()->id())
                <form action="{{ route('songs.destroy', $song) }}" method="POST" onsubmit="return confirm('Delete this track?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 flex items-center justify-center bg-[#1a2730] rounded-[3px] border border-[#53a1b3]/5 text-[#53a1b3]/40 hover:text-red-500 hover:border-red-500/20 transition-all">
                        <ion-icon name="trash-outline" class="w-4 h-4"></ion-icon>
                    </button>
                </form>
            @endif
        </div>
    </div>


    <!-- Content Split Layout -->
    <div class="px-0 mt-6 mb-32">

        <div class="flex items-center gap-6 px-0">
            <button onclick="switchTab('track')" id="btn-tab-track" class="pb-2 text-[13px] font-normal uppercase border-b-2 border-[#e96c4c] text-white transition-all">TRACK</button>
            <button class="pb-2 text-[13px] font-normal uppercase border-b-2 border-transparent text-[#53a1b3]/20 hover:text-white transition-all cursor-not-allowed">LYRICS</button>
            <button onclick="switchTab('similar')" id="btn-tab-similar" class="pb-2 text-[13px] font-normal uppercase border-b-2 border-transparent text-[#53a1b3]/20 hover:text-white transition-all">SIMILAR TRACKS</button>
        </div>

        <!-- Main Content (2 Columns) -->
        <div class="flex flex-col lg:flex-row gap-2">

            <!-- LEFT: Comments & Reviews (65%) -->
            <div class="flex-1 min-w-0">

                <div id="content-track" class="tab-content transition-all duration-300">

                    <!-- Review Input -->
                    @auth
                    <form action="{{ route('interactions.comment', ['type' => 'song', 'id' => $song->id]) }}" method="POST" class="mt-2">
                        @csrf
                        <div class="flex gap-2">
                            <textarea name="body" id="comment-body" rows="1" maxlength="250" oninput="updateCharCount()"
                                class="flex-1 bg-[#1a2730]/40 border border-[#53a1b3]/10 text-white/80 text-[12px] p-2 focus:outline-none focus:border-[#e96c4c]/30 transition-all placeholder-[#53a1b3]/10 resize-none max-h-[35px] font-light leading-tight rounded-[3px]"
                                placeholder="Write a review..."></textarea>

                            <button type="submit" class="px-4 py-2 flex items-center justify-center bg-[#e96c4c] rounded-[3px] border border-[#e96c4c] text-white hover:bg-[#e96c4c]/90 transition shrink-0">
                                <ion-icon name="arrow-up" class="w-4 h-4"></ion-icon>
                            </button>
                        </div>
                        <div class="mt-1 text-right">
                            <span class="text-[9px] text-[#53a1b3]/30"><span id="char-count">250</span></span>
                        </div>
                    </form>
                    @else
                    <div class="">
                        <p class="text-[#53a1b3]/30 text-[10px] uppercase tracking-[0.2em]">Please <a href="{{ route('login') }}" class="text-[#e96c4c] hover:underline">login</a> to leave a review</p>
                    </div>
                    @endauth

                    <!-- Comments Loop -->
                    <div class="space-y-2">
                        @forelse($song->comments()->latest()->get() as $comment)
                        <div class="overflow-hidden">
                            <div class="flex gap-2">
                                <!-- Avatar Square -->
                                <div class="w-10 h-10 bg-[#53a1b3]/10 flex items-center justify-center shrink-0">
                                    <span class="text-[#53a1b3]/40 text-sm font-normal uppercase">{{ substr($comment->user->name, 0, 1) }}</span>
                                </div>

                                <!-- Comment Content -->
                                <div class="flex-1 min-w-0 overflow-hidden">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-[#e96c4c] text-[11px] font-normal uppercase truncate">{{ $comment->user->name }}</span>
                                        <div class="flex items-center gap-2 shrink-0 ml-2">
                                            <span class="text-[#53a1b3]/30 text-[9px] uppercase">{{ $comment->created_at->diffForHumans(['short' => true]) }}</span>
                                            @if(auth()->check() && auth()->id() === $comment->user_id)
                                                <form action="{{ route('interactions.deleteComment', $comment) }}" method="POST" onsubmit="return confirm('Delete this comment?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-[#53a1b3]/30 hover:text-red-500 transition">
                                                        <ion-icon name="trash-outline" class="w-3.5 h-3.5"></ion-icon>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-white/90 text-[13px] leading-relaxed font-light break-all overflow-hidden">
                                        {{ $comment->body }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="py-12 text-center">
                            <p class="text-[#53a1b3]/20 text-[10px] uppercase">No comments yet</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div id="content-similar" class="tab-content hidden animate-fade-in">
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-8">
                        @foreach($relatedSongs as $relatedSong)
                        <a href="{{ route('songs.show', $relatedSong) }}" class="group">
                             <div class="aspect-square bg-[#1a2730] border border-[#53a1b3]/5 overflow-hidden relative shadow-lg">
                                 @if($relatedSong->cover_path)
                                     <img src="{{ Storage::url($relatedSong->cover_path) }}" alt="{{ $relatedSong->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                 @else
                                     <div class="w-full h-full flex items-center justify-center text-[#53a1b3]/10">
                                         <ion-icon name="musical-notes" class="w-12 h-12"></ion-icon>
                                     </div>
                                 @endif
                                 <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                     <div class="w-12 h-12 bg-[#e96c4c] rounded-full flex items-center justify-center text-white scale-0 group-hover:scale-100 transition delay-75 shadow-lg">
                                         <ion-icon name="play" class="w-6 h-6 ml-0.5"></ion-icon>
                                     </div>
                                 </div>
                             </div>
                             <h4 class="text-white text-xs font-normal uppercase line-clamp-1 group-hover:text-[#e96c4c] transition">{{ $relatedSong->title }}</h4>
                             <p class="text-[#53a1b3]/40 text-[10px] uppercase line-clamp-1 font-light">{{ $relatedSong->artist }}</p>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- RIGHT: Stats, Description & Share (35%) -->
            <div class="w-full lg:w-[360px] shrink-0">

                <!-- Main Metadata Block -->
                <div class="border border-[#53a1b3]/5 p-2">
                     <div class="flex items-center justify-between gap-2 pb-2">
                        <div class="flex items-center gap-3 text-[10px] text-[#53a1b3]/30 uppercase">
                            <ion-icon name="calendar-outline" class="w-4 h-4"></ion-icon>
                            <span>{{ $song->created_at->format('m/d/Y') }}</span>
                        </div>

                        <div class="flex gap-2">
                            <div class="text-right">
                                 <span class="block text-sm text-white leading-none font-normal tracking-tight">{{ number_format($song->downloads * 13) }}</span>
                                 <span class="block text-[8px] text-[#53a1b3]/20 uppercase font-light">LISTENS</span>
                            </div>
                            <div class="text-right">
                                 <span class="block text-sm text-white leading-none font-normal tracking-tight">{{ $song->likes->count() }}</span>
                                 <span class="block text-[8px] text-[#53a1b3]/20 uppercase font-light">FAVS</span>
                            </div>
                            <div class="text-right">
                                 <span class="block text-sm text-white leading-none font-normal tracking-tight">{{ number_format($song->downloads) }}</span>
                                 <span class="block text-[8px] text-[#53a1b3]/20 uppercase font-light">SAVE</span>
                            </div>
                        </div>
                    </div>

                    <!-- Description Segment -->
                    <div>
                         <h4 class="text-[10px] text-[#53a1b3]/20 uppercase font-normal">DESCRIPTION</h4>
                         <p class="text-[#c5d2d9]/70 text-[13px] leading-relaxed font-light">
                             "{{ $song->description ?? 'We are still processing the track details. Stay tuned for updates on this release.' }}"
                         </p>
                    </div>

                    <!-- Credits Segment -->
                    <div>
                         <h4 class="text-[10px] text-[#53a1b3]/20 uppercase font-normal">CREDITS</h4>
                         <div class="space-y-2">
                             <div class="flex flex-col">
                                 <span class="text-[9px] text-[#53a1b3]/10 uppercase font-light">Original Artist</span>
                                 <span class="text-xs text-white/70 uppercase font-normal">{{ $song->artist }}</span>
                             </div>
                             <div class="flex flex-col">
                                 <span class="text-[9px] text-[#53a1b3]/10 uppercase font-light">Technical Specs</span>
                                 <div class="flex items-center gap-3">
                                 <span class="text-[9px] text-[#e96c4c]/40 border border-[#e96c4c]/10 px-2 py-0.5 uppercase">HQ Audio</span>
                                     <span class="text-[9px] text-white/20 border border-white/5 px-2 py-0.5 uppercase">MP3</span>
                                 </div>
                             </div>
                             <div class="flex flex-col">
                                 <span class="text-[9px] text-[#53a1b3]/10 uppercase font-light">File Info</span>
                                 <span class="text-xs text-white/70 uppercase font-normal">
                                    @if($song->file_path && Storage::disk('public')->exists($song->file_path))
                                        {{ round(Storage::disk('public')->size($song->file_path) / 1048576, 1) }} MB
                                    @endif
                                 </span>
                             </div>
                         </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script>
        // Tab Switching
        function switchTab(tab) {
            const tabs = {
                'track': { btn: 'btn-tab-track', content: 'content-track' },
                'similar': { btn: 'btn-tab-similar', content: 'content-similar' }
            };

            Object.keys(tabs).forEach(key => {
                const b = document.getElementById(tabs[key].btn);
                const c = document.getElementById(tabs[key].content);
                if (b && c) {
                    if (key === tab) {
                        b.classList.add('border-[#e96c4c]', 'text-white');
                        b.classList.remove('border-transparent', 'text-[#53a1b3]/20');
                        c.classList.remove('hidden');
                    } else {
                        b.classList.remove('border-[#e96c4c]', 'text-white');
                        b.classList.add('border-transparent', 'text-[#53a1b3]/20');
                        c.classList.add('hidden');
                    }
                }
            });
        }

        // Comment Character Count
        function updateCharCount() {
            const textarea = document.getElementById('comment-body');
            const countDisplay = document.getElementById('char-count');
            if (textarea && countDisplay) {
                countDisplay.innerText = 250 - textarea.value.length;
            }
        }

        // Audio Player Logic
        const audio = document.getElementById('audio-player');
        const playStatusIcon = document.getElementById('play-status-icon');
        const playStatusIconBottom = document.getElementById('play-status-icon-bottom');
        const playStatusIconMobile = document.getElementById('play-status-icon-mobile');

        function togglePlay() {
            if (!audio) return;

            if (audio.paused) {
                const playPromise = audio.play();
                if (playPromise !== undefined) {
                    playPromise.then(_ => {
                        if (playStatusIcon) playStatusIcon.setAttribute('name', 'pause');
                        if (playStatusIconBottom) playStatusIconBottom.setAttribute('name', 'pause');
                        if (playStatusIconMobile) playStatusIconMobile.setAttribute('name', 'pause');

                        // Update global player
                        if (window.globalPlayer) {
                            const songData = {
                                title: '{{ $song->title }}',
                                artist: '{{ $song->artist }}',
                                cover: '{{ $song->cover_path ? Storage::url($song->cover_path) : '' }}'
                            };
                            const button = document.getElementById('play-btn-header') || document.getElementById('play-btn-header-mobile');
                            window.globalPlayer.show(audio, songData, button);
                        }
                    }).catch(error => {
                        // Auto-play was prevented
                    });
                }
            } else {
                audio.pause();
                if (playStatusIcon) playStatusIcon.setAttribute('name', 'play');
                if (playStatusIconBottom) playStatusIconBottom.setAttribute('name', 'play');
                if (playStatusIconMobile) playStatusIconMobile.setAttribute('name', 'play');
            }
        }

        if (audio) {
            audio.addEventListener('ended', () => {
                if (playStatusIcon) playStatusIcon.setAttribute('name', 'play');
                if (playStatusIconBottom) playStatusIconBottom.setAttribute('name', 'play');
                if (playStatusIconMobile) playStatusIconMobile.setAttribute('name', 'play');
            });
        }

        // Share functionality
        function shareTrack() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $song->title }}',
                    text: 'Listen to {{ $song->title }} by {{ $song->artist }}',
                    url: window.location.href
                });
            } else {
                const dummy = document.createElement('input');
                document.body.appendChild(dummy);
                dummy.value = window.location.href;
                dummy.select();
                try {
                    document.execCommand('copy');
                    alert('Link copied to clipboard!');
                } catch (err) {
                    console.error('Copy failed', err);
                }
                document.body.removeChild(dummy);
            }
        }

        // Playlist Modal Functions
        function openPlaylistModal() {
            document.getElementById('playlist-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePlaylistModal() {
            document.getElementById('playlist-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        async function addToPlaylist(playlistSlug) {
            const songId = {{ $song->id }};

            try {
                const response = await fetch(`/playlists/${playlistSlug}/songs`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ song_id: songId })
                });

                const data = await response.json();
                if(data.success) {
                    alert('Song added to playlist!');
                    closePlaylistModal();
                } else {
                    alert(data.message || 'Failed to add song to playlist');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred');
            }
        }

        async function createNewPlaylistInline() {
            const nameInput = document.getElementById('inline-playlist-name');
            const name = nameInput.value.trim();
            if (!name) return;

            const saveBtn = nameInput.nextElementSibling;
            saveBtn.disabled = true;
            saveBtn.innerText = '...';

            try {
                // 1. Create Playlist
                const createResponse = await fetch('/playlists', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ name: name, is_public: 1 })
                });

                const createData = await createResponse.json();

                if (createResponse.ok && createData.slug) {
                    // 2. Add current song to newly created playlist
                    await addToPlaylist(createData.slug);
                } else {
                    alert(createData.message || 'Error creating playlist');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred');
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerText = 'Save';
            }
        }
    </script>

    <!-- Playlist Modal -->
    @auth
    <!-- Playlist Modal -->
    <div id="playlist-modal" onclick="if(event.target === this) closePlaylistModal()" class="hidden fixed inset-0 bg-black/80 z-[100] flex items-start justify-center pt-64 p-4 backdrop-blur-sm transition-opacity">
        <div onclick="event.stopPropagation()" class="w-[350px] bg-[#1a2730] shadow-2xl rounded-[3px] overflow-hidden border border-[#53a1b3]/10 flex flex-col">
            
            <!-- Header -->
            <div class="px-2 pt-2 border-b border-[#53a1b3]/10 mb-2">
                <div class="inline-block relative pb-2 text-sm font-medium text-white uppercase tracking-wider">
                    Add to Playlist
                    <div class="absolute bottom-[-1px] left-0 w-full h-[2px] bg-[#e96c4c]"></div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-2 space-y-2 flex-1">
                <!-- Create Inline -->
                <div class="flex items-center gap-2 mb-4">
                    <input type="text" id="inline-playlist-name" placeholder="New Playlist Name" 
                        class="flex-1 h-[35px] bg-[#1a2730]/40 border border-[#53a1b3]/20 text-white text-xs px-2 rounded-[3px] focus:outline-none focus:border-[#e96c4c] placeholder-[#53a1b3]/50 transition">
                    <button onclick="createNewPlaylistInline()" class="h-[35px] px-4 bg-[#e96c4c] text-white rounded-[3px] text-xs font-medium uppercase tracking-wider hover:bg-[#d45a3a] transition shadow-lg shadow-[#e96c4c]/20">
                        Create
                    </button>
                </div>

                <!-- Playlist List -->
                <div class="space-y-2 max-h-[200px] overflow-y-auto">
                    @php
                        $userPlaylists = auth()->user()->playlists->merge(auth()->user()->collaboratedPlaylists);
                    @endphp

                    @forelse($userPlaylists as $playlist)
                    <button onclick="addToPlaylist('{{ $playlist->slug }}')" class="w-full h-[35px] flex items-center gap-3 px-2 bg-[#1a2730]/40 border border-[#53a1b3]/20 hover:border-[#e96c4c] hover:text-[#e96c4c] transition text-left group rounded-[3px]">
                        <ion-icon name="list-outline" class="w-4 h-4 text-[#53a1b3] group-hover:text-[#e96c4c]"></ion-icon>
                        <span class="text-white text-xs font-normal truncate">{{ $playlist->name }}</span>
                    </button>
                    @empty
                    <p class="text-[#53a1b3]/50 text-xs text-center py-2">No playlists found</p>
                    @endforelse
                </div>
            </div>

            <!-- Footer -->
            <div class="p-2 border-t border-[#53a1b3]/10 flex justify-end mt-2">
                <button onclick="closePlaylistModal()" class="px-4 py-2 border border-[#e96c4c] text-[#e96c4c] hover:bg-[#e96c4c] hover:text-white text-xs font-medium uppercase tracking-wider rounded-[3px] transition shadow-lg shadow-[#e96c4c]/5">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endauth
</div>
@endsection
