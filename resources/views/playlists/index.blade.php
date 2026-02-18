@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col overflow-y-auto pb-24 gap-1">

    <div class="mb-4 flex items-center justify-between">
        <h3 class="font-normal text-[#53a1b3] text-xl uppercase tracking-wider">{{ Auth::check() ? 'My Playlists' : 'Public Playlists' }}</h3>

        @auth
        <a href="{{ route('playlists.create') }}" class="bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white px-4 py-2 text-xs font-normal uppercase tracking-wider transition flex items-center gap-2">
            <ion-icon name="add-outline" class="w-4 h-4"></ion-icon>
            <span>Create Playlist</span>
        </a>
        @endauth
    </div>

    @if($playlists->isEmpty())
        <x-empty-state
            icon="list-outline"
            title="No Playlists Yet"
            :message="auth()->check() ? 'Create your first playlist and start organizing your favorite music!' : 'No public playlists available right now.'"
            :action-label="auth()->check() ? 'Create Playlist' : null"
            :action-url="auth()->check() ? route('playlists.create') : null"
        />
    @else
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            @foreach($playlists as $playlist)
            <a href="{{ route('playlists.show', $playlist->slug) }}" class="group">
                @if($playlist->cover_path)
                    <div class="w-full aspect-square bg-[#213042] mb-2 relative overflow-hidden">
                        <img src="{{ Storage::url($playlist->cover_path) }}" alt="{{ $playlist->name }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <ion-icon name="play" class="w-10 h-10 text-white"></ion-icon>
                        </div>
                    </div>
                @else
                    <div class="w-full aspect-square bg-[#213042] mb-2 flex items-center justify-center group-hover:bg-[#2a3d4f] transition">
                        <ion-icon name="list-outline" class="w-12 h-12 text-[#53a1b3]"></ion-icon>
                    </div>
                @endif

                <h4 class="text-white font-normal text-sm truncate group-hover:text-[#e96c4c] transition">{{ $playlist->name }}</h4>
                <div class="flex items-center gap-2 mt-1">
                    <p class="text-[#53a1b3] text-xs truncate">{{ $playlist->user->name }}</p>
                    @if(!$playlist->is_public)
                        <ion-icon name="lock-closed" class="w-3 h-3 text-[#e96c4c]"></ion-icon>
                    @endif
                </div>
                <p class="text-[#53a1b3] text-xs">{{ $playlist->songs()->count() }} songs</p>
            </a>
            @endforeach
        </div>
    @endif

</div>
@endsection
