<header class="h-[50px] px-2 flex items-center justify-between bg-[#141e24]/95 backdrop-blur-md fixed top-0 left-0 right-0 z-50 border-b border-[#53a1b3]/20 gap-2">
    @if(request()->routeIs('songs.index'))
        <!-- Browse Mode: Icon | Search Input | Filter -->
        <a href="{{ route('songs.index') }}" class="w-10 h-10 flex items-center justify-center text-[#53a1b3] hover:text-[#e96c4c] transition rounded-[3px]">
            <ion-icon name="search-outline" class="w-4 h-4"></ion-icon>
        </a>

        <form action="{{ route('songs.index') }}" method="GET" class="flex-1 relative">
            <input type="text" name="search" value="{{ request('search') }}"
                class="w-full h-10 bg-[#0f1319] border border-[#53a1b3]/20 text-[#53a1b3] text-xs px-4 rounded-[3px] focus:outline-none focus:border-[#e96c4c] transition placeholder-[#53a1b3]/50"
                placeholder="Search music...">
            <button type="submit" class="absolute right-0 top-0 h-10 w-10 flex items-center justify-center text-[#53a1b3] hover:text-[#e96c4c]">
                <ion-icon name="search-outline" class="w-4 h-4"></ion-icon>
            </button>
        </form>

        <button onclick="toggleFilters()" class="w-10 h-10 bg-[#0f1319] hover:bg-[#1a2730] border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3] hover:text-[#e96c4c] transition-colors shrink-0 rounded-[3px]">
            <ion-icon name="settings-outline" class="w-4 h-4"></ion-icon>
        </button>

    @else
        <!-- Default Mode: Text Logo | Search Icon | Profile Icon -->
        <a href="{{ route('home') }}" class="font-normal text-xl tracking-tighter no-underline text-[#e96c4c] flex items-center gap-1">
            <ion-icon name="musical-notes-outline" class="w-4 h-4 text-[#53a1b3]"></ion-icon> MusicApp
        </a>

        <div class="flex gap-2 items-center ml-auto">
            <a href="{{ route('songs.index') }}" class="w-10 h-10 bg-[#0f1319] hover:bg-[#1a2730] border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3] transition-colors rounded-[3px]">
                <ion-icon name="search-outline" class="w-4 h-4"></ion-icon>
            </a>

            @auth
            <a href="{{ route('profile') }}" class="w-10 h-10 bg-[#0f1319] hover:bg-[#1a2730] border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3] transition-colors rounded-[3px]">
                <ion-icon name="person-outline" class="w-4 h-4"></ion-icon>
            </a>
            @else
            <button onclick="openLoginModal()" class="w-10 h-10 bg-[#0f1319] hover:bg-[#1a2730] border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3] transition-colors rounded-[3px]">
                <ion-icon name="log-in-outline" class="w-4 h-4"></ion-icon>
            </button>
            @endauth
        </div>
    @endif
</header>
