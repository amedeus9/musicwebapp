<header class="hidden md:flex items-center justify-between px-2 py-1 h-[60px] bg-[#141e24] border-b border-[#53a1b3]/10 fixed top-0 left-0 md:left-[250px] right-0 z-40">

    <!-- Search Bar -->
    <div class="flex-1 max-w-md">
        <form action="{{ route('songs.index') }}" method="GET" class="relative group">
            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                <ion-icon name="search-outline" class="w-4 h-4 text-[#53a1b3]/50 transition"></ion-icon>
            </div>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="block w-full pl-10 pr-3 py-2 border border-[#53a1b3]/10 rounded-none leading-5 bg-[#0f1319] text-[#53a1b3] placeholder-[#53a1b3]/40 focus:outline-none focus:bg-[#141e24] focus:border-[#e96c4c]/50 focus:ring-1 focus:ring-[#e96c4c]/50 sm:text-sm transition"
                placeholder="Search for songs, artists, albums..."
            >
        </form>
    </div>

    <!-- Right Actions -->
    <div class="flex items-center gap-4">

        <!-- Upload Button (Mini) -->
        <a href="{{ route('songs.create') }}" class="hidden lg:flex items-center gap-2 px-4 py-2 bg-[#e96c4c]/10 text-[#e96c4c] rounded-none text-xs font-normal uppercase tracking-wider hover:bg-[#e96c4c] hover:text-white transition">
            <ion-icon name="cloud-upload-outline" class="w-4 h-4"></ion-icon>
            <span>Upload</span>
        </a>

        <div class="h-6 w-px bg-[#53a1b3]/20 mx-2"></div>

        <!-- User Menu -->
        <div class="flex items-center gap-3">
            @auth
            <div class="text-right hidden lg:block">
                <p class="text-white text-xs font-normal leading-none">{{ auth()->user()->name }}</p>
                <p class="text-[#e96c4c] text-[10px] uppercase tracking-wider">Premium Member</p>
            </div>
            @else
            <div class="text-right hidden lg:block">
                <p class="text-white text-xs font-normal leading-none">Guest User</p>
                <p class="text-[#53a1b3] text-[10px] uppercase tracking-wider">Free Plan</p>
            </div>
            @endauth
            <div class="w-10 h-10 rounded-none bg-gradient-to-br from-[#141e24] to-[#0f1319] border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3]">
                <ion-icon name="person-outline" class="w-5 h-5"></ion-icon>
            </div>
        </div>
    </div>
</header>
