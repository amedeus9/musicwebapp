@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col overflow-y-auto pb-24 h-screen scroll-smooth">

    <!-- Header Section (Jamendo Layout) -->
    <div class="w-full flex flex-row gap-4 px-0 py-0 relative overflow-hidden group">
        
        <!-- Cover (Left - Responsive) -->
        <div class="flex-shrink-0 w-24 h-24 md:w-[240px] md:h-[240px] relative shadow-lg md:shadow-[0_20px_50px_rgba(0,0,0,0.5)] z-10">
            @if($playlist->cover_path)
                <img src="{{ Storage::url($playlist->cover_path) }}" alt="{{ $playlist->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-[#1a2730] flex items-center justify-center border border-[#53a1b3]/10">
                    <ion-icon name="list-outline" class="w-16 h-16 text-[#53a1b3]/10"></ion-icon>
                </div>
            @endif
        </div>

        <!-- Data (Right) -->
        <div class="flex-1 md:flex-1 flex flex-col justify-start pt-1 md:pt-3 z-10 min-w-0">
            <span class="hidden md:block text-[10px] font-normal text-[#53a1b3]/40 uppercase">PLAYLIST</span>
            <h1 class="text-[24px] font-normal text-white uppercase tracking-tighter leading-tight truncate">{{ $playlist->name }}</h1>
            <div class="flex items-center gap-3">
                <span class="text-[14px] font-normal text-white/90 uppercase">{{ $playlist->user->name }}</span>
                @if(!$playlist->is_public)
                <div class="flex items-center gap-1 bg-[#1a2730] px-2 py-0.5 rounded-[3px] border border-[#53a1b3]/10">
                    <ion-icon name="lock-closed" class="w-3 h-3 text-[#e96c4c]"></ion-icon>
                    <span class="text-[10px] text-[#53a1b3] uppercase">Private</span>
                </div>
                @endif
            </div>

            <!-- Tags/Stats -->
            <div class="flex flex-wrap gap-2.5 opacity-30 mt-2">
                <span class="text-[10px] font-normal text-[#53a1b3] uppercase cursor-default">{{ $playlist->songs()->count() }} Tracks</span>
            </div>

            <!-- Interaction -->
            <div class="hidden md:flex flex-wrap items-center gap-2 mt-auto">
                <!-- Play Button (Plays first song if available) -->
                @if($playlist->songs->count() > 0)
                <button onclick="playFirstSong()" class="px-4 py-2 bg-[#e96c4c] text-white flex items-center justify-center gap-2 hover:bg-[#e96c4c]/90 transition-all rounded-[3px] text-xs uppercase">
                    <ion-icon name="play" class="w-4 h-4"></ion-icon>
                    <span class="pt-px hidden md:inline">PLAY ALL</span>
                </button>
                @else
                <button disabled class="px-4 py-2 bg-[#1a2730] text-[#53a1b3]/50 flex items-center justify-center gap-2 cursor-not-allowed rounded-[3px] text-xs uppercase border border-[#53a1b3]/10">
                    <ion-icon name="play" class="w-4 h-4"></ion-icon>
                    <span class="pt-px hidden md:inline">EMPTY</span>
                </button>
                @endif

                <!-- Owner Actions -->
                @auth
                @if(Auth::id() === $playlist->user_id)
                    <a href="{{ route('playlists.edit', $playlist->slug) }}" class="px-4 py-2 bg-[#1a2730] border border-[#53a1b3]/10 text-[#53a1b3]/70 flex items-center justify-center gap-2 hover:bg-[#53a1b3]/5 hover:text-white transition-all rounded-[3px] text-xs uppercase">
                        <ion-icon name="create-outline" class="w-4 h-4"></ion-icon>
                        <span class="pt-px hidden md:inline">EDIT</span>
                    </a>

                    <form action="{{ route('playlists.destroy', $playlist->slug) }}" method="POST" onsubmit="return confirm('Delete this playlist?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-[#1a2730] border border-[#53a1b3]/10 text-[#53a1b3]/70 flex items-center justify-center gap-2 hover:bg-red-500/10 hover:text-red-500 hover:border-red-500/20 transition-all rounded-[3px] text-xs uppercase">
                            <ion-icon name="trash-outline" class="w-4 h-4"></ion-icon>
                            <span class="pt-px hidden md:inline">DELETE</span>
                        </button>
                    </form>
                @endif
                @endauth

                <!-- Share -->
                <button onclick="sharePlaylist()" class="px-4 py-2 flex items-center justify-center bg-[#1a2730] rounded-[3px] border border-[#53a1b3]/5 text-[#53a1b3]/40 hover:text-white hover:border-white/10 transition-all">
                    <ion-icon name="share-social-outline" class="w-4 h-4"></ion-icon>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Buttons (Bottom) -->
    <div class="flex md:hidden flex-wrap items-center gap-2 px-0 mt-2">
        @if($playlist->songs->count() > 0)
        <button onclick="playFirstSong()" class="px-4 py-2 bg-[#e96c4c] text-white flex items-center justify-center hover:bg-[#e96c4c]/90 transition-all rounded-[3px]">
            <ion-icon name="play" class="w-4 h-4"></ion-icon>
        </button>
        @endif

        <div class="flex items-center gap-2 ml-auto">
            <button onclick="sharePlaylist()" class="px-4 py-2 flex items-center justify-center bg-[#1a2730] rounded-[3px] border border-[#53a1b3]/5 text-[#53a1b3]/40 hover:text-white hover:border-white/10 transition-all">
                <ion-icon name="share-social-outline" class="w-4 h-4"></ion-icon>
            </button>

            @auth
            @if(Auth::id() === $playlist->user_id)
                <a href="{{ route('playlists.edit', $playlist->slug) }}" class="px-4 py-2 flex items-center justify-center bg-[#1a2730] rounded-[3px] border border-[#53a1b3]/5 text-[#53a1b3]/40 hover:text-white hover:border-white/10 transition-all">
                    <ion-icon name="create-outline" class="w-4 h-4"></ion-icon>
                </a>
                
                <form action="{{ route('playlists.destroy', $playlist->slug) }}" method="POST" onsubmit="return confirm('Delete this playlist?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 flex items-center justify-center bg-[#1a2730] rounded-[3px] border border-[#53a1b3]/5 text-[#53a1b3]/40 hover:text-red-500 hover:border-red-500/20 transition-all">
                        <ion-icon name="trash-outline" class="w-4 h-4"></ion-icon>
                    </button>
                </form>
            @endif
            @endauth
        </div>
    </div>

    <!-- Content Split Layout -->
    <div class="px-0 mt-6 mb-32">

        <div class="flex items-center gap-6 px-0">
            <button onclick="switchTab('tracks')" id="btn-tab-tracks" class="pb-2 text-[13px] font-normal uppercase border-b-2 border-[#e96c4c] text-white transition-all">TRACKS</button>
            <button onclick="switchTab('comments')" id="btn-tab-comments" class="pb-2 text-[13px] font-normal uppercase border-b-2 border-transparent text-[#53a1b3]/20 hover:text-white transition-all">COMMENTS</button>
            @if(Auth::id() === $playlist->user_id)
            <button onclick="switchTab('collaborators')" id="btn-tab-collaborators" class="pb-2 text-[13px] font-normal uppercase border-b-2 border-transparent text-[#53a1b3]/20 hover:text-white transition-all">COLLABORATORS</button>
            @endif
        </div>

        <!-- Main Content (2 Columns) -->
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- LEFT: Content (65%) -->
            <div class="flex-1 min-w-0">

                <!-- TRACKS TAB -->
                <div id="content-tracks" class="tab-content transition-all duration-300">
                    <div class="space-y-1 mt-2">
                        @if($playlist->songs->isEmpty())
                            <div class="py-12 text-center border border-[#53a1b3]/5 rounded-[3px] bg-[#1a2730]/20">
                                <ion-icon name="musical-notes-outline" class="w-8 h-8 text-[#53a1b3]/20 mb-2"></ion-icon>
                                <p class="text-[#53a1b3]/40 text-xs uppercase tracking-wider">No songs in this playlist</p>
                            </div>
                        @else
                            @foreach($playlist->songs()->orderBy('position')->get() as $index => $song)
                            <div class="flex items-center gap-3 p-2 hover:bg-[#1a2834] transition group rounded-[3px] border border-transparent hover:border-[#53a1b3]/5">
                                <!-- (Position Removed) -->

                                <!-- Cover -->
                                <div class="w-10 h-10 bg-[#141e24] flex-shrink-0 overflow-hidden rounded-[3px] relative group-hover:shadow-lg transition">
                                    @if($song->cover_path)
                                        <img src="{{ Storage::url($song->cover_path) }}" alt="{{ $song->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <ion-icon name="musical-notes-outline" class="w-5 h-5 text-[#53a1b3]/20"></ion-icon>
                                        </div>
                                    @endif
                                    
                                    <!-- Play Overlay -->
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center cursor-pointer"
                                         onclick="playSong('audio-{{ $song->id }}', '{{ $song->title }}', '{{ $song->artist }}', '{{ $song->cover_path ? Storage::url($song->cover_path) : '' }}', this)">
                                        <ion-icon name="play" class="w-5 h-5 text-white"></ion-icon>
                                    </div>
                                </div>

                                <!-- Info -->
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('songs.show', $song->slug) }}" class="text-white hover:text-[#e96c4c] text-sm font-normal truncate block transition">
                                        {{ $song->title }}
                                    </a>
                                    <p class="text-[#53a1b3]/60 text-[10px] uppercase truncate">{{ $song->artist }}</p>
                                </div>

                                <!-- Audio Element for Inline Play -->
                                <audio id="audio-{{ $song->id }}" src="{{ Storage::url($song->file_path) }}"></audio>

                                <!-- Actions -->
                                <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition">
                                    <a href="{{ route('songs.show', $song->slug) }}" class="w-8 h-8 flex items-center justify-center text-[#53a1b3] hover:text-white transition">
                                        <ion-icon name="eye-outline" class="w-4 h-4"></ion-icon>
                                    </a>

                                    @auth
                                    @if(Auth::id() === $playlist->user_id || $playlist->collaborators->contains(Auth::id()))
                                        <form action="{{ route('playlists.removeSong', [$playlist->slug, $song->id]) }}" method="POST" onsubmit="return confirm('Remove this song?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center text-[#53a1b3] hover:text-red-500 transition">
                                                <ion-icon name="close-outline" class="w-4 h-4"></ion-icon>
                                            </button>
                                        </form>
                                    @endif
                                    @endauth
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>


                <!-- COMMENTS TAB -->
                <div id="content-comments" class="tab-content hidden transition-all duration-300">
                    <!-- Review Input -->
                    @auth
                    <form action="{{ route('interactions.comment', ['type' => 'playlist', 'id' => $playlist->id]) }}" method="POST" class="mt-2">
                        @csrf
                        <div class="flex gap-2">
                            <textarea name="body" id="comment-body" rows="1" maxlength="250" oninput="updateCharCount()"
                                class="flex-1 bg-[#1a2730]/40 border border-[#53a1b3]/10 text-white/80 text-[12px] p-2 focus:outline-none focus:border-[#e96c4c]/30 transition-all placeholder-[#53a1b3]/10 resize-none h-[35px] font-light leading-tight rounded-[3px]"
                                placeholder="Write a comment..."></textarea>

                            <button type="submit" class="px-4 py-2 flex items-center justify-center bg-[#e96c4c] rounded-[3px] border border-[#e96c4c] text-white hover:bg-[#e96c4c]/90 transition shrink-0">
                                <ion-icon name="arrow-up" class="w-4 h-4"></ion-icon>
                            </button>
                        </div>
                        <div class="mt-1 text-right">
                            <span class="text-[9px] text-[#53a1b3]/30"><span id="char-count">250</span></span>
                        </div>
                    </form>
                    @else
                    <div class="mt-4">
                        <p class="text-[#53a1b3]/30 text-[10px] uppercase tracking-[0.2em]">Please <a href="{{ route('login') }}" class="text-[#e96c4c] hover:underline">login</a> to leave a comment</p>
                    </div>
                    @endauth

                    <!-- Comments Loop -->
                    <div class="space-y-2 mt-4">
                        @forelse($playlist->comments()->latest()->get() as $comment)
                        <div class="overflow-hidden">
                            <div class="flex gap-2">
                                <!-- Avatar Square -->
                                <div class="w-10 h-10 bg-[#53a1b3]/10 flex items-center justify-center shrink-0 rounded-[3px]">
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

                <!-- COLLABORATORS TAB -->
                @if(Auth::id() === $playlist->user_id)
                <div id="content-collaborators" class="tab-content hidden transition-all duration-300 mt-2">
                    
                    <!-- Invite Section -->
                    <div class="mb-6">
                        <label class="text-[10px] text-[#53a1b3]/50 uppercase tracking-widest mb-2 block">Invite Collaborator</label>
                        <form action="{{ route('playlists.invite', $playlist->slug) }}" method="POST" class="relative">
                            @csrf
                            <div class="flex gap-2">
                                <input type="email" name="email" placeholder="Enter user's email to invite..." required
                                    class="flex-1 bg-[#1a2730]/40 border border-[#53a1b3]/10 text-white/80 text-[12px] p-2 focus:outline-none focus:border-[#e96c4c]/30 transition-all placeholder-[#53a1b3]/10 h-[35px] font-light leading-tight rounded-[3px]">
                                <button type="submit" class="px-4 py-2 flex items-center justify-center bg-[#e96c4c] rounded-[3px] border border-[#e96c4c] text-white hover:bg-[#e96c4c]/90 transition shrink-0">
                                    <ion-icon name="arrow-up" class="w-4 h-4"></ion-icon>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Collaborators List -->
                    <div>
                         <label class="text-[10px] text-[#53a1b3]/50 uppercase tracking-widest mb-2 block">Current Collaborators</label>
                        <div class="space-y-2">
                            @forelse($playlist->collaborators as $collaborator)
                            <div class="group flex items-center justify-between py-1">
                                <div class="flex items-center gap-3">
                                    <!-- Avatar -->
                                    <div class="w-8 h-8 bg-[#53a1b3]/10 flex items-center justify-center shrink-0 rounded-[3px] text-[#53a1b3]/40 text-xs font-normal uppercase">
                                        {{ substr($collaborator->name, 0, 1) }}
                                    </div>
                                    
                                    <div class="flex flex-col">
                                        <span class="text-white text-[12px] font-normal tracking-wide group-hover:text-[#e96c4c] transition">{{ $collaborator->name }}</span>
                                        <span class="text-[#53a1b3]/40 text-[10px] font-light">{{ $collaborator->email }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center opacity-0 group-hover:opacity-100 transition">
                                    <form action="{{ route('playlists.removeCollaborator', ['playlist' => $playlist->slug, 'user' => $collaborator->id]) }}" method="POST" onsubmit="return confirm('Remove this collaborator?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[#53a1b3]/20 hover:text-red-500 transition" title="Remove Collaborator">
                                            <ion-icon name="close-circle-outline" class="w-4 h-4"></ion-icon>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <div class="py-12 text-center border border-[#53a1b3]/5 rounded-[3px] bg-[#1a2730]/20 border-dashed">
                                <div class="w-12 h-12 bg-[#53a1b3]/5 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <ion-icon name="people-outline" class="w-6 h-6 text-[#53a1b3]/20"></ion-icon>
                                </div>
                                <p class="text-[#53a1b3]/40 text-xs uppercase tracking-wider">No collaborators yet</p>
                                <p class="text-[#53a1b3]/20 text-[10px] mt-1">Invite friends to add songs to this playlist</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endif

            </div>

            <!-- RIGHT: Stats, Description (35%) -->
            <div class="w-full lg:w-[360px] shrink-0 sticky top-0 self-start">

                <!-- Main Metadata Block -->
                <div class="border border-[#53a1b3]/5 p-2 rounded-[3px]">
                     <div class="flex items-center justify-between gap-2 pb-2">
                        <div class="flex items-center gap-3 text-[10px] text-[#53a1b3]/30 uppercase">
                            <ion-icon name="calendar-outline" class="w-4 h-4"></ion-icon>
                            <span>{{ $playlist->created_at->format('m/d/Y') }}</span>
                        </div>

                        <div class="flex gap-2">
                            <div class="text-right">
                                 <span class="block text-sm text-white leading-none font-normal tracking-tight">{{ $playlist->songs->count() }}</span>
                                 <span class="block text-[8px] text-[#53a1b3]/20 uppercase font-light">SONGS</span>
                            </div>
                            <!-- Likes count if available (assuming likes relationship exists) -->
                            @if(method_exists($playlist, 'likes'))
                            <div class="text-right">
                                 <span class="block text-sm text-white leading-none font-normal tracking-tight">{{ $playlist->likes->count() }}</span>
                                 <span class="block text-[8px] text-[#53a1b3]/20 uppercase font-light">LIKES</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Description Segment -->
                    <div>
                         <h4 class="text-[10px] text-[#53a1b3]/20 uppercase font-normal">DESCRIPTION</h4>
                         <p class="text-[#c5d2d9]/70 text-[13px] leading-relaxed font-light">
                             "{{ $playlist->description ?? 'No description provided.' }}"
                         </p>
                    </div>

                    <!-- Owner Segment -->
                    <div class="mt-4">
                         <h4 class="text-[10px] text-[#53a1b3]/20 uppercase font-normal">CREATED BY</h4>
                         <div class="flex items-center gap-2 mt-1">
                             <div class="w-6 h-6 bg-[#53a1b3]/10 rounded-full flex items-center justify-center text-[10px] text-[#53a1b3]">
                                 {{ substr($playlist->user->name, 0, 1) }}
                             </div>
                             <span class="text-xs text-white/80 font-normal uppercase">{{ $playlist->user->name }}</span>
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
            'tracks': { btn: 'btn-tab-tracks', content: 'content-tracks' },
            'comments': { btn: 'btn-tab-comments', content: 'content-comments' },
            'collaborators': { btn: 'btn-tab-collaborators', content: 'content-collaborators' }
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

    // Play Sharing
    function sharePlaylist() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $playlist->name }}',
                text: 'Check out this playlist: {{ $playlist->name }}',
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

    // Global Player Integration for Inline Playlist Items
    let currentAudio = null;
    let currentButton = null;

    function playSong(audioId, title, artist, cover, btnElement) {
        const audio = document.getElementById(audioId);
        if (!audio) return;

        if (currentAudio && currentAudio !== audio) {
            currentAudio.pause();
            currentAudio.currentTime = 0;
            // Reset previous button icon
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

            // Update Global Player
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
        // Find the first audio element in the list
        const firstAudio = document.querySelector('audio[id^="audio-"]');
        if (firstAudio) {
            const btn = firstAudio.parentElement.querySelector('[onclick^="playSong"]');
            if (btn) btn.click();
        }
    }
</script>
@endsection
