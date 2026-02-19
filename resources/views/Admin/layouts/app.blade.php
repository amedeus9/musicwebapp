<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Music App - Admin Panel">
    <meta name="theme-color" content="#141e24">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Panel - {{ config('app.name', 'MusicApp') }}</title>

    @if(env('APP_ENV') === 'production')
        @php
            $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        @endphp
        <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
        <script type="module" src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}"></script>
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Space Grotesk', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        /* Hide scrollbar for clean UI */
        ::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }
    </style>
</head>
<body class="bg-[#0f1319] text-[#53a1b3] font-light min-h-screen flex justify-center selection:bg-[#e96c4c] selection:text-white">

    <!-- Main Layout Container -->
    <div class="w-full md:flex min-h-screen bg-[#141e24] border-x border-[#53a1b3]/10 shadow-2xl relative">

        <!-- Admin Sidebar -->
        <aside class="hidden md:flex flex-col w-[250px] border-r border-[#53a1b3]/10 bg-[#0f1319] p-2 gap-6 sticky top-0 h-screen overflow-y-auto">

            <!-- Logo -->
            <div class="flex items-center gap-3 px-2">
                <div class="w-10 h-10 rounded-none bg-gradient-to-br from-[#e96c4c] to-[#d45a3a] flex items-center justify-center text-white shadow-lg shadow-[#e96c4c]/20">
                    <ion-icon name="shield-checkmark-outline" class="w-6 h-6"></ion-icon>
                </div>
                <div>
                    <h1 class="font-normal text-xl text-white tracking-tighter leading-none">Admin Panel</h1>
                    <p class="text-[10px] text-[#53a1b3] font-normal uppercase tracking-widest">Management</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex flex-col gap-2">
                <p class="text-[10px] font-normal text-[#53a1b3]/50 uppercase tracking-widest px-2 mb-1">Dashboard</p>

                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 group transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
                    <ion-icon name="grid-outline" class="w-5 h-5"></ion-icon>
                    <span class="text-xs font-normal uppercase tracking-wider">Dashboard</span>
                </a>

                <p class="text-[10px] font-normal text-[#53a1b3]/50 uppercase tracking-widest px-2 mb-1 mt-4">Content</p>

                <a href="{{ route('admin.songs.index') }}" class="flex items-center gap-3 px-3 py-2.5 group transition {{ request()->routeIs('admin.songs.*') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
                    <ion-icon name="musical-notes-outline" class="w-5 h-5"></ion-icon>
                    <span class="text-xs font-normal uppercase tracking-wider">Songs</span>
                </a>

                <a href="{{ route('admin.albums.index') }}" class="flex items-center gap-3 px-3 py-2.5 group transition {{ request()->routeIs('admin.albums.*') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
                    <ion-icon name="albums-outline" class="w-5 h-5"></ion-icon>
                    <span class="text-xs font-normal uppercase tracking-wider">Albums</span>
                </a>

                <a href="{{ route('admin.artists.index') }}" class="flex items-center gap-3 px-3 py-2.5 group transition {{ request()->routeIs('admin.artists.*') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
                    <ion-icon name="mic-outline" class="w-5 h-5"></ion-icon>
                    <span class="text-xs font-normal uppercase tracking-wider">Artists</span>
                </a>

                <a href="{{ route('admin.playlists.index') }}" class="flex items-center gap-3 px-3 py-2.5 group transition {{ request()->routeIs('admin.playlists.*') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
                    <ion-icon name="list-outline" class="w-5 h-5"></ion-icon>
                    <span class="text-xs font-normal uppercase tracking-wider">Playlists</span>
                </a>

                <p class="text-[10px] font-normal text-[#53a1b3]/50 uppercase tracking-widest px-2 mb-1 mt-4">Users</p>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 group transition {{ request()->routeIs('admin.users.*') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
                    <ion-icon name="people-outline" class="w-5 h-5"></ion-icon>
                    <span class="text-xs font-normal uppercase tracking-wider">Users</span>
                </a>

                <a href="{{ route('admin.comments.index') }}" class="flex items-center gap-3 px-3 py-2.5 group transition {{ request()->routeIs('admin.comments.*') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
                    <ion-icon name="chatbubbles-outline" class="w-5 h-5"></ion-icon>
                    <span class="text-xs font-normal uppercase tracking-wider">Comments</span>
                </a>

                <p class="text-[10px] font-normal text-[#53a1b3]/50 uppercase tracking-widest px-2 mb-1 mt-4">Settings</p>
                
                <a href="{{ route('admin.countries.index') }}" class="flex items-center gap-3 px-3 py-2.5 group transition {{ request()->routeIs('admin.countries.*') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
                    <ion-icon name="earth-outline" class="w-5 h-5"></ion-icon>
                    <span class="text-xs font-normal uppercase tracking-wider">Countries</span>
                </a>

                <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-3 py-2.5 group transition {{ request()->routeIs('admin.settings') ? 'bg-[#e96c4c] text-white shadow-lg shadow-[#e96c4c]/20' : 'text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white' }}">
                    <ion-icon name="settings-outline" class="w-5 h-5"></ion-icon>
                    <span class="text-xs font-normal uppercase tracking-wider">Settings</span>
                </a>

                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 group transition text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white">
                    <ion-icon name="arrow-back-outline" class="w-5 h-5"></ion-icon>
                    <span class="text-xs font-normal uppercase tracking-wider">Back to Site</span>
                </a>

                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="flex items-center gap-3 px-3 py-2.5 group transition text-[#53a1b3] hover:bg-red-500/10 hover:text-red-500">
                    <ion-icon name="log-out-outline" class="w-5 h-5"></ion-icon>
                    <span class="text-xs font-normal uppercase tracking-wider">Logout</span>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </nav>
        </aside>

        <!-- Application Content -->
        <div class="flex-1 flex flex-col relative w-full h-full md:h-screen md:overflow-y-auto">

            <!-- Admin Top Navbar -->
            <nav class="hidden md:flex sticky top-0 h-[68px] bg-[#141e24]/95 backdrop-blur-xl border-b border-[#53a1b3]/20 px-4 items-center justify-between z-50">
                <div class="flex items-center gap-3">
                    <h2 class="text-white font-normal text-base uppercase tracking-wider">@yield('page-title', 'Dashboard')</h2>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 px-3 py-2 bg-[#213042]">
                        <ion-icon name="person-circle-outline" class="w-5 h-5 text-[#53a1b3]"></ion-icon>
                        <span class="text-sm text-white">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </nav>

            <!-- Mobile Header -->
            <div class="md:hidden sticky top-0 h-[60px] bg-[#141e24]/95 backdrop-blur-xl border-b border-[#53a1b3]/20 px-4 flex items-center justify-between z-50">
                <h1 class="text-white font-normal text-base uppercase tracking-wider">Admin Panel</h1>
                <button onclick="toggleMobileMenu()" class="text-[#53a1b3] hover:text-white">
                    <ion-icon name="menu-outline" class="w-6 h-6"></ion-icon>
                </button>
            </div>

            <main class="flex-1 flex flex-col relative px-2 py-2 pt-[60px] md:pt-2 w-full max-w-7xl mx-auto">
                @if(session('success'))
                    <div class="mb-2 bg-emerald-500/10 border-y border-emerald-500/20 text-emerald-500 py-2 text-sm font-normal flex items-center gap-2 justify-center">
                        <ion-icon name="checkmark-outline" class="w-3 h-3"></ion-icon>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-2 bg-red-500/10 border-y border-red-500/20 text-red-500 py-2 text-sm font-normal flex items-center gap-2 justify-center">
                        <ion-icon name="close-outline" class="w-3 h-3"></ion-icon>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Bottom Navigation (Mobile Only) -->
            <nav class="md:hidden sticky bottom-0 h-[70px] bg-[#141e24]/95 backdrop-blur-xl border-t border-[#53a1b3]/20 flex items-center justify-around px-1 z-50 pb-2 w-full">

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-1 p-2 group {{ request()->routeIs('admin.dashboard') ? 'text-[#e96c4c]' : 'text-[#53a1b3] opacity-60 hover:opacity-100' }}">
                    <div class="w-6 h-6 flex items-center justify-center transition group-active:scale-90">
                        <ion-icon name="grid-outline" class="w-5 h-5"></ion-icon>
                    </div>
                    <span class="text-[8px] font-normal uppercase tracking-tighter">Dashboard</span>
                </a>

                <!-- Songs -->
                <a href="{{ route('admin.songs.index') }}" class="flex flex-col items-center gap-1 p-2 group {{ request()->routeIs('admin.songs.*') ? 'text-[#e96c4c]' : 'text-[#53a1b3] opacity-60 hover:opacity-100' }}">
                    <div class="w-6 h-6 flex items-center justify-center transition group-active:scale-90">
                        <ion-icon name="musical-notes-outline" class="w-5 h-5"></ion-icon>
                    </div>
                    <span class="text-[8px] font-normal uppercase tracking-tighter">Songs</span>
                </a>

                <!-- Users -->
                <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center gap-1 p-2 group {{ request()->routeIs('admin.users.*') ? 'text-[#e96c4c]' : 'text-[#53a1b3] opacity-60 hover:opacity-100' }}">
                    <div class="w-6 h-6 flex items-center justify-center transition group-active:scale-90">
                        <ion-icon name="people-outline" class="w-5 h-5"></ion-icon>
                    </div>
                    <span class="text-[8px] font-normal uppercase tracking-tighter">Users</span>
                </a>

                <!-- Settings -->
                <a href="{{ route('admin.settings') }}" class="flex flex-col items-center gap-1 p-2 group {{ request()->routeIs('admin.settings') ? 'text-[#e96c4c]' : 'text-[#53a1b3] opacity-60 hover:opacity-100' }}">
                    <div class="w-6 h-6 flex items-center justify-center transition group-active:scale-90">
                        <ion-icon name="settings-outline" class="w-5 h-5"></ion-icon>
                    </div>
                    <span class="text-[8px] font-normal uppercase tracking-tighter">Settings</span>
                </a>

                <!-- Back -->
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 p-2 group text-[#53a1b3] opacity-60 hover:opacity-100">
                    <div class="w-6 h-6 flex items-center justify-center transition group-active:scale-90">
                        <ion-icon name="arrow-back-outline" class="w-5 h-5"></ion-icon>
                    </div>
                    <span class="text-[8px] font-normal uppercase tracking-tighter">Exit</span>
                </a>

            </nav>
        </div>
    </div>

    <!-- Mobile Menu (Hidden by default) -->
    <div id="mobile-menu" class="hidden fixed inset-0 bg-black/80 z-[100] md:hidden">
        <div class="w-[280px] h-full bg-[#0f1319] border-r border-[#53a1b3]/10 overflow-y-auto p-4">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-white font-normal text-lg uppercase tracking-wider">Menu</h2>
                <button onclick="toggleMobileMenu()" class="text-[#53a1b3] hover:text-white">
                    <ion-icon name="close-outline" class="w-6 h-6"></ion-icon>
                </button>
            </div>

            <nav class="flex flex-col gap-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-3 text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white transition">
                    <ion-icon name="grid-outline" class="w-5 h-5"></ion-icon>
                    <span class="text-sm font-normal uppercase tracking-wider">Dashboard</span>
                </a>
                <a href="{{ route('admin.songs.index') }}" class="flex items-center gap-3 px-3 py-3 text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white transition">
                    <ion-icon name="musical-notes-outline" class="w-5 h-5"></ion-icon>
                    <span class="text-sm font-normal uppercase tracking-wider">Songs</span>
                </a>
                <a href="{{ route('admin.albums.index') }}" class="flex items-center gap-3 px-3 py-3 text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white transition">
                    <ion-icon name="albums-outline" class="w-5 h-5"></ion-icon>
                    <span class="text-sm font-normal uppercase tracking-wider">Albums</span>
                </a>
                <a href="{{ route('admin.artists.index') }}" class="flex items-center gap-3 px-3 py-3 text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white transition">
                    <ion-icon name="mic-outline" class="w-5 h-5"></ion-icon>
                    <span class="text-sm font-normal uppercase tracking-wider">Artists</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-3 text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white transition">
                    <ion-icon name="people-outline" class="w-5 h-5"></ion-icon>
                    <span class="text-sm font-normal uppercase tracking-wider">Users</span>
                </a>
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-3 text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white transition">
                    <ion-icon name="arrow-back-outline" class="w-5 h-5"></ion-icon>
                    <span class="text-sm font-normal uppercase tracking-wider">Back to Site</span>
                </a>
            </nav>
        </div>
    </div>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>

    <!-- Global Confirmation Modal -->
    <x-confirm-modal />

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
