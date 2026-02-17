<aside class="hidden md:flex flex-col w-[250px] border-r border-[#53a1b3]/10 bg-[#0f1319] p-2 gap-6 sticky top-0 h-screen overflow-y-auto">

    <!-- Logo -->
    <div class="flex items-center gap-3 px-2">
        <div class="w-10 h-10 rounded-none bg-gradient-to-br from-[#e96c4c] to-[#d45a3a] flex items-center justify-center text-white shadow-lg shadow-[#e96c4c]/20">
            <ion-icon name="musical-notes-outline" class="w-6 h-6"></ion-icon>
        </div>
        <div>
            <h1 class="font-normal text-xl text-white tracking-tighter leading-none">MusicApp</h1>
            <p class="text-[10px] text-[#53a1b3] font-normal uppercase tracking-widest">Premium</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex flex-col gap-2">
        <p class="text-[10px] font-normal text-[#53a1b3]/50 uppercase tracking-widest px-2 mb-1">Menu</p>

        <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 group transition {{ request()->routeIs('home') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
            <ion-icon name="musical-notes-outline" class="w-5 h-5"></ion-icon>
            <span class="text-xs font-normal uppercase tracking-wider">Home</span>
        </a>

        <a href="{{ route('songs.index') }}" class="flex items-center gap-3 px-3 py-2.5 group transition {{ request()->routeIs('songs.index') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
            <ion-icon name="globe-outline" class="w-5 h-5"></ion-icon>
            <span class="text-xs font-normal uppercase tracking-wider">Browse</span>
        </a>

        <a href="{{ route('albums.index') }}" class="flex items-center gap-3 px-3 py-2.5 group transition {{ request()->routeIs('albums.index') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
            <ion-icon name="albums-outline" class="w-5 h-5"></ion-icon>
            <span class="text-xs font-normal uppercase tracking-wider">Albums</span>
        </a>

        <a href="{{ route('songs.create') }}" class="flex items-center gap-3 px-3 py-2.5 group transition {{ request()->routeIs('songs.create') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
            <ion-icon name="cloud-upload-outline" class="w-5 h-5"></ion-icon>
            <span class="text-xs font-normal uppercase tracking-wider">Upload</span>
        </a>

        <a href="{{ route('favorites') }}" class="flex items-center gap-3 px-3 py-2.5 group transition {{ request()->routeIs('favorites') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
            <ion-icon name="heart-outline" class="w-5 h-5"></ion-icon>
            <span class="text-xs font-normal uppercase tracking-wider">Favorites</span>
        </a>

        <a href="{{ route('playlists.index') }}" class="flex items-center gap-3 px-3 py-2.5 group transition {{ request()->routeIs('playlists.*') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
            <ion-icon name="list-outline" class="w-5 h-5"></ion-icon>
            <span class="text-xs font-normal uppercase tracking-wider">Playlists</span>
        </a>

        @auth
        <a href="{{ route('profile') }}" class="flex items-center gap-3 px-3 py-2.5 group transition {{ request()->routeIs('profile') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
            <ion-icon name="person-outline" class="w-5 h-5"></ion-icon>
            <span class="text-xs font-normal uppercase tracking-wider">Profile</span>
        </a>

        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 group transition {{ request()->routeIs('admin.*') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
            <ion-icon name="shield-checkmark-outline" class="w-5 h-5"></ion-icon>
            <span class="text-xs font-normal uppercase tracking-wider">Admin Panel</span>
        </a>
        @endif

        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="flex items-center gap-3 px-3 py-2.5 text-[#53a1b3] hover:bg-red-500/10 hover:text-red-400 transition">
            <ion-icon name="log-out-outline" class="w-5 h-5"></ion-icon>
            <span class="text-xs font-normal uppercase tracking-wider">Sign Out</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
        @else
        <a href="{{ route('login') }}" class="flex items-center gap-3 px-3 py-2.5 group transition {{ request()->routeIs('login') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
            <ion-icon name="log-in-outline" class="w-5 h-5"></ion-icon>
            <span class="text-xs font-normal uppercase tracking-wider">Sign In</span>
        </a>

        <a href="{{ route('register') }}" class="flex items-center gap-3 px-3 py-2.5 group transition {{ request()->routeIs('register') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
            <ion-icon name="person-add-outline" class="w-5 h-5"></ion-icon>
            <span class="text-xs font-normal uppercase tracking-wider">Register</span>
        </a>
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
            <a href="{{ route('songs.index', ['search' => $genre]) }}" class="px-2 py-1 bg-[#141e24] border border-[#53a1b3]/20 text-[10px] font-normal text-[#53a1b3] hover:border-[#e96c4c] hover:text-[#e96c4c] transition uppercase">
                {{ $genre }}
            </a>
            @endforeach
        </div>
    </div>
</aside>
