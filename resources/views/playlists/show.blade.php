@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col overflow-y-auto pb-24 gap-1">

    <!-- Playlist Header -->
    <div class="mb-4">
        <div class="flex items-start gap-4 mb-4">
            <!-- Cover -->
            @if($playlist->cover_path)
                <div class="w-32 h-32 bg-[#213042] flex-shrink-0 overflow-hidden">
                    <img src="{{ Storage::url($playlist->cover_path) }}" alt="{{ $playlist->name }}" class="w-full h-full object-cover">
                </div>
            @else
                <div class="w-32 h-32 bg-[#213042] flex-shrink-0 flex items-center justify-center">
                    <ion-icon name="list-outline" class="w-16 h-16 text-[#53a1b3]"></ion-icon>
                </div>
            @endif

            <!-- Info -->
            <div class="flex-1 min-w-0">
                <p class="text-[#53a1b3] text-xs uppercase tracking-wider mb-1">Playlist</p>
                <h1 class="text-white text-2xl font-normal mb-2">{{ $playlist->name }}</h1>

                @if($playlist->description)
                    <p class="text-[#53a1b3] text-sm mb-3">{{ $playlist->description }}</p>
                @endif

                <div class="flex items-center gap-3 text-xs text-[#53a1b3] mb-3">
                    <span>{{ $playlist->user->name }}</span>
                    <span>•</span>
                    <span>{{ $playlist->songs()->count() }} songs</span>
                    @if(!$playlist->is_public)
                        <span>•</span>
                        <div class="flex items-center gap-1">
                            <ion-icon name="lock-closed" class="w-3 h-3"></ion-icon>
                            <span>Private</span>
                        </div>
                    @endif
                </div>

                <!-- Actions (only for owner) -->
                @auth
                @if(Auth::id() === $playlist->user_id)
                    <div class="flex items-center gap-2">
                        <a href="{{ route('playlists.edit', $playlist->slug) }}" class="bg-[#141e24] hover:bg-[#1a2834] text-[#53a1b3] hover:text-white px-4 py-2 text-xs font-normal uppercase tracking-wider transition flex items-center gap-2">
                            <ion-icon name="create-outline" class="w-4 h-4"></ion-icon>
                            <span>Edit</span>
                        </a>
                        <form action="{{ route('playlists.destroy', $playlist->slug) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this playlist?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-[#141e24] hover:bg-[#e96c4c] text-[#53a1b3] hover:text-white px-4 py-2 text-xs font-normal uppercase tracking-wider transition flex items-center gap-2">
                                <ion-icon name="trash-outline" class="w-4 h-4"></ion-icon>
                                <span>Delete</span>
                            </button>
                        </form>
                    </div>
                @endif
                @endauth
            </div>
        </div>

        <a href="{{ route('playlists.index') }}" class="text-[#53a1b3] hover:text-white text-xs font-normal uppercase tracking-wider transition flex items-center gap-2">
            <ion-icon name="arrow-back-outline" class="w-4 h-4"></ion-icon>
            <span>Back to Playlists</span>
        </a>
    </div>

    <!-- Songs List -->
    <div class="bg-[#213042] p-4">
        @if($playlist->songs->isEmpty())
            <div class="py-8 flex flex-col items-center justify-center gap-3">
                <div class="w-16 h-16 bg-[#141e24] flex items-center justify-center">
                    <ion-icon name="musical-notes-outline" class="w-10 h-10 text-[#e96c4c]"></ion-icon>
                </div>
                <h4 class="text-white font-normal text-base">No Songs Yet</h4>
                <p class="text-[#53a1b3] text-sm text-center max-w-xs">
                    @auth
                    @if(Auth::id() === $playlist->user_id)
                        Start adding songs to this playlist from the song pages.
                    @else
                        This playlist doesn't have any songs yet.
                    @endif
                    @else
                        This playlist doesn't have any songs yet.
                    @endauth
                </p>
            </div>
        @else
            <div class="space-y-1">
                @foreach($playlist->songs()->orderBy('position')->get() as $index => $song)
                <div class="flex items-center gap-3 p-3 hover:bg-[#141e24] transition group">
                    <!-- Position -->
                    <div class="w-6 text-center text-[#53a1b3] text-sm">
                        {{ $index + 1 }}
                    </div>

                    <!-- Cover -->
                    <div class="w-12 h-12 bg-[#141e24] flex-shrink-0 overflow-hidden">
                        @if($song->cover_path)
                            <img src="{{ Storage::url($song->cover_path) }}" alt="{{ $song->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <ion-icon name="musical-notes-outline" class="w-6 h-6 text-[#53a1b3]"></ion-icon>
                            </div>
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('songs.show', $song->slug) }}" class="text-white hover:text-[#e96c4c] text-sm font-normal truncate block transition">
                            {{ $song->title }}
                        </a>
                        <p class="text-[#53a1b3] text-xs truncate">{{ $song->artist }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2">
                        <!-- Play Button -->
                        <a href="{{ route('songs.show', $song->slug) }}" class="w-8 h-8 bg-[#e96c4c] hover:bg-[#e96c4c]/90 flex items-center justify-center transition">
                            <ion-icon name="play" class="w-4 h-4 text-white"></ion-icon>
                        </a>

                        <!-- Remove from Playlist (owner only) -->
                        @auth
                        @if(Auth::id() === $playlist->user_id)
                            <form action="{{ route('playlists.removeSong', [$playlist->slug, $song->id]) }}" method="POST" onsubmit="return confirm('Remove this song from the playlist?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 bg-[#141e24] hover:bg-[#e96c4c] flex items-center justify-center transition opacity-0 group-hover:opacity-100">
                                    <ion-icon name="remove-outline" class="w-4 h-4 text-white"></ion-icon>
                                </button>
                            </form>
                        @endif
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
