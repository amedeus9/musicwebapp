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

        <form action="{{ route('interactions.like', ['type' => 'album', 'id' => $album->id]) }}" method="POST">
            @csrf
            <button type="submit" class="w-10 h-10 border border-white/5 flex items-center justify-center {{ $album->likes()->where('user_id', auth()->id())->exists() ? 'bg-red-600/20 text-red-500' : 'bg-[#141e24] text-[#53a1b3]' }} hover:bg-red-600/10 transition">
                <ion-icon name="{{ $album->likes()->where('user_id', auth()->id())->exists() ? 'heart' : 'heart-outline' }}" class="w-5 h-5"></ion-icon>
            </button>
        </form>

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

        <!-- Comment Form -->
        @auth
        <form action="{{ route('interactions.comment', ['type' => 'album', 'id' => $album->id]) }}" method="POST" class="mb-8">
            @csrf
            <div class="relative">
                <textarea name="body" rows="3" class="w-full bg-[#141e24] border border-[#53a1b3]/20 text-white text-sm p-4 focus:outline-none focus:border-[#e96c4c]/50 transition placeholder-[#53a1b3]/40" placeholder="Give your thoughts on this album..."></textarea>
                <button type="submit" class="mt-2 w-full bg-[#e96c4c]/10 border border-[#e96c4c]/20 py-3 text-[#e96c4c] text-xs uppercase tracking-widest hover:bg-[#e96c4c]/20 transition">Post Comment</button>
            </div>
        </form>
        @else
        <div class="bg-[#141e24] border border-[#53a1b3]/10 p-6 text-center mb-8">
            <p class="text-[#53a1b3] text-xs">Please <a href="{{ route('login') }}" class="text-[#e96c4c] underline">login</a> to leave a comment or like this album.</p>
        </div>
        @endauth

        <!-- Comments List -->
        <div class="space-y-6">
            @forelse($album->comments()->latest()->get() as $comment)
            <div class="bg-[#141e24]/50 border-l-2 border-[#e96c4c]/30 p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[#e96c4c] text-[11px] font-normal uppercase tracking-wider">{{ $comment->user->name }}</span>
                    <span class="text-[#53a1b3]/50 text-[9px]">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-white/80 text-sm font-light leading-relaxed">{{ $comment->body }}</p>
            </div>
            @empty
            <div class="text-center py-10 opacity-30">
                <ion-icon name="chatbox-ellipses-outline" class="w-10 h-10 text-[#53a1b3] mb-3"></ion-icon>
                <p class="text-[#53a1b3] text-xs uppercase tracking-widest">No review yet</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
