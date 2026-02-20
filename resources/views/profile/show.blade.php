@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col overflow-y-auto pb-24 gap-1">
    
    <!-- Hero / Profile Header -->
    <div class="relative w-full h-[300px] flex items-end p-8 bg-gradient-to-b from-[#1a2730] to-[#0f1319]">
        <div class="relative z-10 flex items-end gap-6 w-full">
            <!-- User Avatar -->
            <div class="w-48 h-48 rounded-[3px] overflow-hidden shadow-2xl bg-[#0f1319] group shrink-0">
                <div class="w-full h-full flex items-center justify-center bg-[#e96c4c]/20 text-[#e96c4c]">
                    <ion-icon name="person" class="w-20 h-20"></ion-icon>
                </div>
            </div>
            
            <!-- User Info -->
            <div class="flex-1 min-w-0 mb-2">
                <span class="text-xs font-medium text-white/60 uppercase tracking-widest mb-1 block">Profile</span>
                <h1 class="text-7xl font-bold text-white tracking-tight leading-none mb-6 truncate">{{ $user->name }}</h1>
                
                <div class="flex items-center gap-6 text-sm text-white/60">
                    <span>{{ $playlists->count() }} Playlists</span>
                    <span>•</span>
                    <span>{{ $followedArtists->count() }} Following</span>
                    <span>•</span>
                    <a href="{{ route('profile.edit') }}" class="text-[#e96c4c] hover:text-white transition">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Sections -->
    <div class="px-8 py-6 space-y-12">
        
        <!-- My Playlists -->
        @if($playlists->count() > 0)
        <section>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white tracking-tight">My Playlists</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($playlists as $playlist)
                    <a href="{{ route('playlists.show', $playlist->slug) }}" class="group bg-[#181818] p-4 rounded-md hover:bg-[#282828] transition duration-300">
                        <div class="relative aspect-square mb-4 bg-[#333] shadow-[0_8px_24px_rgba(0,0,0,0.5)] rounded-md overflow-hidden flex items-center justify-center">
                            @if($playlist->cover_image)
                                <img src="{{ asset('storage/' . $playlist->cover_image) }}" alt="{{ $playlist->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-[#282828]">
                                    <ion-icon name="musical-notes" class="text-4xl text-white/20"></ion-icon>
                                </div>
                            @endif
                        </div>
                        <h3 class="text-white font-bold truncate mb-1">{{ $playlist->name }}</h3>
                        <p class="text-sm text-[#b3b3b3] line-clamp-2">By {{ $user->name }}</p>
                    </a>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Followed Playlists -->
        @if($followedPlaylists->count() > 0)
        <section>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white tracking-tight">Followed Playlists</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($followedPlaylists as $playlist)
                    <a href="{{ route('playlists.show', $playlist->slug) }}" class="group bg-[#181818] p-4 rounded-md hover:bg-[#282828] transition duration-300">
                        <div class="relative aspect-square mb-4 bg-[#333] shadow-[0_8px_24px_rgba(0,0,0,0.5)] rounded-md overflow-hidden flex items-center justify-center">
                            @if($playlist->cover_image)
                                <img src="{{ asset('storage/' . $playlist->cover_image) }}" alt="{{ $playlist->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-[#282828]">
                                    <ion-icon name="musical-notes" class="text-4xl text-white/20"></ion-icon>
                                </div>
                            @endif
                        </div>
                        <h3 class="text-white font-bold truncate mb-1">{{ $playlist->name }}</h3>
                        <p class="text-sm text-[#b3b3b3] line-clamp-2">By {{ $playlist->user ? $playlist->user->name : 'Unknown' }}</p>
                    </a>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Followed Artists -->
        @if($followedArtists->count() > 0)
        <section>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white tracking-tight">Following Artists</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($followedArtists as $artist)
                    <a href="{{ route('artists.show', ['country' => optional($artist->country)->slug ?? 'global', 'artist' => $artist->slug]) }}" class="group bg-[#181818] p-4 rounded-md hover:bg-[#282828] transition duration-300">
                        <div class="relative aspect-square mb-4 bg-[#333] shadow-[0_8px_24px_rgba(0,0,0,0.5)] rounded-full overflow-hidden flex items-center justify-center">
                            @if($artist->image)
                                <img src="{{ asset('storage/' . $artist->image) }}" alt="{{ $artist->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-[#282828]">
                                    <ion-icon name="person" class="text-4xl text-white/20"></ion-icon>
                                </div>
                            @endif
                        </div>
                        <h3 class="text-white font-bold truncate mb-1 text-center">{{ $artist->name }}</h3>
                        <p class="text-sm text-[#b3b3b3] text-center">Artist</p>
                    </a>
                @endforeach
            </div>
        </section>
        @endif

    </div>
</div>
@endsection
