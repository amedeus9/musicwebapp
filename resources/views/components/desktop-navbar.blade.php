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
                class="block w-full pl-10 pr-3 py-2 border border-[#53a1b3]/10 rounded-[3px] leading-5 bg-[#0f1319] text-[#53a1b3] placeholder-[#53a1b3]/40 focus:outline-none focus:bg-[#141e24] focus:border-[#e96c4c]/50 focus:ring-1 focus:ring-[#e96c4c]/50 text-xs transition placeholder:text-xs"
                placeholder="Search for songs, artists, albums..."
            >
        </form>
    </div>

    <!-- Right Actions -->
    <div class="flex items-center gap-4">

        <!-- Upload Button (Mini) -->
        @auth
        <a href="{{ route('songs.create') }}" class="hidden lg:flex items-center gap-2 px-4 py-2 bg-[#e96c4c]/10 text-[#e96c4c] rounded-[3px] text-xs font-normal uppercase tracking-wider hover:bg-[#e96c4c] hover:text-white transition">
            <ion-icon name="cloud-upload-outline" class="w-4 h-4"></ion-icon>
            <span>Upload</span>
        </a>
        @endauth

        <div class="h-6 w-px bg-[#53a1b3]/20 mx-2"></div>

        <!-- User Menu -->
        <div class="flex items-center gap-3">
            @auth
            <div class="text-right hidden lg:block">
                <p class="text-white text-xs font-normal leading-none">{{ auth()->user()->name }}</p>
                <p class="text-[#e96c4c] text-[10px] uppercase tracking-wider">Premium Member</p>
            </div>
            <div class="relative group">
                <a href="{{ route('profile') }}" class="w-10 h-10 rounded-[3px] bg-gradient-to-br from-[#141e24] to-[#0f1319] border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3] hover:text-[#e96c4c] transition">
                    <ion-icon name="person-outline" class="w-5 h-5"></ion-icon>
                </a>
                
                <!-- Dropdown -->
                <div class="absolute right-0 top-full mt-2 w-48 bg-[#1a2730] border border-[#53a1b3]/10 rounded-[3px] shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <a href="{{ route('profile') }}" class="flex items-center gap-2 px-4 py-3 text-xs text-[#53a1b3] hover:text-white hover:bg-[#141e24] transition border-b border-[#53a1b3]/5 first:rounded-t-[3px]">
                        <ion-icon name="person-outline" class="w-4 h-4"></ion-icon>
                        <span>My Profile</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-3 text-xs text-[#e96c4c] hover:bg-[#141e24] transition last:rounded-b-[3px] text-left">
                            <ion-icon name="log-out-outline" class="w-4 h-4"></ion-icon>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
            @else
            <button onclick="openLoginModal()" class="px-6 py-2 bg-[#e96c4c] text-white rounded-[3px] text-xs font-medium uppercase tracking-wider hover:bg-[#d45a3a] transition shadow-lg shadow-[#e96c4c]/20">
                Login
            </button>
            @endauth
        </div>
    </div>
</header>
