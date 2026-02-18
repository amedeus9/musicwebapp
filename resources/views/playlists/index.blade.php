@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col overflow-y-auto pb-24 gap-1">

    <!-- Header -->
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-2">
            <div class="w-12 h-12 flex items-center justify-center bg-[#e96c4c]/10 rounded-[3px] border border-[#e96c4c]/20 shrink-0">
                <ion-icon name="musical-notes" class="w-6 h-6 text-[#e96c4c]"></ion-icon>
            </div>
            <div>
                <h1 class="text-lg font-normal text-white uppercase tracking-wider">{{ Auth::check() ? 'My Playlists' : 'Public Playlists' }}</h1>
                <p class="text-[10px] text-[#53a1b3]/50 uppercase tracking-widest mt-0.5">Curated collections for every mood</p>
            </div>
        </div>

        @auth
        <a href="{{ route('playlists.create') }}" class="group flex items-center justify-center gap-2 bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white px-4 py-2 rounded-[3px] transition shadow-lg hover:shadow-[#e96c4c]/20">
            <ion-icon name="add" class="w-4 h-4"></ion-icon>
            <span class="text-[10px] font-normal uppercase tracking-widest hidden sm:inline">Create New</span>
        </a>
        @endauth
    </div>

    <!-- Playlist Grid -->
    @if($playlists->isEmpty())
        <x-empty-state
            icon="list-outline"
            title="No Playlists Yet"
            :message="auth()->check() ? 'Create your first playlist and start organizing your favorite music!' : 'No public playlists available right now.'"
            :action-label="auth()->check() ? 'Create Playlist' : null"
            :action-url="auth()->check() ? route('playlists.create') : null"
        />
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-2">
            @foreach($playlists as $playlist)
                <x-playlist-card :playlist="$playlist" />
            @endforeach
        </div>
    @endif

</div>
@endsection
