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
                <a href="{{ route('songs.index') }}" class="flex flex-col items-center gap-1 p-2 group {{ (request()->routeIs('songs.index') || request()->routeIs('songs.show') || request()->routeIs('artists.show')) ? 'text-[#e96c4c]' : 'text-[#53a1b3] opacity-60 hover:opacity-100' }}">
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
    <div id="auth-modal" onclick="closeAuthModal(event)" class="hidden fixed inset-0 bg-black/80 z-[100] flex items-start justify-center pt-64 p-4 backdrop-blur-sm transition-opacity">
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

    <!-- Global Player Bar -->
    <div id="global-player" class="hidden fixed bottom-[70px] md:bottom-0 left-0 md:left-[250px] right-0 h-16 bg-[#1a2730] border-t border-[#53a1b3]/20 z-40 px-4">
        <div class="max-w-7xl mx-auto h-full flex items-center gap-4">
            <!-- Song Info -->
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <img id="player-cover" src="" class="w-12 h-12 object-cover" alt="">
                <div class="flex-1 min-w-0">
                    <h4 id="player-title" class="text-white text-sm font-normal truncate">Song Title</h4>
                    <p id="player-artist" class="text-[#53a1b3] text-xs truncate">Artist</p>
                </div>
            </div>

            <!-- Controls -->
            <div class="flex items-center gap-3">
                <button id="player-prev" class="text-[#53a1b3] hover:text-white transition">
                    <ion-icon name="play-back" class="w-5 h-5"></ion-icon>
                </button>
                <button id="player-play" class="w-10 h-10 bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white flex items-center justify-center transition">
                    <ion-icon name="play" class="w-5 h-5"></ion-icon>
                </button>
                <button id="player-next" class="text-[#53a1b3] hover:text-white transition">
                    <ion-icon name="play-forward" class="w-5 h-5"></ion-icon>
                </button>
            </div>

            <!-- Progress -->
            <div class="hidden md:flex items-center gap-2 flex-1">
                <span id="player-current" class="text-[10px] text-[#53a1b3] font-mono">0:00</span>
                <input id="player-seek" type="range" min="0" max="100" value="0" class="flex-1 h-1 bg-[#53a1b3]/20 appearance-none cursor-pointer accent-[#e96c4c]">
                <span id="player-duration" class="text-[10px] text-[#53a1b3] font-mono">0:00</span>
            </div>

            <!-- Volume -->
            <div class="hidden md:flex items-center gap-2">
                <ion-icon name="volume-medium" class="w-5 h-5 text-[#53a1b3]"></ion-icon>
                <input id="player-volume" type="range" min="0" max="100" value="100" class="w-20 h-1 bg-[#53a1b3]/20 appearance-none cursor-pointer accent-[#e96c4c]">
            </div>
        </div>

        <!-- Mobile Progress Bar -->
        <div class="md:hidden absolute top-0 left-0 right-0 h-0.5 bg-[#53a1b3]/20">
            <div id="player-progress-mobile" class="h-full bg-[#e96c4c] w-0"></div>
        </div>
    </div>

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

        // Global Player Control
        window.globalPlayer = {
            audio: null,
            currentButton: null,

            show(audioElement, songData, button) {
                const player = document.getElementById('global-player');
                const playBtn = document.getElementById('player-play');
                const playIcon = playBtn.querySelector('ion-icon');

                // Update UI
                document.getElementById('player-cover').src = songData.cover || '';
                document.getElementById('player-title').textContent = songData.title;
                document.getElementById('player-artist').textContent = songData.artist;

                // Show player
                player.classList.remove('hidden');

                // Set current audio
                if (this.audio && this.audio !== audioElement) {
                    this.audio.pause();
                    this.audio.currentTime = 0;
                    if (this.currentButton) {
                        const icon = this.currentButton.querySelector('ion-icon');
                        if (icon) icon.setAttribute('name', 'play');
                    }
                }

                this.audio = audioElement;
                this.currentButton = button;

                // Update play button
                if (audioElement.paused) {
                    playIcon.setAttribute('name', 'play');
                } else {
                    playIcon.setAttribute('name', 'pause');
                }

                // Bind events
                this.bindEvents();
            },

            bindEvents() {
                const playBtn = document.getElementById('player-play');
                const seekBar = document.getElementById('player-seek');
                const volumeBar = document.getElementById('player-volume');
                const currentTime = document.getElementById('player-current');
                const duration = document.getElementById('player-duration');
                const progressMobile = document.getElementById('player-progress-mobile');

                // Play/Pause
                playBtn.onclick = () => {
                    if (!this.audio) return;

                    if (this.audio.paused) {
                        this.audio.play();
                        playBtn.querySelector('ion-icon').setAttribute('name', 'pause');
                        if (this.currentButton) {
                            const icon = this.currentButton.querySelector('ion-icon');
                            if (icon) icon.setAttribute('name', 'pause');
                        }
                    } else {
                        this.audio.pause();
                        playBtn.querySelector('ion-icon').setAttribute('name', 'play');
                        if (this.currentButton) {
                            const icon = this.currentButton.querySelector('ion-icon');
                            if (icon) icon.setAttribute('name', 'play');
                        }
                    }
                };

                // Update progress
                if (!this.audio.dataset.globalBound) {
                    this.audio.addEventListener('timeupdate', () => {
                        if (!this.audio || !this.audio.duration) return;

                        const percent = (this.audio.currentTime / this.audio.duration) * 100;
                        if (seekBar) seekBar.value = percent;
                        if (progressMobile) progressMobile.style.width = percent + '%';
                        if (currentTime) currentTime.textContent = this.formatTime(this.audio.currentTime);
                    });

                    this.audio.addEventListener('loadedmetadata', () => {
                        if (duration && this.audio.duration) {
                            duration.textContent = this.formatTime(this.audio.duration);
                        }
                    });

                    this.audio.addEventListener('ended', () => {
                        playBtn.querySelector('ion-icon').setAttribute('name', 'play');
                        if (this.currentButton) {
                            const icon = this.currentButton.querySelector('ion-icon');
                            if (icon) icon.setAttribute('name', 'play');
                        }
                    });

                    this.audio.dataset.globalBound = '1';
                }

                // Seek
                if (seekBar && !seekBar.dataset.bound) {
                    seekBar.addEventListener('input', () => {
                        if (!this.audio || !this.audio.duration) return;
                        this.audio.currentTime = (seekBar.value / 100) * this.audio.duration;
                    });
                    seekBar.dataset.bound = '1';
                }

                // Volume
                if (volumeBar && !volumeBar.dataset.bound) {
                    volumeBar.addEventListener('input', () => {
                        if (!this.audio) return;
                        this.audio.volume = volumeBar.value / 100;
                    });
                    volumeBar.dataset.bound = '1';
                }
            },

            formatTime(seconds) {
                if (!seconds || !isFinite(seconds)) return '0:00';
                const mins = Math.floor(seconds / 60);
                const secs = Math.floor(seconds % 60);
                return `${mins}:${secs.toString().padStart(2, '0')}`;
            }
        };

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
    </script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
