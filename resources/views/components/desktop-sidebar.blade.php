<aside class="hidden md:flex flex-col w-[250px] border-r border-[#53a1b3]/10 bg-[#0f1319] p-2 gap-6 sticky top-0 h-screen overflow-y-auto">

    <!-- Logo -->
    <div class="flex items-center gap-3 px-2">
        <div class="w-10 h-10 rounded-[3px] bg-gradient-to-br from-[#e96c4c] to-[#d45a3a] flex items-center justify-center text-white shadow-lg shadow-[#e96c4c]/20">
            <ion-icon name="musical-notes-outline" class="w-4 h-4"></ion-icon>
        </div>
        <div>
            <h1 class="font-normal text-xl text-white tracking-tighter leading-none">MusicApp</h1>
            <p class="text-[10px] text-[#53a1b3] font-normal uppercase tracking-widest">Premium</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex flex-col gap-2">
        <p class="text-[10px] font-normal text-[#53a1b3]/50 uppercase tracking-widest px-2 mb-1">Menu</p>

        <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2 group transition rounded-[3px] {{ request()->routeIs('home') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
            <ion-icon name="musical-notes-outline" class="w-4 h-4"></ion-icon>
            <span class="text-xs font-normal uppercase tracking-wider">Home</span>
        </a>

        <a href="{{ route('songs.index') }}" class="flex items-center gap-3 px-4 py-2 group transition rounded-[3px] {{ (request()->routeIs('songs.index') || request()->routeIs('songs.show')) ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
            <ion-icon name="globe-outline" class="w-4 h-4"></ion-icon>
            <span class="text-xs font-normal uppercase tracking-wider">Browse</span>
        </a>

        <a href="{{ route('artists.index') }}" class="flex items-center gap-3 px-4 py-2 group transition rounded-[3px] {{ request()->routeIs('artists.*') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
            <ion-icon name="mic-outline" class="w-4 h-4"></ion-icon>
            <span class="text-xs font-normal uppercase tracking-wider">Artists</span>
        </a>

        <a href="{{ route('albums.index') }}" class="flex items-center gap-3 px-4 py-2 group transition rounded-[3px] {{ request()->routeIs('albums.*') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
            <ion-icon name="albums-outline" class="w-4 h-4"></ion-icon>
            <span class="text-xs font-normal uppercase tracking-wider">Albums</span>
        </a>

        @auth
        @if(auth()->user()->isAdmin())
        <a href="{{ route('songs.create') }}" class="flex items-center gap-3 px-4 py-2 group transition rounded-[3px] {{ request()->routeIs('songs.create') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
            <ion-icon name="cloud-upload-outline" class="w-4 h-4"></ion-icon>
            <span class="text-xs font-normal uppercase tracking-wider">Upload</span>
        </a>
        @endif
        @endauth

        <a href="{{ route('favorites') }}" class="flex items-center gap-3 px-4 py-2 group transition rounded-[3px] {{ request()->routeIs('favorites') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
            <ion-icon name="heart-outline" class="w-4 h-4"></ion-icon>
            <span class="text-xs font-normal uppercase tracking-wider">Favorites</span>
        </a>

        <a href="{{ route('playlists.index') }}" class="flex items-center gap-3 px-4 py-2 group transition rounded-[3px] {{ request()->routeIs('playlists.*') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
            <ion-icon name="list-outline" class="w-4 h-4"></ion-icon>
            <span class="text-xs font-normal uppercase tracking-wider">Playlists</span>
        </a>

        @auth
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 group transition rounded-[3px] {{ request()->routeIs('admin.*') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
            <ion-icon name="shield-checkmark-outline" class="w-4 h-4"></ion-icon>
            <span class="text-xs font-normal uppercase tracking-wider">Admin Panel</span>
        </a>
        @endif
        @endauth
    </nav>

    <!-- Genres -->
    <div class="mt-auto">
        <p class="text-[10px] font-normal text-[#53a1b3]/50 uppercase tracking-widest px-2 mb-2">Genres</p>
        <div class="flex flex-wrap gap-2 px-2">
            @php
                $sidebarGenres = ['Bongo Flava', 'Hip Hop', 'Gospel', 'Amapiano', 'Afrobeat', 'Singeli'];
            @endphp
            @foreach($sidebarGenres as $genre)
            <a href="{{ route('songs.index', ['search' => $genre]) }}" class="px-3 py-2 bg-[#141e24] border border-[#53a1b3]/20 text-[10px] font-normal text-[#53a1b3] hover:border-[#e96c4c] hover:text-[#e96c4c] transition uppercase rounded-[3px]">
                {{ $genre }}
            </a>
            @endforeach
        </div>
    </div>
</aside>
