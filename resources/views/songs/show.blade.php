@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col overflow-y-auto pb-24 h-screen scroll-smooth">

    <!-- Header Section (Jamendo Layout) -->
    <div class="w-full flex flex-row gap-4 px-0 py-0 relative overflow-hidden group">
        <!-- Abstract Background Glow -->
        <!-- Abstract Background Glow Removed -->

        <!-- Track Cover (Left - Responsive) -->
        <div class="flex-shrink-0 w-24 h-24 md:w-[240px] md:h-[240px] relative z-10">
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
                    <button onclick="toggleLike('song', {{ $song->id }})" id="like-btn-song-desktop" class="px-4 py-2 flex items-center justify-center bg-[#1a2730] rounded-[3px] border border-[#53a1b3]/5 hover:border-red-500/20 transition-all {{ $song->likes()->where('user_id', auth()->id())->exists() ? 'text-red-500' : 'text-[#53a1b3]/40' }}">
                        <ion-icon id="like-icon-song-desktop" name="{{ $song->likes()->where('user_id', auth()->id())->exists() ? 'heart' : 'heart-outline' }}" class="w-4 h-4"></ion-icon>
                    </button>

                    <button onclick="shareTrack()" class="px-4 py-2 flex items-center justify-center bg-[#1a2730] rounded-[3px] border border-[#53a1b3]/5 text-[#53a1b3]/40 hover:text-white hover:border-white/10 transition-all">
                        <ion-icon name="share-social-outline" class="w-4 h-4"></ion-icon>
                    </button>

                    @auth
                    <button onclick="openPlaylistModal()" class="px-4 py-2 flex items-center justify-center bg-[#1a2730] rounded-[3px] border border-[#53a1b3]/5 text-[#53a1b3]/40 hover:text-white hover:border-white/10 transition-all">
                        <ion-icon name="add-outline" class="w-4 h-4"></ion-icon>
                    </button>
                    @endauth

                    @if(auth()->check() && $song->user_id === auth()->id())
                        <form id="delete-song-form-desktop" action="{{ route('songs.destroy', $song) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                data-confirm="Are you sure you want to delete this track? This cannot be undone."
                                data-confirm-title="Delete Track"
                                data-confirm-form="delete-song-form-desktop"
                                class="px-4 py-2 flex items-center justify-center bg-[#1a2730] rounded-[3px] border border-[#53a1b3]/5 text-[#53a1b3]/40 hover:text-red-500 hover:border-red-500/20 transition-all">
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
            <button onclick="toggleLike('song', {{ $song->id }})" id="like-btn-song-mobile" class="px-4 py-2 flex items-center justify-center bg-[#1a2730] rounded-[3px] border border-[#53a1b3]/5 hover:border-red-500/20 transition-all {{ $song->likes()->where('user_id', auth()->id())->exists() ? 'text-red-500' : 'text-[#53a1b3]/40' }}">
                <ion-icon id="like-icon-song-mobile" name="{{ $song->likes()->where('user_id', auth()->id())->exists() ? 'heart' : 'heart-outline' }}" class="w-4 h-4"></ion-icon>
            </button>

            <button onclick="shareTrack()" class="px-4 py-2 flex items-center justify-center bg-[#1a2730] rounded-[3px] border border-[#53a1b3]/5 text-[#53a1b3]/40 hover:text-white hover:border-white/10 transition-all">
                <ion-icon name="share-social-outline" class="w-4 h-4"></ion-icon>
            </button>

            @auth
            <button onclick="openPlaylistModal()" class="px-4 py-2 flex items-center justify-center bg-[#1a2730] rounded-[3px] border border-[#53a1b3]/5 text-[#53a1b3]/40 hover:text-white hover:border-white/10 transition-all">
                <ion-icon name="add-outline" class="w-4 h-4"></ion-icon>
            </button>
            @endauth

            @if(auth()->check() && $song->user_id === auth()->id())
                <form id="delete-song-form-mobile" action="{{ route('songs.destroy', $song) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                        data-confirm="Are you sure you want to delete this track? This cannot be undone."
                        data-confirm-title="Delete Track"
                        data-confirm-form="delete-song-form-mobile"
                        class="px-4 py-2 flex items-center justify-center bg-[#1a2730] rounded-[3px] border border-[#53a1b3]/5 text-[#53a1b3]/40 hover:text-red-500 hover:border-red-500/20 transition-all">
                        <ion-icon name="trash-outline" class="w-4 h-4"></ion-icon>
                    </button>
                </form>
            @endif
        </div>
    </div>


    <!-- Content Split Layout -->
    <div class="px-0 mt-6 mb-32">

        <div class="flex items-center gap-6 px-0">
            <button onclick="switchTab('track')" id="btn-tab-track" class="pb-2 text-[13px] font-normal uppercase border-b-2 border-[#e96c4c] text-white transition-all">COMMENTS</button>
            <button class="pb-2 text-[13px] font-normal uppercase border-b-2 border-transparent text-[#53a1b3]/20 hover:text-white transition-all cursor-not-allowed">LYRICS</button>
            <button onclick="switchTab('similar')" id="btn-tab-similar" class="pb-2 text-[13px] font-normal uppercase border-b-2 border-transparent text-[#53a1b3]/20 hover:text-white transition-all">SIMILAR TRACKS</button>
        </div>

        <!-- Main Content (2 Columns) -->
        <div class="flex flex-col lg:flex-row gap-2">

            <!-- LEFT: Comments & Reviews (65%) -->
            <div class="flex-1 min-w-0">

                <div id="content-track" class="tab-content transition-all duration-300">

                    <x-comments
                        type="song"
                        :model-id="$song->id"
                        :comments="$song->comments()->with('user')->latest()->get()"
                        list-id="comments-list-song"
                        placeholder="Write a review..."
                    />
                </div>

                <div id="content-similar" class="tab-content hidden animate-fade-in">
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-2 mt-2">
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

        async function togglePlaylist(playlistSlug, btn) {
            const songId = {{ $song->id }};
            const isInPlaylist = btn.getAttribute('data-in-playlist') === 'true';
            
            const iconAdd   = btn.querySelector('.icon-add');
            const iconCheck = btn.querySelector('.icon-check');
            const iconRemove = btn.querySelector('.icon-remove');

            if (isInPlaylist) {
                // --- REMOVE ---
                // Optimistic UI
                setPlaylistState(btn, false);

                try {
                    const response = await fetch(`/playlists/${playlistSlug}/songs/${songId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    const data = await response.json();
                    if (!data.success) {
                        setPlaylistState(btn, true); // Revert
                        alert(data.message || 'Failed to remove song');
                    }
                } catch (e) {
                    console.error(e);
                    setPlaylistState(btn, true); // Revert
                    alert('Failed to remove song');
                }
            } else {
                // --- ADD ---
                setPlaylistState(btn, true);

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
                    if (!data.success) {
                        setPlaylistState(btn, false); // Revert
                        alert(data.message || 'Failed to add song');
                    }
                } catch (e) {
                    console.error(e);
                    setPlaylistState(btn, false); // Revert
                    alert('Failed to add song');
                }
            }
        }

        function setPlaylistState(btn, inPlaylist) {
            const iconAdd    = btn.querySelector('.icon-add');
            const iconCheck  = btn.querySelector('.icon-check');
            const iconRemove = btn.querySelector('.icon-remove');

            btn.setAttribute('data-in-playlist', inPlaylist ? 'true' : 'false');

            if (inPlaylist) {
                iconAdd.style.display    = 'none';
                iconCheck.style.display  = 'block';
                iconRemove.style.display = 'none';

                // Hover: show minus, hide check
                btn.onmouseenter = () => {
                    iconCheck.style.display  = 'none';
                    iconRemove.style.display = 'block';
                };
                btn.onmouseleave = () => {
                    iconCheck.style.display  = 'block';
                    iconRemove.style.display = 'none';
                };
            } else {
                iconAdd.style.display    = 'block';
                iconCheck.style.display  = 'none';
                iconRemove.style.display = 'none';
                btn.onmouseenter = null;
                btn.onmouseleave = null;
            }
        }

        // Initialize states on page load
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('#playlist-list-container button[data-in-playlist]').forEach(btn => {
                const inPlaylist = btn.getAttribute('data-in-playlist') === 'true';
                setPlaylistState(btn, inPlaylist);
            });
        });

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
                    // Update UI Dynamically
                    const container = document.getElementById('playlist-list-container');
                    const nomsg = document.getElementById('no-playlists-msg');
                    if (nomsg) nomsg.classList.add('hidden');

                    const btn = document.createElement('button');
                    btn.setAttribute('onclick', `togglePlaylist('${createData.slug}', this)`);
                    btn.setAttribute('data-in-playlist', 'false');
                    btn.className = 'w-full h-[35px] flex items-center justify-between px-2 bg-[#1a2730]/40 border border-[#53a1b3]/20 hover:border-[#e96c4c] transition text-left group rounded-[3px]';
                    btn.innerHTML = `
                        <div class="flex items-center gap-3 overflow-hidden">
                            <ion-icon name="list-outline" class="w-4 h-4 text-[#53a1b3] group-hover:text-[#e96c4c] shrink-0"></ion-icon>
                            <span class="text-white text-xs font-normal truncate">${createData.name}</span>
                        </div>
                        <div class="relative w-4 h-4 flex items-center justify-center">
                            <ion-icon name="add" class="w-5 h-5 text-[#53a1b3] group-hover:text-[#e96c4c] transition icon-add"></ion-icon>
                            <ion-icon name="checkmark" class="w-5 h-5 text-green-500 transition hidden icon-check group-hover:opacity-0 absolute inset-0"></ion-icon>
                            <ion-icon name="remove" class="w-5 h-5 text-red-500 transition hidden icon-remove group-hover:opacity-100 absolute inset-0"></ion-icon>
                        </div>
                    `;
                    
                    // Insert at top
                    if (container.firstChild) {
                        container.insertBefore(btn, container.firstChild);
                    } else {
                        container.appendChild(btn);
                    }

                    // Initialize icon state & hover events
                    setPlaylistState(btn, false);
                    
                    // Clear input
                    nameInput.value = '';
                    container.scrollTop = 0;

                } else {
                    alert(createData.message || 'Error creating playlist');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred');
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerText = 'Create';
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
                <div id="playlist-list-container" class="space-y-2 max-h-[200px] overflow-y-auto">
                    @php
                        $userPlaylists = auth()->user()->playlists->merge(auth()->user()->collaboratedPlaylists);
                    @endphp

                    @forelse($userPlaylists as $playlist)
                    @php
                        $isInPlaylist = $playlist->songs->contains($song->id);
                    @endphp
                    <button onclick="togglePlaylist('{{ $playlist->slug }}', this)" 
                            data-in-playlist="{{ $isInPlaylist ? 'true' : 'false' }}"
                            class="w-full h-[35px] flex items-center justify-between px-2 bg-[#1a2730]/40 border border-[#53a1b3]/20 hover:border-[#e96c4c] transition text-left group rounded-[3px]">
                        
                        <div class="flex items-center gap-3 overflow-hidden">
                            <ion-icon name="list-outline" class="w-4 h-4 text-[#53a1b3] group-hover:text-[#e96c4c] shrink-0"></ion-icon>
                            <span class="text-white text-xs font-normal truncate">{{ $playlist->name }}</span>
                        </div>

                        <!-- Status Icon -->
                        <div class="relative w-4 h-4 flex items-center justify-center">
                            <!-- Plus Icon (Default) -->
                            <ion-icon name="add" class="w-5 h-5 text-[#53a1b3] group-hover:text-[#e96c4c] transition {{ $isInPlaylist ? 'hidden' : '' }} icon-add"></ion-icon>
                            
                            <!-- Tick Icon (In Playlist) -->
                            <ion-icon name="checkmark" class="w-5 h-5 text-green-500 transition {{ $isInPlaylist ? '' : 'hidden' }} icon-check group-hover:opacity-0 absolute inset-0"></ion-icon>
                            
                            <!-- Minus Icon (Hover on In Playlist) -->
                            <ion-icon name="remove" class="w-5 h-5 text-red-500 transition hidden icon-remove group-hover:opacity-100 absolute inset-0 {{ $isInPlaylist ? 'group-hover:block' : '' }}"></ion-icon>
                        </div>
                    </button>
                    @empty
                    <p id="no-playlists-msg" class="text-[#53a1b3]/50 text-xs text-center py-2">No playlists found</p>
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
