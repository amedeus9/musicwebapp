@extends('layouts.app')

@section('content')
<div class="flex flex-col gap-2">

    {{-- Latest Releases --}}
    <div>
        <x-section-header title="Latest Releases" scroll-id="latest-releases-scroll" />
        <div id="latest-releases-scroll" class="flex overflow-x-auto gap-2 pb-2 scrollbar-hide scroll-smooth">
            @foreach($latestReleases as $song)
            <div class="flex-shrink-0 w-[150px] group">
                @php
                    $artistProfile = $song->artistProfile;
                    $countrySlug = $artistProfile?->country->slug ?? 'global';
                    $artistSlug = $artistProfile?->slug ?? \Illuminate\Support\Str::slug($song->artist);
                    $songUrl = route('songs.show', ['country' => $countrySlug, 'artist' => $artistSlug, 'song' => $song->slug]);
                @endphp
                <a href="{{ $songUrl }}" class="block relative mb-2">
                    <div class="w-[150px] h-[150px] border border-[#53a1b3]/20 group-hover:border-[#e96c4c]/50 transition overflow-hidden">
                        <x-cover-art :path="$song->cover_path" :alt="$song->title" :hover="true" fallback-icon="musical-notes-outline" />
                    </div>
                    <div class="absolute bottom-1 right-1 bg-black/60 px-1.5 text-[10px] font-normal text-white">NEW</div>
                </a>
                <h3 class="font-normal text-white text-sm leading-tight truncate px-0.5">{{ $song->title }}</h3>
                <p class="text-[10px] text-[#53a1b3] truncate px-0.5">{{ $song->artist }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Trending By Location Sections --}}
    @foreach ($trendingSections as $section)
    <div class="mt-2">
        <x-section-header :title="$section['title']" :scroll-id="$section['scroll_id']" />
        <div id="{{ $section['scroll_id'] }}" class="flex overflow-x-auto gap-2 pb-2 scrollbar-hide scroll-smooth px-1">
            @foreach($section['songs'] as $song)
            <div class="flex-shrink-0 w-[150px] group relative">
                @php
                    $artistProfile = $song->artistProfile;
                    $countrySlug = $artistProfile?->country->slug ?? 'global';
                    $artistSlug = $artistProfile?->slug ?? \Illuminate\Support\Str::slug($song->artist);
                    $songUrl = route('songs.show', ['country' => $countrySlug, 'artist' => $artistSlug, 'song' => $song->slug]);
                @endphp
                <a href="{{ $songUrl }}" class="block relative mb-2">
                    <div class="w-[150px] h-[150px] border border-[#53a1b3]/20 group-hover:border-[#e96c4c]/50 transition overflow-hidden bg-[#0f1319]">
                        <x-cover-art :path="$song->cover_path" :alt="$song->title" :hover="true" fallback-icon="flame" />
                    </div>
                    <!-- Rank Badge -->
                    <div class="absolute top-0 left-0 bg-[#e96c4c] px-2 py-0.5 text-[10px] font-bold text-white z-10 rounded-br-[3px] shadow-sm">
                        #{{ $loop->iteration }}
                    </div>
                </a>
                <h3 class="font-normal text-white text-sm leading-tight truncate px-0.5">{{ $song->title }}</h3>
                <p class="text-[10px] text-[#53a1b3] truncate px-0.5">{{ $song->artist }}</p>
                
                <!-- Stats Row -->
                <div class="flex items-center gap-3 px-0.5 mt-1 text-[10px] text-[#53a1b3]/60">
                    <div class="flex items-center gap-1">
                        <ion-icon name="play" class="w-3 h-3"></ion-icon>
                        <span>{{ $song->recent_plays_count ?? 0 }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <ion-icon name="download" class="w-3 h-3"></ion-icon>
                        <span>{{ $song->recent_downloads_count ?? 0 }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    {{-- Featured Albums --}}
    <div class="mt-2">
        <x-section-header title="Featured Albums" scroll-id="featured-albums-scroll" />
        <div id="featured-albums-scroll" class="flex overflow-x-auto gap-2 pb-2 scrollbar-hide px-1 scroll-smooth">
            @foreach($albums as $album)
            <a href="{{ route('albums.show', $album) }}" class="flex-shrink-0 w-[120px] group block">
                <div class="w-[120px] h-[120px] bg-[#0f1319] border border-[#53a1b3]/20 mb-2 relative overflow-hidden">
                    <x-cover-art :path="$album->cover_path" :alt="$album->title" :hover="true" fallback-icon="musical-notes-outline" />
                </div>
                <h3 class="font-normal text-white text-sm leading-tight truncate px-0.5 group-hover:text-[#e96c4c] transition">{{ $album->title }}</h3>
                <p class="text-[10px] text-[#53a1b3] truncate px-0.5">{{ $album->artistProfile->name ?? 'Unknown' }}</p>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Top Playlists --}}
    <div class="mt-2">
        <x-section-header title="Top Playlists" scroll-id="top-playlists-scroll" />
        <div id="top-playlists-scroll" class="flex overflow-x-auto gap-2 pb-2 scrollbar-hide px-1 scroll-smooth">
            @foreach($topPlaylists as $playlist)
            <a href="{{ route('playlists.show', $playlist->slug) }}" class="flex-shrink-0 w-[120px] md:w-[180px] group block">
                <div class="w-[120px] h-[120px] md:w-[180px] md:h-[180px] bg-[#0f1319] border border-[#53a1b3]/20 mb-2 relative overflow-hidden">
                    <x-cover-art :path="$playlist->cover_path" :alt="$playlist->name" :hover="true" fallback-icon="list-outline" />
                    @if($playlist->cover_path)
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                        <ion-icon name="play" class="w-8 h-8 md:w-12 md:h-12 text-white"></ion-icon>
                    </div>
                    @endif
                </div>
                <h3 class="font-normal text-white text-sm leading-tight truncate px-0.5 group-hover:text-[#e96c4c] transition">{{ $playlist->name }}</h3>
                <div class="flex items-center gap-1.5 mt-1 px-0.5">
                    <p class="text-[10px] text-[#53a1b3] truncate max-w-[60px] md:max-w-[100px]">{{ $playlist->user->name }}</p>
                    <div class="flex items-center gap-0.5 text-[10px] text-[#53a1b3]">
                        <ion-icon name="musical-notes-outline" class="w-3 h-3"></ion-icon>
                        <span>{{ $playlist->songs_count }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Popular Artists --}}
    <div class="mt-2">
        <x-section-header title="Popular Artists" :view-all="'#'" />
        <div class="grid grid-cols-4 md:grid-cols-6 gap-2 px-1">
            @foreach($artists as $artist)
            <a href="{{ route('artists.show', ['country' => $artist->country->slug ?? 'global', 'artist' => $artist]) }}" class="group block text-center">
                <div class="aspect-square bg-[#0f1319] border border-[#53a1b3]/20 mb-2 relative overflow-hidden w-full">
                    <x-cover-art :path="$artist->image_path" :alt="$artist->name" :hover="true" fallback-icon="mic-outline" />
                </div>
                <h3 class="font-normal text-white text-xs leading-tight truncate px-0.5 group-hover:text-[#e96c4c] transition">{{ $artist->name }}</h3>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Browse Genre --}}
    <div>
        <x-section-header title="Browse Genre" scroll-id="browse-genre-scroll" />
        <div id="browse-genre-scroll" class="flex overflow-x-auto gap-2 pb-2 scrollbar-hide scroll-smooth">
            @php
                $genres = [
                    ['name' => 'Bongo Flava', 'icon' => 'musical-notes-outline', 'color' => 'text-[#e96c4c]'],
                    ['name' => 'Hip Hop',     'icon' => 'mic-outline',            'color' => 'text-[#53a1b3]'],
                    ['name' => 'Gospel',      'icon' => 'cloud-upload-outline',   'color' => 'text-emerald-500'],
                    ['name' => 'Amapiano',    'icon' => 'musical-notes-outline',  'color' => 'text-yellow-500'],
                    ['name' => 'Afrobeat',    'icon' => 'musical-notes-outline',  'color' => 'text-purple-500'],
                    ['name' => 'Taarab',      'icon' => 'musical-notes-outline',  'color' => 'text-cyan-500'],
                    ['name' => 'Singeli',     'icon' => 'cloud-upload-outline',   'color' => 'text-orange-500'],
                    ['name' => 'Zouk',        'icon' => 'people-outline',         'color' => 'text-pink-500'],
                ];
            @endphp
            @foreach($genres as $genre)
            <a href="{{ route('home') }}?search={{ $genre['name'] }}"
                class="flex-shrink-0 w-[100px] h-[100px] border border-[#53a1b3]/20 hover:border-[#e96c4c]/50 flex flex-col items-center justify-center gap-2 group transition overflow-hidden bg-transparent">
                <ion-icon name="{{ $genre['icon'] }}" class="{{ $genre['color'] }} w-8 h-8 group-hover:scale-110 transition"></ion-icon>
                <span class="text-white font-normal text-[10px] uppercase tracking-wider group-hover:text-[#e96c4c] transition">{{ $genre['name'] }}</span>
            </a>
            @endforeach
        </div>

        {{-- Trending Songs --}}


        {{-- Top Downloads --}}
        <div class="mt-2">
            <x-section-header title="Top Downloads This Week" />
            <div class="flex flex-col">
                @foreach($songs->sortByDesc('downloads')->take(5) as $song)
                @php
                    $artistProfile = $song->artistProfile;
                    $countrySlug = $artistProfile?->country->slug ?? 'global';
                    $artistSlug = $artistProfile?->slug ?? \Illuminate\Support\Str::slug($song->artist);
                    $songUrl = route('songs.show', ['country' => $countrySlug, 'artist' => $artistSlug, 'song' => $song->slug]);
                @endphp
                <a href="{{ $songUrl }}" class="flex items-center gap-3 py-1 group transition">
                    <div class="w-12 h-12 shrink-0 ml-1 overflow-hidden">
                        <x-cover-art :path="$song->cover_path" :alt="$song->title" size="w-12 h-12" fallback-icon="musical-notes-outline" icon-size="w-5 h-5" bg="bg-[#213042]" />
                    </div>
                    <div class="flex-1 min-w-0 py-1">
                        <h3 class="font-normal text-white text-sm mb-0.5 truncate group-hover:text-[#e96c4c] transition">{{ $song->title }}</h3>
                        <div class="flex items-center gap-2 mt-0.5">
                            <p class="text-[11px] text-[#53a1b3] truncate max-w-[100px]">{{ $song->artist }}</p>
                            <x-stat-badge icon="download-outline" :value="$song->downloads" />
                        </div>
                    </div>
                    @php
                        $i = $loop->index;
                        $badgeStyle = match($i) {
                            0 => 'background-color:rgba(234,179,8,0.1);border-color:rgba(234,179,8,0.2);color:#eab308;',
                            1 => 'background-color:rgba(209,213,219,0.1);border-color:rgba(209,213,219,0.2);color:#d1d5db;',
                            2 => 'background-color:rgba(249,115,22,0.1);border-color:rgba(249,115,22,0.2);color:#f97316;',
                            3 => 'background-color:rgba(59,130,246,0.1);border-color:rgba(59,130,246,0.2);color:#3b82f6;',
                            4 => 'background-color:rgba(168,85,247,0.1);border-color:rgba(168,85,247,0.2);color:#a855f7;',
                            default => 'background-color:rgba(16,185,129,0.1);border-color:rgba(16,185,129,0.2);color:#10b981;'
                        };
                    @endphp
                    <div class="w-10 h-10 flex items-center justify-center shrink-0 border font-normal text-lg mr-1" style="{{ $badgeStyle }}">
                        {{ $loop->iteration }}
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Track List --}}
        <div class="mt-2">
            <x-section-header title="Track List" :view-all="route('songs.index')" />
            <div class="flex flex-col">
                @foreach($songs->sortByDesc('downloads')->take(5) as $song)
                @php
                    $artistProfile = $song->artistProfile;
                    $countrySlug = $artistProfile?->country->slug ?? 'global';
                    $artistSlug = $artistProfile?->slug ?? \Illuminate\Support\Str::slug($song->artist);
                    $songUrl = route('songs.show', ['country' => $countrySlug, 'artist' => $artistSlug, 'song' => $song->slug]);
                @endphp
                <a href="{{ $songUrl }}" class="flex items-center gap-3 py-1 group transition">
                    <div class="w-12 h-12 shrink-0 ml-1 overflow-hidden">
                        <x-cover-art :path="$song->cover_path" :alt="$song->title" size="w-12 h-12" fallback-icon="musical-notes-outline" icon-size="w-5 h-5" bg="bg-[#213042]" />
                    </div>
                    <div class="flex-1 min-w-0 py-1">
                        <h3 class="font-normal text-white text-sm mb-0.5 truncate group-hover:text-[#e96c4c] transition">{{ $song->title }}</h3>
                        <div class="flex items-center gap-2 mt-0.5">
                            <p class="text-[11px] text-[#53a1b3] truncate max-w-[100px]">{{ $song->artist }}</p>
                            <x-stat-badge icon="download-outline" :value="$song->downloads" />
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>

</div>

<script>
function sideScroll(id, direction) {
    const container = document.getElementById(id);
    container.scrollLeft += direction === 'left' ? -200 : 200;
}
</script>
@endsection
