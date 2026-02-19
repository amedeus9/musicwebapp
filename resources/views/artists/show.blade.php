@extends('layouts.app')

@section('content')
<div class="flex flex-col gap-8 pb-12">
    
    <!-- Artist Hero Header -->
    <div class="relative w-full aspect-[21/9] md:aspect-[21/7] bg-[#1a2730] rounded-[3px] overflow-hidden border border-white/5">
        @if($artist->image_path)
            <img src="{{ Storage::url($artist->image_path) }}" 
                 alt="{{ $artist->name }}" 
                 class="w-full h-full object-cover opacity-60">
        @else
            <div class="w-full h-full flex items-center justify-center text-[#53a1b3]/10">
                <ion-icon name="mic-outline" class="w-32 h-32"></ion-icon>
            </div>
        @endif
        
        <!-- Gradient Overlays -->
        <div class="absolute inset-x-0 bottom-0 h-2/3 bg-gradient-to-t from-[#141e24] via-[#141e24]/80 to-transparent"></div>
        <div class="absolute inset-0 bg-[#0f1319]/20"></div>

        <!-- Content -->
        <div class="absolute inset-x-0 bottom-0 p-6 md:p-10 flex flex-col md:flex-row md:items-end gap-6">
            <div class="w-24 h-24 md:w-32 md:h-32 rounded-[3px] overflow-hidden border-2 border-white/10 shadow-2xl shrink-0">
                @if($artist->image_path)
                    <img src="{{ Storage::url($artist->image_path) }}" alt="{{ $artist->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-[#1a2730] flex items-center justify-center text-[#53a1b3]/30">
                        <ion-icon name="person" class="w-12 h-12"></ion-icon>
                    </div>
                @endif
            </div>
            
            <div class="flex flex-col gap-2 min-w-0">
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 bg-[#e96c4c] text-white text-[9px] font-normal uppercase tracking-widest rounded-[2px] shadow-lg shadow-[#e96c4c]/20">Verified Artist</span>
                </div>
                <h1 class="text-3xl md:text-5xl lg:text-6xl font-normal text-white uppercase tracking-tight truncate">
                    {{ $artist->name }}
                </h1>
                    <span>{{ $artist->songs_count ?? $artist->songs()->count() }} Tracks</span>
                    <span class="w-1 h-1 bg-[#53a1b3]/20 rounded-full"></span>
                    <span id="followers-count">{{ $artist->followers()->count() }} Followers</span>
                </div>
                
                <div class="flex items-center gap-3 mt-4">
                    <button class="px-6 py-2 bg-[#e96c4c] text-white rounded-[3px] text-xs uppercase tracking-widest hover:bg-[#d45a3a] transition shadow-lg shadow-[#e96c4c]/20 flex items-center gap-2 group">
                        <ion-icon name="play" class="w-4 h-4"></ion-icon>
                        Play
                    </button>
                    
                    @auth
                        <button 
                            id="follow-btn"
                            data-artist-slug="{{ $artist->slug }}"
                            data-is-following="{{ auth()->user()->isFollowing($artist) ? 'true' : 'false' }}"
                            class="px-6 py-2 border border-[#53a1b3]/30 text-[#53a1b3] rounded-[3px] text-xs uppercase tracking-widest transition flex items-center gap-2 hover:border-white hover:text-white group-follow">
                            <ion-icon name="{{ auth()->user()->isFollowing($artist) ? 'checkmark' : 'person-add-outline' }}" class="w-4 h-4"></ion-icon>
                            <span>{{ auth()->user()->isFollowing($artist) ? 'Following' : 'Follow' }}</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2 border border-[#53a1b3]/30 text-[#53a1b3] rounded-[3px] text-xs uppercase tracking-widest hover:border-[#e96c4c] hover:text-[#e96c4c] transition flex items-center gap-2">
                           <ion-icon name="person-add-outline" class="w-4 h-4"></ion-icon>
                           Follow
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Artist Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 px-2 lg:px-0">
        
        <!-- Main Column (Songs) -->
        <div class="lg:col-span-2 flex flex-col gap-8">
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between border-b border-[#53a1b3]/5 pb-3">
                    <h3 class="text-white text-sm font-normal uppercase tracking-[0.2em]">Popular Tracks</h3>
                    <button class="text-[#53a1b3]/40 text-[10px] uppercase tracking-widest hover:text-white transition">Show All</button>
                </div>
                
                <div class="flex flex-col gap-0.5">
                    @foreach($artist->songs as $index => $song)
                        <x-song-item :song="$song" :index="$index" />
                    @endforeach
                </div>
            </div>

            <!-- Albums Section -->
            @if($artist->albums->count() > 0)
            <div class="flex flex-col gap-6 pt-4">
                <div class="flex items-center justify-between border-b border-[#53a1b3]/5 pb-3">
                    <h3 class="text-white text-sm font-normal uppercase tracking-[0.2em]">Albums</h3>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($artist->albums as $album)
                        <a href="{{ route('albums.show', $album) }}" class="group flex flex-col gap-3">
                            <div class="relative aspect-square bg-[#1a2730] rounded-[3px] overflow-hidden border border-white/5 group-hover:border-[#e96c4c]/50 transition">
                                @if($album->cover_image)
                                    <img src="{{ Storage::url($album->cover_image) }}" alt="{{ $album->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-[#53a1b3]/20">
                                        <ion-icon name="disc" class="w-12 h-12"></ion-icon>
                                    </div>
                                @endif
                                
                                <!-- Play Overlay -->
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center backdrop-blur-[2px]">
                                    <div class="w-10 h-10 rounded-full bg-[#e96c4c] text-white flex items-center justify-center shadow-lg transform translate-y-4 group-hover:translate-y-0 transition duration-300">
                                        <ion-icon name="play" class="w-5 h-5 ml-0.5"></ion-icon>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-white text-sm font-base truncate group-hover:text-[#e96c4c] transition">{{ $album->title }}</h4>
                                <p class="text-[#53a1b3]/50 text-[10px] uppercase tracking-wider mt-0.5">{{ $album->release_date ? $album->release_date->format('Y') : 'Unknown' }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        <!-- Sidebar (Similar Artists / Info) -->
        <div class="hidden lg:flex flex-col gap-8">
            <div class="bg-[#141e24] border border-[#53a1b3]/10 rounded-[3px] p-6 sticky top-24">
                <h3 class="text-white text-xs font-normal uppercase tracking-widest mb-4 pb-2 border-b border-[#53a1b3]/10">About Artist</h3>
                <p class="text-[#53a1b3]/70 text-sm leading-relaxed font-light">
                    {{ $artist->bio ?? 'No biography available for this artist.' }}
                </p>
                
                <div class="mt-6 pt-4 border-t border-[#53a1b3]/5 flex flex-col gap-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-[#53a1b3]/40 uppercase tracking-widest">Country</span>
                        <span class="text-white">{{ $artist->country->name ?? 'Unknown' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-[#53a1b3]/40 uppercase tracking-widest">Joined</span>
                        <span class="text-white">{{ $artist->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const followBtn = document.getElementById('follow-btn');
        const followersCount = document.getElementById('followers-count');
        
        if (followBtn) {
            followBtn.addEventListener('click', async () => {
                const slug = followBtn.dataset.artistSlug;
                const isFollowing = followBtn.dataset.isFollowing === 'true';
                const url = isFollowing ? `/artists/${slug}/unfollow` : `/artists/${slug}/follow`;
                
                // Disable button
                followBtn.disabled = true;
                followBtn.style.opacity = '0.7';
                
                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        if (data.success) {
                            // Update State
                            followBtn.dataset.isFollowing = data.is_following;
                            
                            // Update UI
                            const icon = followBtn.querySelector('ion-icon');
                            const text = followBtn.querySelector('span');
                            
                            if (data.is_following) {
                                icon.setAttribute('name', 'checkmark');
                                text.textContent = 'Following';
                                // Optional styling updates could go here
                            } else {
                                icon.setAttribute('name', 'person-add-outline');
                                text.textContent = 'Follow';
                            }
                            
                            // Update Count
                            followersCount.textContent = data.followers_count + ' Followers';
                        }
                    } else {
                        console.error('Follow action failed');
                    }
                } catch (error) {
                    console.error('Error:', error);
                } finally {
                    followBtn.disabled = false;
                    followBtn.style.opacity = '1';
                }
            });
        }
    });
</script>
            </div>
        </div>
    </div>

    <!-- Artist Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 px-2 lg:px-0">
        
        <!-- Main Column (Songs) -->
        <div class="lg:col-span-2 flex flex-col gap-8">
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between border-b border-[#53a1b3]/5 pb-3">
                    <h3 class="text-white text-sm font-normal uppercase tracking-[0.2em]">Popular Tracks</h3>
                    <button class="text-[#53a1b3]/40 text-[10px] uppercase tracking-widest hover:text-white transition">Show All</button>
                </div>
                
                <div class="flex flex-col gap-0.5">
                    @foreach($artist->songs as $index => $song)
                        <x-song-item :song="$song" :index="$index" />
                    @endforeach
                </div>
            </div>

            <!-- Albums Section -->
            @if($artist->albums->count() > 0)
            <div class="flex flex-col gap-6 pt-4">
                <div class="flex items-center justify-between border-b border-[#53a1b3]/5 pb-3">
                    <h3 class="text-white text-sm font-normal uppercase tracking-[0.2em]">Discography</h3>
                </div>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($artist->albums as $album)
                    <a href="{{ route('albums.show', $album) }}" class="group flex flex-col gap-3">
                        <div class="relative aspect-square bg-[#1a2730]/40 rounded-[3px] overflow-hidden border border-white/5 group-hover:border-[#e96c4c]/30 transition duration-500">
                            @if($album->cover_path)
                                <img src="{{ Storage::url($album->cover_path) }}" alt="{{ $album->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[#53a1b3]/10 transition duration-700">
                                    <ion-icon name="disc-outline" class="w-12 h-12"></ion-icon>
                                </div>
                            @endif
                            <!-- Play Overlay (Visual) -->
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-500 flex items-center justify-center">
                                <div class="w-10 h-10 bg-[#e96c4c] rounded-[3px] flex items-center justify-center text-white transform translate-y-4 group-hover:translate-y-0 transition duration-500">
                                    <ion-icon name="play" class="w-4 h-4"></ion-icon>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <h4 class="text-white text-[11px] font-normal uppercase tracking-wider truncate group-hover:text-[#e96c4c] transition">
                                {{ $album->title }}
                            </h4>
                            <span class="text-[#53a1b3]/40 text-[9px] uppercase tracking-[0.2em]">
                                {{ \Carbon\Carbon::parse($album->release_date)->format('Y') }} • Album
                            </span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar (Artist Bio & Details) -->
        <div class="flex flex-col gap-8">
            <div class="p-5 bg-[#1a2730]/40 rounded-[3px] border border-white/5 flex flex-col gap-6">
                <div class="flex flex-col gap-3">
                    <h4 class="text-white text-[11px] font-normal uppercase tracking-[0.2em] border-l-2 border-[#e96c4c] pl-3">Artist Bio</h4>
                    <p class="text-[#53a1b3]/60 text-[11px] leading-relaxed uppercase tracking-widest text-justify">
                        {{ $artist->bio ?: 'No biography available for this artist yet. Stay tuned for updates on their musical journey.' }}
                    </p>
                </div>

                <div class="flex flex-col gap-4 pt-4 border-t border-[#53a1b3]/5">
                    <div class="flex justify-between items-center text-[10px] uppercase tracking-widest">
                        <span class="text-[#53a1b3]/40">Hometown</span>
                        <span class="text-white">{{ $artist->hometown ?? 'Unknown' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[10px] uppercase tracking-widest">
                        <span class="text-[#53a1b3]/40">Active Since</span>
                        <span class="text-white">{{ $artist->formed_at ? \Carbon\Carbon::parse($artist->formed_at)->format('Y') : 'Unknown' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[10px] uppercase tracking-widest">
                        <span class="text-[#53a1b3]/40">Genre</span>
                        <span class="text-[#e96c4c]">Various</span>
                    </div>
                </div>

                <button class="w-full py-2.5 bg-[#e96c4c] text-white text-[10px] font-normal uppercase tracking-[0.2em] rounded-[3px] hover:bg-[#e96c4c]/90 transition shadow-lg shadow-[#e96c4c]/10">
                    Follow Artist
                </button>
            </div>

            <!-- Social Links (Placeholder) -->
            <div class="flex flex-col gap-4 px-2">
                <h4 class="text-white text-[10px] font-normal uppercase tracking-[0.2em]">On the Web</h4>
                <div class="flex gap-3">
                    <a href="#" class="w-8 h-8 rounded-[3px] bg-[#1a2730]/40 border border-white/5 flex items-center justify-center text-[#53a1b3]/60 hover:text-white hover:border-[#e96c4c]/30 transition">
                        <ion-icon name="logo-instagram" class="w-4 h-4"></ion-icon>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-[3px] bg-[#1a2730]/40 border border-white/5 flex items-center justify-center text-[#53a1b3]/60 hover:text-white hover:border-[#e96c4c]/30 transition">
                        <ion-icon name="logo-youtube" class="w-4 h-4"></ion-icon>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-[3px] bg-[#1a2730]/40 border border-white/5 flex items-center justify-center text-[#53a1b3]/60 hover:text-white hover:border-[#e96c4c]/30 transition">
                        <ion-icon name="logo-twitter" class="w-4 h-4"></ion-icon>
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
