<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Music App - Download and stream your favorite music">
    <meta name="theme-color" content="#141e24">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MusicApp') }}</title>

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192.png') }}">
    
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(registration) {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function(err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>

    @if(app()->environment('production'))
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

        <!-- Desktop Sidebar -->
        <x-desktop-sidebar class="hidden md:flex" />

        <!-- Application Content -->
        <div class="flex-1 flex flex-col relative w-full h-full md:h-screen md:overflow-y-auto">

        <!-- Desktop Navbar -->
        <x-desktop-navbar />

        <div class="md:hidden">
            <x-header />
        </div>

            <main class="flex-1 flex flex-col relative px-2 py-2 pt-[60px] md:pt-[68px] w-full max-w-7xl mx-auto">
                @if(session('success'))
                    <div class="mb-2 bg-emerald-500/10 border-y border-emerald-500/20 text-emerald-500 py-2 text-sm font-normal flex items-center gap-2 justify-center">
                        <ion-icon name="checkmark-outline" class="w-3 h-3"></ion-icon>
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Bottom Navigation (Mobile Only) -->
            <nav class="md:hidden sticky bottom-0 h-[70px] bg-[#141e24]/95 backdrop-blur-xl border-t border-[#53a1b3]/20 flex items-center justify-around px-1 z-50 pb-2 w-full">

                <!-- Home -->
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 p-2 group {{ request()->routeIs('home') ? 'text-[#e96c4c]' : 'text-[#53a1b3] opacity-60 hover:opacity-100' }}">
                    <div class="w-6 h-6 flex items-center justify-center transition group-active:scale-90">
                        <ion-icon name="musical-notes-outline" class="w-4 h-4"></ion-icon>
                    </div>
                    <span class="text-[8px] font-normal uppercase tracking-tighter">Home</span>
                </a>

                <!-- Browse -->
                <a href="{{ route('songs.index') }}" class="flex flex-col items-center gap-1 p-2 group {{ (request()->routeIs('songs.index') || request()->routeIs('songs.show') || request()->routeIs('artists.*')) ? 'text-[#e96c4c]' : 'text-[#53a1b3] opacity-60 hover:opacity-100' }}">
                    <div class="w-6 h-6 flex items-center justify-center transition group-active:scale-90">
                        <ion-icon name="globe-outline" class="w-4 h-4"></ion-icon>
                    </div>
                    <span class="text-[8px] font-normal uppercase tracking-tighter">Browse</span>
                </a>

                <!-- Playlists (Center) -->
                <a href="{{ route('playlists.index') }}" class="flex flex-col items-center gap-1 p-2 group {{ request()->routeIs('playlists.*') ? 'text-[#e96c4c]' : 'text-[#53a1b3] opacity-60 hover:opacity-100' }}">
                    <div class="w-6 h-6 flex items-center justify-center transition group-active:scale-90">
                        <ion-icon name="list-outline" class="w-4 h-4"></ion-icon>
                    </div>
                    <span class="text-[8px] font-normal uppercase tracking-tighter">Playlists</span>
                </a>

                <!-- Favorites -->
                <a href="{{ route('favorites') }}" class="flex flex-col items-center gap-1 p-2 group {{ request()->routeIs('favorites') ? 'text-[#e96c4c]' : 'text-[#53a1b3] opacity-60 hover:opacity-100' }}">
                    <div class="w-6 h-6 flex items-center justify-center transition group-active:scale-90">
                        <ion-icon name="heart-outline" class="w-4 h-4"></ion-icon>
                    </div>
                    <span class="text-[8px] font-normal uppercase tracking-tighter">Favorites</span>
                </a>

                <!-- Profile / Admin -->
                @auth
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-1 p-2 group {{ request()->routeIs('admin.*') ? 'text-[#e96c4c]' : 'text-[#53a1b3] opacity-60 hover:opacity-100' }}">
                        <div class="w-6 h-6 flex items-center justify-center transition group-active:scale-90">
                            <ion-icon name="shield-checkmark-outline" class="w-4 h-4"></ion-icon>
                        </div>
                        <span class="text-[8px] font-normal uppercase tracking-tighter">Admin</span>
                    </a>
                    @else
                    <a href="{{ route('profile') }}" class="flex flex-col items-center gap-1 p-2 group {{ request()->routeIs('profile*') ? 'text-[#e96c4c]' : 'text-[#53a1b3] opacity-60 hover:opacity-100' }}">
                        <div class="w-6 h-6 flex items-center justify-center transition group-active:scale-90">
                            <ion-icon name="person-outline" class="w-4 h-4"></ion-icon>
                        </div>
                        <span class="text-[8px] font-normal uppercase tracking-tighter">Profile</span>
                    </a>
                    @endif
                @else
                <button onclick="openLoginModal()" class="flex flex-col items-center gap-1 p-2 group {{ request()->routeIs('login') ? 'text-[#e96c4c]' : 'text-[#53a1b3] opacity-60 hover:opacity-100' }}">
                    <div class="w-6 h-6 flex items-center justify-center transition group-active:scale-90">
                        <ion-icon name="log-in-outline" class="w-4 h-4"></ion-icon>
                    </div>
                    <span class="text-[8px] font-normal uppercase tracking-tighter">Login</span>
                </button>
                @endauth

            </nav>
        </div>
    </div>

    <!-- Auth Modal -->
    <div id="auth-modal" onclick="closeAuthModal(event)" class="hidden fixed inset-0 bg-black/50 z-[100] flex items-start justify-center pt-64 p-4 transition-opacity">
        <div onclick="event.stopPropagation()" class="w-[350px] bg-[#1a2730] shadow-2xl rounded-[3px] overflow-hidden border border-[#53a1b3]/10 flex flex-col">
            
            <!-- Header (Tabs) -->
            <div class="px-2 pt-2 border-b border-[#53a1b3]/10">
                <div class="flex gap-4">
                    <!-- Login Tab -->
                    <button onclick="switchAuthTab('login')" class="relative pb-2 text-sm font-medium text-white transition-colors focus:outline-none uppercase tracking-wider">
                        Login
                        <div id="tab-indicator-login" class="absolute bottom-[-1px] left-0 w-full h-[2px] bg-[#e96c4c]"></div>
                    </button>
                    <!-- Register Tab -->
                    <button onclick="switchAuthTab('register')" class="relative pb-2 text-sm font-medium text-[#53a1b3] hover:text-white transition-colors focus:outline-none uppercase tracking-wider">
                        Register
                        <div id="tab-indicator-register" class="hidden absolute bottom-[-1px] left-0 w-full h-[2px] bg-[#e96c4c]"></div>
                    </button>
                </div>
            </div>

            <!-- Login View -->
            <div id="auth-content-login" class="flex flex-1 flex-col">
                <form method="POST" action="{{ route('login') }}" class="flex-1 flex flex-col">
                    @csrf
                    <!-- Content -->
                    <div class="p-2 space-y-2 flex-1">
                        <!-- Email -->
                        <div class="space-y-1">
                            <input id="login-email" type="email" name="email" required autocomplete="email" autofocus
                                   class="w-full h-[35px] bg-[#1a2730]/40 border border-[#53a1b3]/20 text-white text-xs px-2 rounded-[3px] focus:outline-none focus:border-[#e96c4c] placeholder-[#53a1b3]/50 transition"
                                   placeholder="Username or email">
                        </div>

                        <!-- Password -->
                        <div class="space-y-1">
                            <input id="login-password" type="password" name="password" required autocomplete="current-password"
                                   class="w-full h-[35px] bg-[#1a2730]/40 border border-[#53a1b3]/20 text-white text-xs px-2 rounded-[3px] focus:outline-none focus:border-[#e96c4c] placeholder-[#53a1b3]/50 transition"
                                   placeholder="Password">
                        </div>

                        <!-- Options -->
                        <div class="flex items-center justify-between text-xs pt-1">
                            <label class="flex items-center gap-2 cursor-pointer text-[#53a1b3] hover:text-white transition">
                                <input class="w-3.5 h-3.5 rounded-[2px] bg-[#1a2730] border-[#53a1b3]/20 text-[#e96c4c] focus:ring-0 focus:ring-offset-0" type="checkbox" name="remember" id="remember">
                                <span>Remember me</span>
                            </label>
                            
                            <a href="{{ route('password.request') }}" class="text-[#53a1b3] hover:text-[#e96c4c] transition">Forgot your password?</a>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="p-2 border-t border-[#53a1b3]/10 flex justify-end">
                        <button type="submit" class="px-4 py-2 border border-[#e96c4c] text-[#e96c4c] hover:bg-[#e96c4c] hover:text-white text-xs font-medium uppercase tracking-wider rounded-[3px] transition shadow-lg shadow-[#e96c4c]/5">
                            Login
                        </button>
                    </div>
                </form>
            </div>

            <!-- Register View -->
            <div id="auth-content-register" class="hidden flex-1 flex-col">
                <form method="POST" action="{{ route('register') }}" class="flex-1 flex flex-col">
                    @csrf
                    <!-- Content -->
                    <div class="p-2 space-y-2 flex-1">
                        <!-- Name -->
                        <div class="space-y-1">
                            <input id="register-name" type="text" name="name" required autocomplete="name"
                                   class="w-full h-[35px] bg-[#1a2730]/40 border border-[#53a1b3]/20 text-white text-xs px-2 rounded-[3px] focus:outline-none focus:border-[#e96c4c] placeholder-[#53a1b3]/50 transition"
                                   placeholder="Full Name">
                        </div>

                        <!-- Email -->
                        <div class="space-y-1">
                            <input id="register-email" type="email" name="email" required autocomplete="email"
                                   class="w-full h-[35px] bg-[#1a2730]/40 border border-[#53a1b3]/20 text-white text-xs px-2 rounded-[3px] focus:outline-none focus:border-[#e96c4c] placeholder-[#53a1b3]/50 transition"
                                   placeholder="Email Address">
                        </div>

                        <!-- Password -->
                        <div class="space-y-1">
                            <input id="register-password" type="password" name="password" required autocomplete="new-password"
                                   class="w-full h-[35px] bg-[#1a2730]/40 border border-[#53a1b3]/20 text-white text-xs px-2 rounded-[3px] focus:outline-none focus:border-[#e96c4c] placeholder-[#53a1b3]/50 transition"
                                   placeholder="Password">
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-1">
                            <input id="register-password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                                   class="w-full h-[35px] bg-[#1a2730]/40 border border-[#53a1b3]/20 text-white text-xs px-2 rounded-[3px] focus:outline-none focus:border-[#e96c4c] placeholder-[#53a1b3]/50 transition"
                                   placeholder="Confirm Password">
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="p-2 border-t border-[#53a1b3]/10 flex justify-end">
                        <button type="submit" class="px-4 py-2 border border-[#e96c4c] text-[#e96c4c] hover:bg-[#e96c4c] hover:text-white text-xs font-medium uppercase tracking-wider rounded-[3px] transition shadow-lg shadow-[#e96c4c]/5">
                            Register
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Click outside to close (Optional invisible overlay logic handled by JS or just the button inside?) -->
        <button onclick="closeAuthModal()" class="absolute top-4 right-4 text-white/50 hover:text-white transition md:hidden">
            <ion-icon name="close-circle-outline" class="w-8 h-8"></ion-icon>
        </button>
    </div>

    <x-player />

    <!-- Filter Bottom Sheet -->

    <!-- Filter Bottom Sheet -->
    <div id="filter-sheet" class="fixed inset-0 z-[60] hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity opacity-0" id="filter-backdrop" onclick="toggleFilters()"></div>

        <!-- Sheet -->
        <div class="absolute bottom-0 left-0 right-0 bg-[#1a2730] border-t border-[#53a1b3]/20 rounded-none transform translate-y-full transition-transform duration-300 flex flex-col max-h-[70vh]" id="filter-content">

            <!-- Handle -->
            <div class="w-full flex justify-center pt-3 pb-1" onclick="toggleFilters()">
                <div class="w-10 h-1 bg-[#53a1b3]/20 rounded-none"></div>
            </div>

            <!-- Header -->
            <div class="px-4 py-2 flex justify-between items-center border-b border-[#53a1b3]/10">
                <h3 class="text-[#e96c4c] font-normal uppercase tracking-wider text-sm">Filter Music</h3>
                <button onclick="toggleFilters()" class="text-[#53a1b3] hover:text-white">
                    <ion-icon name="close-outline" class="w-5 h-5"></ion-icon>
                </button>
            </div>

            <!-- Content -->
            <div class="p-4 overflow-y-auto">
                <h4 class="text-[#53a1b3] text-xs font-normal uppercase mb-3">By Genre</h4>
                <div class="grid grid-cols-2 gap-2">
                    @php
                        $genres = ['All', 'Bongo Flava', 'Hip Hop', 'Gospel', 'Amapiano', 'Afrobeat', 'Singeli', 'Zouk', 'Taarab'];
                    @endphp
                    @foreach($genres as $genre)
                    <a href="{{ route('songs.index', ['search' => $genre === 'All' ? '' : $genre]) }}"
                       class="px-3 py-2 text-xs font-normal uppercase tracking-wider border text-center transition {{ request('search') == $genre ? 'bg-[#e96c4c] border-[#e96c4c] text-white' : 'bg-[#141e24] border-[#53a1b3]/20 text-[#53a1b3] hover:border-[#e96c4c]/50 hover:text-[#e96c4c]' }}">
                        {{ $genre }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFilters() {
            const sheet = document.getElementById('filter-sheet');
            const backdrop = document.getElementById('filter-backdrop');
            const content = document.getElementById('filter-content');

            if (sheet.classList.contains('hidden')) {
                // Open
                sheet.classList.remove('hidden');
                // Trigger reflow
                void sheet.offsetWidth;

                backdrop.classList.remove('opacity-0');
                content.classList.remove('translate-y-full');
            } else {
                // Close
                backdrop.classList.add('opacity-0');
                content.classList.add('translate-y-full');

                setTimeout(() => {
                    sheet.classList.add('hidden');
                }, 300);
            }
        }

        // Auth Modals

        // Auth Modals
        function openLoginModal() {
            document.getElementById('auth-modal').classList.remove('hidden');
            switchAuthTab('login');
        }

        function openRegisterModal() {
            document.getElementById('auth-modal').classList.remove('hidden');
            switchAuthTab('register');
        }

        function closeAuthModal(event) {
            if (event && event.target.id !== 'auth-modal' && !event.target.closest('button')) return;
            document.getElementById('auth-modal').classList.add('hidden');
        }

        function switchAuthTab(tab) {
            const loginContent = document.getElementById('auth-content-login');
            const registerContent = document.getElementById('auth-content-register');
            const loginIndicator = document.getElementById('tab-indicator-login');
            const registerIndicator = document.getElementById('tab-indicator-register');

            if (tab === 'login') {
                loginContent.classList.remove('hidden');
                registerContent.classList.add('hidden');
                
                loginIndicator.classList.remove('hidden');
                registerIndicator.classList.add('hidden');
                
                loginIndicator.parentElement.classList.add('text-white');
                loginIndicator.parentElement.classList.remove('text-[#53a1b3]');
                registerIndicator.parentElement.classList.add('text-[#53a1b3]');
                registerIndicator.parentElement.classList.remove('text-white');
            } else {
                loginContent.classList.add('hidden');
                registerContent.classList.remove('hidden');
                
                loginIndicator.classList.add('hidden');
                registerIndicator.classList.remove('hidden');
                
                registerIndicator.parentElement.classList.add('text-white');
                registerIndicator.parentElement.classList.remove('text-[#53a1b3]');
                loginIndicator.parentElement.classList.add('text-[#53a1b3]');
                loginIndicator.parentElement.classList.remove('text-white');
            }
        }

        // Global AJAX Like Toggle
        async function toggleLike(type, id, btn) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            try {
                const response = await fetch(`/interactions/${type}/${id}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (response.status === 302 || response.redirected) {
                    // Not logged in — redirect to login
                    window.location.href = '/login';
                    return;
                }

                const data = await response.json();
                const liked = data.liked;

                // Find the icon: either inside the passed btn, or by common IDs
                const icon = btn ? btn.querySelector('.like-icon, ion-icon') : null;

                if (liked) {
                    if (icon) icon.setAttribute('name', 'heart');
                    if (btn) {
                        btn.classList.add('text-red-500');
                        btn.classList.remove('text-[#53a1b3]/40', 'text-[#53a1b3]');
                    }
                    // Also update song page specific buttons
                    ['like-icon-song-desktop', 'like-icon-song-mobile', 'like-icon-album'].forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.setAttribute('name', 'heart');
                    });
                    ['like-btn-song-desktop', 'like-btn-song-mobile', 'like-btn-album'].forEach(id => {
                        const el = document.getElementById(id);
                        if (el) { el.classList.add('text-red-500'); el.classList.remove('text-[#53a1b3]/40', 'text-[#53a1b3]'); }
                    });
                } else {
                    if (icon) icon.setAttribute('name', 'heart-outline');
                    if (btn) {
                        btn.classList.remove('text-red-500');
                        btn.classList.add('text-[#53a1b3]/40');
                    }
                    ['like-icon-song-desktop', 'like-icon-song-mobile', 'like-icon-album'].forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.setAttribute('name', 'heart-outline');
                    });
                    ['like-btn-song-desktop', 'like-btn-song-mobile', 'like-btn-album'].forEach(id => {
                        const el = document.getElementById(id);
                        if (el) { el.classList.remove('text-red-500'); el.classList.add('text-[#53a1b3]/40'); }
                    });
                }
            } catch (e) {
                console.error('Like failed:', e);
            }
        }

        // Global char counter (used by comments component)
        function updateCharCount(listId) {
            const textarea = document.getElementById('comment-body-' + listId)
                          || document.getElementById('comment-body');
            const counter  = document.getElementById('char-count-' + listId)
                          || document.getElementById('char-count');
            if (textarea && counter) {
                counter.textContent = 250 - textarea.value.length;
            }
        }

        // Compact time helper: "4m", "2h", "3d", "1w", "2mo"
        function timeAgo(dateStr) {
            const diff = Math.floor((Date.now() - new Date(dateStr)) / 1000);
            if (isNaN(diff) || diff < 5) return 'just now';
            if (diff < 60) return diff + 's ago';
            if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
            if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
            if (diff < 604800) return Math.floor(diff / 86400) + 'd ago';
            if (diff < 2592000) return Math.floor(diff / 604800) + 'w ago';
            return Math.floor(diff / 2592000) + 'mo ago';
        }

        // Global AJAX Comment Submit
        async function submitComment(type, id, listId) {
            const textarea = document.getElementById('comment-body-' + listId)
                          || document.getElementById('comment-body');
            const body = textarea ? textarea.value.trim() : '';
            if (!body) return;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            try {
                const response = await fetch(`/interactions/${type}/${id}/comment`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ body })
                });

                if (response.redirected || response.status === 302) {
                    window.location.href = '/login';
                    return;
                }

                const data = await response.json();
                if (!data.success) return;

                const c = data.comment;
                const list = document.getElementById(listId);
                if (!list) return;

                // Hide empty state
                const noComments = list.querySelector('[id^="no-comments"]');
                if (noComments) noComments.remove();

                // Build comment HTML
                const div = document.createElement('div');
                div.id = `comment-${c.id}`;
                div.className = 'group relative flex items-start gap-2 transition duration-300';
                div.innerHTML = `
                    <div class="relative w-12 h-12 shrink-0 bg-[#1a2730]/40 rounded-[3px] overflow-hidden border border-white/5">
                        <div class="w-full h-full flex items-center justify-center text-[#53a1b3]/30">
                            <span class="text-sm font-normal uppercase">${c.user_name.charAt(0)}</span>
                        </div>
                    </div>
                    <div class="flex flex-col min-w-0 flex-1">
                        <span class="text-white text-[13px] font-normal uppercase tracking-wide truncate group-hover:text-[#e96c4c] transition duration-300">${c.user_name}</span>
                        <p class="text-[#53a1b3]/50 text-[10px] uppercase tracking-widest break-words mt-0.5">${c.body}</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0 pt-0.5">
                        <span class="text-[#53a1b3]/30 text-[9px] uppercase tracking-widest font-mono whitespace-nowrap">${timeAgo(c.created_at)}</span>
                        <button onclick="deleteComment(${c.id}, this)" class="w-8 h-8 flex items-center justify-center text-[#53a1b3]/20 hover:text-red-500 transition rounded-[3px] hover:bg-red-500/5">
                            <ion-icon name="trash-outline" class="w-4 h-4"></ion-icon>
                        </button>
                    </div>
                `;

                list.insertBefore(div, list.firstChild);
                textarea.value = '';

                const charCount = document.getElementById('char-count-' + listId)
                               || document.getElementById('char-count');
                if (charCount) charCount.textContent = '250';

            } catch (e) {
                console.error('Comment submit failed:', e);
            }
        }

        // Global AJAX Comment Delete
        async function deleteComment(commentId, btn) {
            confirmAction({
                title: 'Delete Comment',
                message: 'Are you sure you want to delete this comment? This action cannot be undone.',
                confirmLabel: 'Delete',
                icon: 'trash-outline',
                danger: true,
                onConfirm: async () => {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                    try {
                        const response = await fetch(`/interactions/comment/${commentId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        });
                        const data = await response.json();
                        if (data.success) {
                            const commentEl = document.getElementById(`comment-${commentId}`);
                            if (commentEl) {
                                commentEl.style.transition = 'opacity 0.3s';
                                commentEl.style.opacity = '0';
                                setTimeout(() => commentEl.remove(), 300);
                            }
                        }
                    } catch (e) {
                        console.error('Delete comment failed:', e);
                    }
                }
            });
        }
    </script>
    <!-- Global Interaction Components -->
    <x-confirm-modal />
    <x-bottom-sheet />

    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
