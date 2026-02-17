@extends('Admin.layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="flex flex-col space-y-4">

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2">
        <!-- Total Songs -->
        <div class="p-4 border border-[#53a1b3]/10">
            <div class="flex items-center justify-between mb-2">
                <ion-icon name="musical-notes-outline" class="w-6 h-6 text-[#e96c4c]"></ion-icon>
                <span class="text-[10px] text-[#53a1b3] uppercase tracking-wider">Songs</span>
            </div>
            <h3 class="text-2xl font-normal text-white">{{ number_format($stats['total_songs']) }}</h3>
        </div>

        <!-- Total Albums -->
        <div class="p-4 border border-[#53a1b3]/10">
            <div class="flex items-center justify-between mb-2">
                <ion-icon name="albums-outline" class="w-6 h-6 text-[#53a1b3]"></ion-icon>
                <span class="text-[10px] text-[#53a1b3] uppercase tracking-wider">Albums</span>
            </div>
            <h3 class="text-2xl font-normal text-white">{{ number_format($stats['total_albums']) }}</h3>
        </div>

        <!-- Total Artists -->
        <div class="p-4 border border-[#53a1b3]/10">
            <div class="flex items-center justify-between mb-2">
                <ion-icon name="mic-outline" class="w-6 h-6 text-purple-500"></ion-icon>
                <span class="text-[10px] text-[#53a1b3] uppercase tracking-wider">Artists</span>
            </div>
            <h3 class="text-2xl font-normal text-white">{{ number_format($stats['total_artists']) }}</h3>
        </div>

        <!-- Total Users -->
        <div class="p-4 border border-[#53a1b3]/10">
            <div class="flex items-center justify-between mb-2">
                <ion-icon name="people-outline" class="w-6 h-6 text-emerald-500"></ion-icon>
                <span class="text-[10px] text-[#53a1b3] uppercase tracking-wider">Users</span>
            </div>
            <h3 class="text-2xl font-normal text-white">{{ number_format($stats['total_users']) }}</h3>
        </div>

        <!-- Total Playlists -->
        <div class="p-4 border border-[#53a1b3]/10">
            <div class="flex items-center justify-between mb-2">
                <ion-icon name="list-outline" class="w-6 h-6 text-yellow-500"></ion-icon>
                <span class="text-[10px] text-[#53a1b3] uppercase tracking-wider">Playlists</span>
            </div>
            <h3 class="text-2xl font-normal text-white">{{ number_format($stats['total_playlists']) }}</h3>
        </div>

        <!-- Total Comments -->
        <div class="p-4 border border-[#53a1b3]/10">
            <div class="flex items-center justify-between mb-2">
                <ion-icon name="chatbubbles-outline" class="w-6 h-6 text-cyan-500"></ion-icon>
                <span class="text-[10px] text-[#53a1b3] uppercase tracking-wider">Comments</span>
            </div>
            <h3 class="text-2xl font-normal text-white">{{ number_format($stats['total_comments']) }}</h3>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid md:grid-cols-2 gap-2">

        <!-- Recent Songs -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-[#53a1b3] font-normal text-xs uppercase tracking-wider">Recent Songs</h3>
                <a href="{{ route('admin.songs.index') }}" class="text-[10px] text-[#e96c4c] uppercase tracking-wider hover:text-white transition">View All</a>
            </div>
            <div class="space-y-2">
                @forelse($recentSongs as $song)
                <div class="flex items-center gap-3">
                    @if($song->cover_path)
                        <img src="{{ Storage::url($song->cover_path) }}" alt="{{ $song->title }}" class="w-12 h-12 object-cover">
                    @else
                        <div class="w-12 h-12 bg-[#0f1319] flex items-center justify-center">
                            <ion-icon name="musical-notes-outline" class="w-5 h-5 text-[#53a1b3]"></ion-icon>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h4 class="text-white text-sm font-normal truncate">{{ $song->title }}</h4>
                        <p class="text-[#53a1b3] text-xs truncate">{{ $song->artist }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[10px] text-[#e96c4c] bg-[#e96c4c]/10 px-2 py-0.5">{{ $song->likes_count }} likes</span>
                            <span class="text-[10px] text-[#53a1b3] bg-[#53a1b3]/10 px-2 py-0.5">{{ $song->comments_count }} comments</span>
                            <span class="text-[10px] text-purple-400 bg-purple-400/10 px-2 py-0.5">{{ $song->downloads }} downloads</span>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-[#53a1b3] text-sm text-center py-4">No songs yet</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Users -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-[#53a1b3] font-normal text-xs uppercase tracking-wider">Recent Users</h3>
                <a href="{{ route('admin.users.index') }}" class="text-[10px] text-[#e96c4c] uppercase tracking-wider hover:text-white transition">View All</a>
            </div>
            <div class="space-y-2">
                @forelse($recentUsers as $user)
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#e96c4c] flex items-center justify-center text-white font-normal">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-white text-sm font-normal truncate">{{ $user->name }}</h4>
                        <p class="text-[#53a1b3] text-xs truncate">{{ $user->email }}</p>
                    </div>
                    <span class="text-[10px] text-[#53a1b3]">{{ $user->created_at->diffForHumans() }}</span>
                </div>
                @empty
                <p class="text-[#53a1b3] text-sm text-center py-4">No users yet</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Another Row -->
    <div class="grid md:grid-cols-2 gap-2">

        <!-- Top Songs by Likes -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-[#53a1b3] font-normal text-xs uppercase tracking-wider">Top Songs</h3>
            </div>
            <div class="space-y-2">
                @forelse($topSongs as $index => $song)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center text-[#e96c4c] font-normal text-sm">
                        #{{ $index + 1 }}
                    </div>
                    @if($song->cover_path)
                        <img src="{{ Storage::url($song->cover_path) }}" alt="{{ $song->title }}" class="w-10 h-10 object-cover">
                    @else
                        <div class="w-10 h-10 bg-[#0f1319] flex items-center justify-center">
                            <ion-icon name="musical-notes-outline" class="w-4 h-4 text-[#53a1b3]"></ion-icon>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h4 class="text-white text-sm font-normal truncate">{{ $song->title }}</h4>
                        <p class="text-[#53a1b3] text-xs">{{ $song->likes_count }} likes</p>
                    </div>
                </div>
                @empty
                <p class="text-[#53a1b3] text-sm text-center py-4">No songs yet</p>
                @endforelse
            </div>
        </div>

        <!-- System Stats -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-[#53a1b3] font-normal text-xs uppercase tracking-wider">System Stats</h3>
            </div>
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[#53a1b3] text-sm">Total Downloads</span>
                    <span class="text-white font-normal">{{ number_format($systemStats['total_downloads']) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[#53a1b3] text-sm">Total Engagement</span>
                    <span class="text-white font-normal">{{ number_format($systemStats['total_engagement']) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[#53a1b3] text-sm">Total Likes</span>
                    <span class="text-[#e96c4c] font-normal">{{ number_format($systemStats['total_likes']) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[#53a1b3] text-sm">Avg Downloads/Song</span>
                    <span class="text-white font-normal">{{ $systemStats['avg_downloads_per_song'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[#53a1b3] text-sm">Total Content</span>
                    <span class="text-purple-400 font-normal">{{ number_format($systemStats['total_content']) }} items</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Top Playlists -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-[#53a1b3] font-normal text-xs uppercase tracking-wider">Top Public Playlists</h3>
            <a href="{{ route('admin.playlists.index') }}" class="text-[10px] text-[#e96c4c] uppercase tracking-wider hover:text-white transition">View All</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
            @forelse($topPlaylists as $playlist)
            <div>
                @if($playlist->cover_path)
                    <div class="w-full aspect-square bg-[#0f1319] mb-2 relative overflow-hidden">
                        <img src="{{ Storage::url($playlist->cover_path) }}" alt="{{ $playlist->name }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="w-full aspect-square bg-[#0f1319] mb-2 flex items-center justify-center">
                        <ion-icon name="list-outline" class="w-10 h-10 text-[#53a1b3]"></ion-icon>
                    </div>
                @endif
                <h4 class="text-white text-sm font-normal truncate">{{ $playlist->name }}</h4>
                <p class="text-[#53a1b3] text-xs">{{ $playlist->songs_count }} songs</p>
            </div>
            @empty
            <p class="text-[#53a1b3] text-sm text-center py-4 col-span-full">No playlists yet</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
