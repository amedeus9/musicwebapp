@extends('layouts.app')

@section('content')
<div class="flex flex-col gap-6 pb-24">
    
    <!-- Album Header -->
    <div class="flex flex-col md:flex-row gap-6 px-4 pt-4 items-center md:items-end">
        <!-- Cover Art -->
        <div class="w-48 h-48 md:w-56 md:h-56 shrink-0 shadow-2xl shadow-black/50 rounded-none overflow-hidden relative group">
            @if($album->cover_path)
                <img src="{{ Storage::url($album->cover_path) }}" alt="{{ $album->title }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-[#0f1319] flex items-center justify-center">
                    <ion-icon name="musical-notes-outline" class="w-16 h-16 text-[#53a1b3]/20"></ion-icon>
                </div>
            @endif
        </div>

        <!-- Info -->
        <div class="flex flex-col items-center md:items-start text-center md:text-left">
            <h5 class="text-[#e96c4c] text-xs font-normal uppercase tracking-widest mb-1">Album</h5>
            <h1 class="text-3xl md:text-5xl font-normal text-white leading-none mb-2">{{ $album->title }}</h1>
            <div class="flex items-center gap-2 text-[#53a1b3] text-sm font-medium">
                @if($album->artistProfile)
                    <a href="{{ route('artists.show', $album->artistProfile) }}" class="hover:text-white hover:underline">
                        {{ $album->artistProfile->name }}
                    </a>
                @else
                    <span>Unknown Artist</span>
                @endif
                <span>•</span>
                <span>{{ $album->release_date ? \Carbon\Carbon::parse($album->release_date)->format('Y') : 'Unknown Year' }}</span>
                <span>•</span>
                <span>{{ $album->songs->count() }} Songs</span>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="px-4 flex items-center gap-3">
        <button class="w-12 h-12 bg-[#e96c4c] rounded-none flex items-center justify-center text-white shadow-lg shadow-[#e96c4c]/30 hover:scale-105 transition">
            <ion-icon name="play-outline" class="w-6 h-6 ml-1"></ion-icon>
        </button>

        <button onclick="toggleLike('album', {{ $album->id }}, this)" id="like-btn-album" class="w-10 h-10 border border-white/5 flex items-center justify-center transition {{ $album->likes()->where('user_id', auth()->id())->exists() ? 'bg-red-600/20 text-red-500' : 'bg-[#141e24] text-[#53a1b3] hover:bg-red-600/10' }}">
            <ion-icon id="like-icon-album" name="{{ $album->likes()->where('user_id', auth()->id())->exists() ? 'heart' : 'heart-outline' }}" class="w-5 h-5 like-icon"></ion-icon>
        </button>

        <button class="w-10 h-10 border border-[#53a1b3]/30 rounded-none flex items-center justify-center text-[#53a1b3] hover:text-white hover:border-white transition">
            <ion-icon name="ellipsis-horizontal-outline" class="w-5 h-5"></ion-icon>
        </button>
    </div>

    <!-- Tracklist -->
    <div class="px-2">
        <div class="flex flex-col">
            <div class="px-4 py-2 border-b border-[#53a1b3]/10 text-[#53a1b3] text-[10px] font-normal uppercase tracking-widest flex items-center">
                <span class="w-8">#</span>
                <span class="flex-1">Title</span>
                <span class="w-16 text-right"><ion-icon name="download-outline" class="w-3 h-3 inline-block"></ion-icon></span>
                <span class="w-16 text-right"><ion-icon name="download-outline" class="w-3 h-3 inline-block"></ion-icon></span>
            </div>

            @foreach($album->songs as $index => $song)
            <a href="{{ route('songs.show', $song) }}" class="group px-4 py-3 flex items-center hover:bg-white/5 rounded-none transition">
                <span class="w-8 text-[#53a1b3]/50 font-mono text-sm group-hover:text-white">{{ $index + 1 }}</span>
                <div class="flex-1 flex flex-col">
                    <span class="text-white text-sm font-normal group-hover:text-[#e96c4c] transition line-clamp-1">{{ $song->title }}</span>
                    <span class="text-[#53a1b3] text-xs">{{ $song->artist }}</span>
                </div>
                <span class="w-16 text-right text-[#53a1b3]/50 text-xs">-</span>
            </a>
            @endforeach
        </div>
    <!-- Album Comments Section -->
    <div class="mt-8 px-4 pb-10">
        <h3 class="text-[#53a1b3] font-normal text-xs uppercase tracking-widest mb-6 flex items-center justify-between">
            <span>Comments ({{ $album->comments->count() }})</span>
            <div class="flex items-center gap-1 text-[10px] text-[#e96c4c]">
                <ion-icon name="heart" class="w-3 h-3"></ion-icon>
                <span>{{ $album->likes->count() }} Likes</span>
            </div>
        </h3>

        <x-comments
            type="album"
            :model-id="$album->id"
            :comments="$album->comments()->with('user')->latest()->get()"
            list-id="comments-list-album"
            placeholder="Give your thoughts on this album..."
        />
    </div>
</div>
@endsection
