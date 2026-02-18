<div id="global-player" class="hidden fixed bottom-[70px] md:bottom-0 left-0 md:left-[250px] right-0 h-16 bg-[#1a2730] border-t border-[#53a1b3]/20 z-40 px-4">
    <div class="max-w-7xl mx-auto h-full flex items-center gap-4">
        <!-- Song Info -->
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <img id="player-cover" src="" class="w-12 h-12 object-cover rounded-[3px]" alt="">
            <div class="flex-1 min-w-0">
                <h4 id="player-title" class="text-white text-sm font-normal truncate uppercase tracking-wide">Song Title</h4>
                <p id="player-artist" class="text-[#53a1b3]/60 text-[10px] uppercase tracking-[0.2em] truncate">Artist</p>
            </div>
        </div>

        <!-- Controls -->
        <div class="flex items-center gap-3">
            <button id="player-prev" onclick="window.globalPlayer.prev()" class="text-[#53a1b3] hover:text-white transition group">
                <ion-icon name="play-back" class="w-5 h-5 transition group-active:scale-90"></ion-icon>
            </button>
            <button id="player-play" class="w-10 h-10 bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white flex items-center justify-center transition rounded-[3px] shadow-lg shadow-[#e96c4c]/10 group">
                <ion-icon name="play" class="w-5 h-5 transition group-active:scale-95"></ion-icon>
            </button>
            <button id="player-next" onclick="window.globalPlayer.next()" class="text-[#53a1b3] hover:text-white transition group">
                <ion-icon name="play-forward" class="w-5 h-5 transition group-active:scale-90"></ion-icon>
            </button>
        </div>

        <!-- Progress -->
        <div class="hidden md:flex items-center gap-2 flex-1">
            <span id="player-current" class="text-[10px] text-[#53a1b3]/60 font-mono">0:00</span>
            <input id="player-seek" type="range" min="0" max="100" value="0" class="flex-1 h-0.5 bg-[#53a1b3]/10 appearance-none cursor-pointer accent-[#e96c4c]">
            <span id="player-duration" class="text-[10px] text-[#53a1b3]/60 font-mono">0:00</span>
        </div>

        <!-- Volume -->
        <div class="hidden md:flex items-center gap-4 ml-2">
            <div class="flex items-center gap-2">
                <ion-icon name="volume-medium" class="w-4 h-4 text-[#53a1b3]/40"></ion-icon>
                <input id="player-volume" type="range" min="0" max="100" value="100" class="w-16 h-0.5 bg-[#53a1b3]/10 appearance-none cursor-pointer accent-[#e96c4c]">
            </div>
            
            <!-- Close Player Button -->
            <button onclick="document.getElementById('global-player').classList.add('hidden'); if(window.globalPlayer.audio) window.globalPlayer.audio.pause();" 
                    class="text-[#53a1b3]/20 hover:text-white transition">
                <ion-icon name="close-outline" class="w-5 h-5"></ion-icon>
            </button>
        </div>
    </div>

    <!-- Mobile Progress Bar -->
    <div class="md:hidden absolute top-0 left-0 right-0 h-0.5 bg-[#53a1b3]/10">
        <div id="player-progress-mobile" class="h-full bg-[#e96c4c] w-0 transition-all duration-300"></div>
    </div>
</div>

