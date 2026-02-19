@extends('layouts.app')

@section('content')
<div class="flex flex-col gap-0">

    <!-- Song List (Grid) -->
    <div id="songs-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-2 min-h-[50vh]">
        @if($songs->count() > 0)
            @include('songs.partials.list')
        @else
            <div class="flex flex-col items-center justify-center py-10 text-[#53a1b3]">
                <ion-icon name="search-outline" class="w-10 h-10 mb-2 opacity-50"></ion-icon>
                <p class="text-sm font-normal">No songs found</p>
                <p class="text-xs opacity-70">Try searching for something else</p>
            </div>
        @endif
    </div>

    <!-- Loading Indicator -->
    <div id="loading" class="hidden py-4 flex justify-center">
        <ion-icon name="cloud-upload-outline" class="w-6 h-6 animate-spin text-[#e96c4c]"></ion-icon>
    </div>

    <!-- Sentinel for Infinite Scroll -->
    <div id="sentinel" class="h-4"></div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let nextPageUrl = "{{ $songs->nextPageUrl() }}";
        const sentinel = document.getElementById('sentinel');
        const loading = document.getElementById('loading');
        const container = document.getElementById('songs-container');
        let currentAudio = null;
        let currentButton = null;

        if (nextPageUrl) {
            const observer = new IntersectionObserver(entries => {
                if (entries[0].isIntersecting && nextPageUrl) {
                    loadMoreSongs();
                }
            }, { rootMargin: '100px' });

            observer.observe(sentinel);
        }

        container.addEventListener('click', (event) => {
            const path = event.composedPath ? event.composedPath() : [];
            const button = path.find((el) => el instanceof HTMLElement && el.hasAttribute('data-play-btn'))
                || event.target.closest('[data-play-btn]');
            if (!button) return;

            event.preventDefault();

            const audioId = button.getAttribute('data-audio-id');
            const audio = document.getElementById(audioId);
            if (!audio) return;

            // Get song data from the row
            const row = button.closest('.group');
            const title = row.querySelector('.song-title')?.textContent.trim() || 'Unknown';
            const artist = row.querySelector('.song-artist')?.textContent.trim() || 'Unknown';
            const cover = row.querySelector('img')?.src || '';

            if (currentAudio && currentAudio !== audio) {
                currentAudio.pause();
                currentAudio.currentTime = 0;
                if (currentButton) {
                    const icon = currentButton.querySelector('ion-icon');
                    if (icon) icon.setAttribute('name', 'play');
                }
            }

            if (audio.paused) {
                audio.play().then(() => {
                    const icon = button.querySelector('ion-icon');
                    if (icon) icon.setAttribute('name', 'pause');
                    currentAudio = audio;
                    currentButton = button;

                    // Update global player
                    if (window.globalPlayer) {
                        window.globalPlayer.show(audio, { title, artist, cover }, button);
                    }
                    
                    // Register Play for Trending
                    const songIdNumeric = audioId.replace('audio-', '');
                    fetch(`/songs/${songIdNumeric}/play`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).catch(err => console.error('Failed to register play', err));

                }).catch(() => {
                    // Autoplay may be blocked
                });
            } else {
                audio.pause();
                const icon = button.querySelector('ion-icon');
                if (icon) icon.setAttribute('name', 'play');
            }
        });

        async function loadMoreSongs() {
            loading.classList.remove('hidden');

            try {
                const response = await fetch(nextPageUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                if (response.ok) {
                    const html = await response.text();
                    container.insertAdjacentHTML('beforeend', html);

                    // Note: Since we are using simplePaginate, we don't get the next page URL in the HTML response easily unless we parse it or the controller sends it in headers/JSON.
                    // For simplicity, let's assume we increment the page number manually or rely on the fact that if we got content, there might be more.
                    // However, standard Laravel separate partials usually just return HTML.
                    // A better way for infinite scroll with simplePaginate is to check if the response was empty or if we can predict the next page.
                    // Let's parse the current URL and increment page.

                    const url = new URL(nextPageUrl);
                    const currentPage = parseInt(url.searchParams.get('page'));
                    url.searchParams.set('page', currentPage + 1);
                    nextPageUrl = url.toString();

                    // Check if we reached end (simple check: if html is empty)
                    if (!html.trim()) {
                        nextPageUrl = null;
                        observer.unobserve(sentinel);
                    }
                } else {
                    nextPageUrl = null;
                }
            } catch (error) {
                console.error('Error loading songs:', error);
            } finally {
                loading.classList.add('hidden');
            }
        }
    });

    function shareSong(title, artist, url) {
        if (navigator.share) {
            navigator.share({
                title: title,
                text: `Listen to ${title} by ${artist}`,
                url: url
            });
        } else {
            const dummy = document.createElement('input');
            document.body.appendChild(dummy);
            dummy.value = url;
            dummy.select();
            try {
                document.execCommand('copy');
                alert('Link copied to clipboard!');
            } catch (err) {
                console.error('Copy failed', err);
            }
            document.body.removeChild(dummy);
        }
    }
</script>
@endsection
