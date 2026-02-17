@extends('layouts.app')

@section('content')
<div class="flex flex-col gap-2">


    <!-- Latest Releases (Horizontal Scroll) -->
    <div>
        <div class="flex items-center justify-between mb-3 px-1">
            <h2 class="text-[#53a1b3] font-normal text-xs uppercase tracking-wider">Latest Releases</h2>
            <div class="flex items-center gap-1">
                <button onclick="sideScroll('latest-releases-scroll', 'left')" class="w-6 h-6 border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3] hover:text-white hover:border-[#e96c4c] transition">
                    <ion-icon name="chevron-back-outline" class="w-3 h-3"></ion-icon>
                </button>
                <button onclick="sideScroll('latest-releases-scroll', 'right')" class="w-6 h-6 border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3] hover:text-white hover:border-[#e96c4c] transition">
                    <ion-icon name="chevron-forward-outline" class="w-3 h-3"></ion-icon>
                </button>
            </div>
        </div>
        <div id="latest-releases-scroll" class="flex overflow-x-auto gap-2 pb-2 scrollbar-hide scroll-smooth">
            @foreach($latestReleases as $song)
            <div class="flex-shrink-0 w-[150px] group">
                 <a href="{{ route('songs.show', $song) }}" class="block relative mb-2">
                    @if($song->cover_path)
                        <img src="{{ Storage::url($song->cover_path) }}" alt="{{ $song->title }}" class="w-[150px] h-[150px] object-cover rounded-none border border-[#53a1b3]/20 group-hover:border-[#e96c4c]/50 transition">
                    @else
                        <div class="w-full h-[150px] bg-[#141e24] border border-[#53a1b3]/20 rounded-none flex items-center justify-center text-[#53a1b3] group-hover:border-[#e96c4c]/50 transition">
                            <ion-icon name="musical-notes-outline" class="w-12 h-12"></ion-icon>
                        </div>
                    @endif
                    <div class="absolute bottom-1 right-1 bg-black/60 px-1.5 rounded-none text-[10px] font-normal text-white">
                        NEW
                    </div>
                </a>
                <h3 class="font-normal text-white text-sm leading-tight truncate px-0.5">{{ $song->title }}</h3>
                <p class="text-[10px] text-[#53a1b3] truncate px-0.5">{{ $song->artist }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Featured Albums -->
    <div class="mt-2">
        <div class="flex items-center justify-between mb-3 px-1">
            <h2 class="text-[#53a1b3] font-normal text-xs uppercase tracking-wider">Featured Albums</h2>
            <div class="flex items-center gap-1">
                <button onclick="sideScroll('featured-albums-scroll', 'left')" class="w-6 h-6 border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3] hover:text-white hover:border-[#e96c4c] transition">
                    <ion-icon name="chevron-back-outline" class="w-3 h-3"></ion-icon>
                </button>
                <button onclick="sideScroll('featured-albums-scroll', 'right')" class="w-6 h-6 border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3] hover:text-white hover:border-[#e96c4c] transition">
                    <ion-icon name="chevron-forward-outline" class="w-3 h-3"></ion-icon>
                </button>
            </div>
        </div>
        <div id="featured-albums-scroll" class="flex overflow-x-auto gap-2 pb-2 scrollbar-hide px-1 scroll-smooth">
            @foreach($albums as $album)
            <a href="{{ route('albums.show', $album) }}" class="flex-shrink-0 w-[120px] group block">
                <div class="w-[120px] h-[120px] bg-[#0f1319] border border-[#53a1b3]/20 mb-2 relative overflow-hidden">
                    @if($album->cover_path)
                        <img src="{{ Storage::url($album->cover_path) }}" alt="{{ $album->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-[#53a1b3]/20 group-hover:text-[#e96c4c]/50 transition">
                            <ion-icon name="musical-notes-outline" class="w-12 h-12"></ion-icon>
                        </div>
                    @endif
                </div>
                <h3 class="font-normal text-white text-sm leading-tight truncate px-0.5 group-hover:text-[#e96c4c] transition">{{ $album->title }}</h3>
                <p class="text-[10px] text-[#53a1b3] truncate px-0.5">{{ $album->artistProfile->name ?? 'Unknown' }}</p>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Top Playlists -->
    <div class="mt-2">
        <div class="flex items-center justify-between mb-3 px-1">
            <h2 class="text-[#53a1b3] font-normal text-xs uppercase tracking-wider">Top Playlists</h2>
            <div class="flex items-center gap-1">
                <button onclick="sideScroll('top-playlists-scroll', 'left')" class="w-6 h-6 border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3] hover:text-white hover:border-[#e96c4c] transition">
                    <ion-icon name="chevron-back-outline" class="w-3 h-3"></ion-icon>
                </button>
                <button onclick="sideScroll('top-playlists-scroll', 'right')" class="w-6 h-6 border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3] hover:text-white hover:border-[#e96c4c] transition">
                    <ion-icon name="chevron-forward-outline" class="w-3 h-3"></ion-icon>
                </button>
            </div>
        </div>
        <div id="top-playlists-scroll" class="flex overflow-x-auto gap-2 pb-2 scrollbar-hide px-1 scroll-smooth">
            @foreach($topPlaylists as $playlist)
            <a href="{{ route('playlists.show', $playlist->slug) }}" class="flex-shrink-0 w-[120px] md:w-[180px] group block">
                @if($playlist->cover_path)
                    <div class="w-[120px] h-[120px] md:w-[180px] md:h-[180px] bg-[#0f1319] border border-[#53a1b3]/20 mb-2 relative overflow-hidden">
                        <img src="{{ Storage::url($playlist->cover_path) }}" alt="{{ $playlist->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <ion-icon name="play" class="w-8 h-8 md:w-12 md:h-12 text-white"></ion-icon>
                        </div>
                    </div>
                @else
                    <div class="w-[120px] h-[120px] md:w-[180px] md:h-[180px] bg-[#0f1319] border border-[#53a1b3]/20 mb-2 flex items-center justify-center group-hover:bg-[#1a2730] transition">
                        <ion-icon name="list-outline" class="w-10 h-10 md:w-14 md:h-14 text-[#53a1b3]"></ion-icon>
                    </div>
                @endif
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

    <!-- Popular Artists -->
    <div class="mt-2">
        <div class="flex items-center justify-between mb-3 px-1">
            <h2 class="text-[#53a1b3] font-normal text-xs uppercase tracking-wider">Popular Artists</h2>
            <a href="#" class="text-[10px] font-normal text-[#e96c4c] uppercase tracking-wider hover:text-white transition">View All</a>
        </div>
        <div class="grid grid-cols-4 md:grid-cols-6 gap-2 px-1">
            @foreach($artists as $artist)
            <a href="{{ route('artists.show', $artist) }}" class="group block text-center">
                <div class="aspect-square bg-[#0f1319] border border-[#53a1b3]/20 mb-2 relative overflow-hidden w-full">
                    @if($artist->image_path)
                        <img src="{{ Storage::url($artist->image_path) }}" alt="{{ $artist->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-[#53a1b3]/20 group-hover:text-[#e96c4c]/50 transition">
                            <ion-icon name="mic-outline" class="w-12 h-12"></ion-icon>
                        </div>
                    @endif
                </div>
                <h3 class="font-normal text-white text-xs leading-tight truncate px-0.5 group-hover:text-[#e96c4c] transition">{{ $artist->name }}</h3>
            </a>
            @endforeach
        </div>
    </div>

    <!-- browse by categories -->
    <div>
       <div class="flex items-center justify-between mb-3 px-1">
           <h2 class="text-[#53a1b3] font-normal text-xs uppercase tracking-wider">Browse Genre</h2>
           <div class="flex items-center gap-1">
                <button onclick="sideScroll('browse-genre-scroll', 'left')" class="w-6 h-6 border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3] hover:text-white hover:border-[#e96c4c] transition">
                    <ion-icon name="chevron-back-outline" class="w-3 h-3"></ion-icon>
                </button>
                <button onclick="sideScroll('browse-genre-scroll', 'right')" class="w-6 h-6 border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3] hover:text-white hover:border-[#e96c4c] transition">
                    <ion-icon name="chevron-forward-outline" class="w-3 h-3"></ion-icon>
                </button>
            </div>
       </div>
       <div id="browse-genre-scroll" class="flex overflow-x-auto gap-2 pb-2 scrollbar-hide scroll-smooth">
            @php
                $genres = [
                    ['name' => 'Bongo Flava', 'icon' => 'musical-notes-outline', 'color' => 'text-[#e96c4c]'],
                    ['name' => 'Hip Hop', 'icon' => 'mic-outline', 'color' => 'text-[#53a1b3]'],
                    ['name' => 'Gospel', 'icon' => 'cloud-upload-outline', 'color' => 'text-emerald-500'],
                    ['name' => 'Amapiano', 'icon' => 'musical-notes-outline', 'color' => 'text-yellow-500'],
                    ['name' => 'Afrobeat', 'icon' => 'musical-notes-outline', 'color' => 'text-purple-500'],
                    ['name' => 'Taarab', 'icon' => 'musical-notes-outline', 'color' => 'text-cyan-500'],
                    ['name' => 'Singeli', 'icon' => 'cloud-upload-outline', 'color' => 'text-orange-500'],
                    ['name' => 'Zouk', 'icon' => 'people-outline', 'color' => 'text-pink-500'],
                ];
            @endphp

            @foreach($genres as $genre)
            <a href="{{ route('home') }}?search={{ $genre['name'] }}" class="flex-shrink-0 w-[100px] h-[100px] border border-[#53a1b3]/20 hover:border-[#e96c4c]/50 flex flex-col items-center justify-center gap-2 group transition rounded-none relative overflow-hidden bg-transparent">
                <ion-icon name="{{ $genre['icon'] }}" class="{{ $genre['color'] }} w-8 h-8 group-hover:scale-110 transition"></ion-icon>
                <span class="text-white font-normal text-[10px] uppercase tracking-wider group-hover:text-[#e96c4c] transition">{{ $genre['name'] }}</span>
            </a>
            @endforeach
       </div>

       <!-- trending songs -->
       <div class="mt-2">
            <div class="flex items-center justify-between mb-3 px-1">
                <h2 class="text-[#53a1b3] font-normal text-xs uppercase tracking-wider">Trending Songs</h2>
                <div class="flex items-center gap-1">
                    <button onclick="sideScroll('trending-songs-scroll', 'left')" class="w-6 h-6 border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3] hover:text-white hover:border-[#e96c4c] transition">
                        <ion-icon name="chevron-back-outline" class="w-3 h-3"></ion-icon>
                    </button>
                    <button onclick="sideScroll('trending-songs-scroll', 'right')" class="w-6 h-6 border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3] hover:text-white hover:border-[#e96c4c] transition">
                        <ion-icon name="chevron-forward-outline" class="w-3 h-3"></ion-icon>
                    </button>
                </div>
            </div>
            <div id="trending-songs-scroll" class="flex overflow-x-auto gap-2 pb-2 scrollbar-hide scroll-smooth">
                @foreach($songs->sortByDesc('downloads')->take(5) as $song)
                <div class="flex-shrink-0 w-[120px] group">
                    <a href="{{ route('songs.show', $song) }}" class="block relative mb-2">
                        @if($song->cover_path)
                            <img src="{{ Storage::url($song->cover_path) }}" alt="{{ $song->title }}" class="w-[120px] h-[120px] object-cover rounded-none border border-[#53a1b3]/20 group-hover:border-[#e96c4c]/50 transition">
                        @else
                            <div class="w-[120px] h-[120px] bg-[#141e24] border border-[#53a1b3]/20 rounded-none flex items-center justify-center text-[#53a1b3] group-hover:border-[#e96c4c]/50 transition">
                                <ion-icon name="cloud-upload-outline" class="w-10 h-10 text-orange-500"></ion-icon>
                            </div>
                        @endif
                        <div class="absolute top-1 left-1 bg-[#e96c4c] px-1.5 rounded-none text-[8px] font-normal text-white">
                            #{{ $loop->iteration }}
                        </div>
                    </a>
                    <h3 class="font-normal text-white text-xs leading-tight truncate px-0.5">{{ $song->title }}</h3>
                    <p class="text-[9px] text-[#53a1b3] truncate px-0.5">{{ $song->artist }}</p>
                </div>
                @endforeach
            </div>
       </div>

       <!-- Top Downloads List -->
       <div class="mt-2">
           <h2 class="text-[#53a1b3] font-normal text-xs uppercase tracking-wider mb-3 px-1">Top Downloads This Week</h2>
            <div class="flex flex-col">
               @foreach($songs->sortByDesc('downloads')->take(5) as $index => $song)
               <a href="{{ route('songs.show', $song) }}" class="flex items-center gap-3 py-1 group transition">

                   @if($song->cover_path)
                       <img src="{{ Storage::url($song->cover_path) }}" class="w-12 h-12 object-cover shrink-0 ml-1">
                   @else
                       <div class="w-12 h-12 bg-[#213042] flex items-center justify-center shrink-0 ml-1">
                           <ion-icon name="musical-notes-outline" class="w-5 h-5 text-[#53a1b3]"></ion-icon>
                       </div>
                   @endif

                   <!-- Info -->
                   <div class="flex-1 min-w-0 py-1">
                       <h3 class="font-normal text-white text-sm mb-0.5 truncate group-hover:text-[#e96c4c] transition">{{ $song->title }}</h3>
                       <div class="flex items-center gap-2 mt-0.5">
                           <p class="text-[11px] text-[#53a1b3] truncate max-w-[100px]">{{ $song->artist }}</p>

                           <!-- GitHub Style Badge -->
                           <div class="flex items-center h-4 border border-[#53a1b3]/20 overflow-hidden">
                                <div class="bg-[#141e24] px-1.5 h-full flex items-center justify-center border-r border-[#53a1b3]/10">
                                     <ion-icon name="download-outline" class="w-2 h-2 text-[#53a1b3]"></ion-icon>
                                </div>
                                <div class="bg-[#53a1b3]/10 px-1.5 h-full flex items-center justify-center text-[9px] font-normal text-[#53a1b3] font-mono">
                                    {{ number_format($song->downloads) }}
                                </div>
                           </div>
                       </div>
                   </div>

                   <!-- Big Square Counter Badge -->
                   @php
                       $i = $loop->index;
                       $badgeStyle = match($i) {
                           0 => 'background-color: rgba(234, 179, 8, 0.1); border-color: rgba(234, 179, 8, 0.2); color: #eab308;', // Gold (#1)
                           1 => 'background-color: rgba(209, 213, 219, 0.1); border-color: rgba(209, 213, 219, 0.2); color: #d1d5db;', // Silver (#2)
                           2 => 'background-color: rgba(249, 115, 22, 0.1); border-color: rgba(249, 115, 22, 0.2); color: #f97316;', // Bronze (#3)
                           3 => 'background-color: rgba(59, 130, 246, 0.1); border-color: rgba(59, 130, 246, 0.2); color: #3b82f6;', // Blue (#4)
                           4 => 'background-color: rgba(168, 85, 247, 0.1); border-color: rgba(168, 85, 247, 0.2); color: #a855f7;', // Purple (#5)
                           default => 'background-color: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); color: #10b981;'
                       };
                   @endphp
                   <div class="w-10 h-10 flex items-center justify-center shrink-0 border font-normal text-lg mr-1" style="{{ $badgeStyle }}">
                       {{ $loop->iteration }}
                   </div>
               </a>
               @endforeach
           </div>
       </div>

       <!-- Track List -->
       <div class="mt-2">
           <div class="flex items-center justify-between mb-3 px-1">
               <h2 class="text-[#53a1b3] font-normal text-xs uppercase tracking-wider">Track List</h2>
               <a href="{{ route('songs.index') }}" class="text-[10px] font-normal text-[#e96c4c] uppercase tracking-wider hover:text-white transition">View All</a>
           </div>
            <div class="flex flex-col">
               @foreach($songs->sortByDesc('downloads')->take(5) as $index => $song)
               <a href="{{ route('songs.show', $song) }}" class="flex items-center gap-3 py-1 group transition">

                   @if($song->cover_path)
                       <img src="{{ Storage::url($song->cover_path) }}" class="w-12 h-12 object-cover shrink-0 ml-1">
                   @else
                       <div class="w-12 h-12 bg-[#213042] flex items-center justify-center shrink-0 ml-1">
                           <ion-icon name="musical-notes-outline" class="w-5 h-5 text-[#53a1b3]"></ion-icon>
                       </div>
                   @endif

                   <!-- Info -->
                   <div class="flex-1 min-w-0 py-1">
                       <h3 class="font-normal text-white text-sm mb-0.5 truncate group-hover:text-[#e96c4c] transition">{{ $song->title }}</h3>
                       <div class="flex items-center gap-2 mt-0.5">
                           <p class="text-[11px] text-[#53a1b3] truncate max-w-[100px]">{{ $song->artist }}</p>

                           <!-- GitHub Style Badge -->
                           <div class="flex items-center h-4 border border-[#53a1b3]/20 overflow-hidden">
                                <div class="bg-[#141e24] px-1.5 h-full flex items-center justify-center border-r border-[#53a1b3]/10">
                                     <ion-icon name="download-outline" class="w-2 h-2 text-[#53a1b3]"></ion-icon>
                                </div>
                                <div class="bg-[#53a1b3]/10 px-1.5 h-full flex items-center justify-center text-[9px] font-normal text-[#53a1b3] font-mono">
                                    {{ number_format($song->downloads) }}
                                </div>
                           </div>
                       </div>
                   </div>
               </a>
               @endforeach
           </div>
       </div>
</div>

<script>
function sideScroll(id, direction) {
    const container = document.getElementById(id);
    const scrollAmount = 200;
    if (direction === 'left') {
        container.scrollLeft -= scrollAmount;
    } else {
        container.scrollLeft += scrollAmount;
    }
}
</script>
@endsection