<script>
    // Global Player Control
    window.globalPlayer = {
        audio: null,
        currentButton: null,
        queue: [],
        currentIndex: -1,

        show(audioElement, songData, button) {
            const player = document.getElementById('global-player');
            const playBtn = document.getElementById('player-play');
            const playIcon = playBtn.querySelector('ion-icon');

            // Build queue if not present or new song is not in queue
            this.buildQueue(audioElement.id);

            // Update UI
            document.getElementById('player-cover').src = songData.cover || '';
            document.getElementById('player-title').textContent = songData.title;
            document.getElementById('player-artist').textContent = songData.artist;

            // Show player with slide up animation
            if (player.classList.contains('hidden')) {
                player.classList.remove('hidden');
                player.classList.add('animate-slide-up');
            }

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

        buildQueue(currentAudioId) {
            // Find all songs in the current page context
            const allPlayBtns = Array.from(document.querySelectorAll('[data-play-btn]'));
            this.queue = allPlayBtns.map(btn => ({
                id: btn.getAttribute('data-audio-id'),
                button: btn,
                title: btn.closest('.group').querySelector('.song-title')?.textContent.trim(),
                artist: btn.closest('.group').querySelector('.song-artist')?.textContent.trim(),
                cover: btn.closest('.group').querySelector('img')?.src
            }));

            this.currentIndex = this.queue.findIndex(item => item.id === currentAudioId);
        },

        next() {
            if (this.currentIndex < this.queue.length - 1) {
                this.playAtIndex(this.currentIndex + 1);
            }
        },

        prev() {
            if (this.currentIndex > 0) {
                this.playAtIndex(this.currentIndex - 1);
            }
        },

        playAtIndex(index) {
            const item = this.queue[index];
            if (!item) return;

            const audioElement = document.getElementById(item.id);
            if (!audioElement) return;

            // Reset current button icon if exists
            if (this.currentButton) {
                const icon = this.currentButton.querySelector('ion-icon');
                if (icon) icon.setAttribute('name', 'play');
            }

            this.currentIndex = index;
            this.audio.pause();
            this.audio.currentTime = 0;
            
            this.audio = audioElement;
            this.currentButton = item.button;

            // Update UI
            document.getElementById('player-cover').src = item.cover || '';
            document.getElementById('player-title').textContent = item.title;
            document.getElementById('player-artist').textContent = item.artist;

            this.audio.play();
            document.getElementById('player-play').querySelector('ion-icon').setAttribute('name', 'pause');
            
            const btnIcon = this.currentButton.querySelector('ion-icon');
            if (btnIcon) btnIcon.setAttribute('name', 'pause');

            this.bindEvents();
        },

        bindEvents() {
            const playBtn = document.getElementById('player-play');
            const seekBar = document.getElementById('player-seek');
            const volumeBar = document.getElementById('player-volume');
            const currentTimeStr = document.getElementById('player-current');
            const durationStr = document.getElementById('player-duration');
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
                    if (currentTimeStr) currentTimeStr.textContent = this.formatTime(this.audio.currentTime);
                });

                this.audio.addEventListener('loadedmetadata', () => {
                    if (durationStr && this.audio.duration) {
                        durationStr.textContent = this.formatTime(this.audio.duration);
                    }
                });

                this.audio.addEventListener('ended', () => {
                    if (this.currentIndex < this.queue.length - 1) {
                        this.next();
                    } else {
                        playBtn.querySelector('ion-icon').setAttribute('name', 'play');
                        if (this.currentButton) {
                            const icon = this.currentButton.querySelector('ion-icon');
                            if (icon) icon.setAttribute('name', 'play');
                        }
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

    // Global listener for play buttons
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-play-btn]');
        if (btn) {
            const audioId = btn.getAttribute('data-audio-id');
            const audio = document.getElementById(audioId);
            if (!audio) return;

            const songGroup = btn.closest('.group');
            const songData = {
                title: songGroup.querySelector('.song-title')?.textContent.trim() || 'Unknown Title',
                artist: songGroup.querySelector('.song-artist')?.textContent.trim() || 'Unknown Artist',
                cover: songGroup.querySelector('img')?.src || ''
            };

            if (audio.paused) {
                audio.play();
                window.globalPlayer.show(audio, songData, btn);
                const icon = btn.querySelector('ion-icon');
                if (icon) icon.setAttribute('name', 'pause');
            } else {
                audio.pause();
                const icon = btn.querySelector('ion-icon');
                if (icon) icon.setAttribute('name', 'play');
                document.getElementById('player-play').querySelector('ion-icon').setAttribute('name', 'play');
            }
        }
    });
</script>

<style>
    @keyframes slide-up {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
    .animate-slide-up {
        animation: slide-up 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
</style>
