@extends('layouts.app')

@section('content')
<div class="flex flex-col pb-24">
    
    <!-- Artist Header (Hero) -->
    <div class="relative h-[300px] md:h-[400px] w-full overflow-hidden">
        @if($artist->image_path)
            <img src="{{ Storage::url($artist->image_path) }}" alt="{{ $artist->name }}" class="w-full h-full object-cover filter brightness-50">
        @else
            <div class="w-full h-full bg-gradient-to-b from-[#1a2730] to-[#141e24] flex items-center justify-center">
                <ion-icon name="mic-outline" class="w-24 h-24 text-[#53a1b3]/10"></ion-icon>
            </div>
        @endif
        
        <div class="absolute inset-0 bg-gradient-to-t from-[#141e24] via-transparent to-transparent"></div>

        <div class="absolute bottom-0 left-0 w-full p-6">
            <h1 class="text-4xl md:text-6xl font-normal text-white uppercase tracking-tight mb-2">{{ $artist->name }}</h1>
            <p class="text-[#53a1b3] text-sm md:text-base font-medium max-w-2xl line-clamp-2 md:line-clamp-none">{{ $artist->bio }}</p>
        </div>
    </div>

    <div class="px-4 py-6 flex flex-col gap-8">
        
        <!-- Popular Songs -->
        <div class="flex flex-col gap-4">
            <h3 class="text-white text-lg font-normal">Popular Songs</h3>
            <div class="flex flex-col gap-2">
                @foreach($artist->songs as $index => $song)
                <a href="{{ route('songs.show', $song) }}" class="flex items-center gap-4 p-2 rounded-none hover:bg-white/5 transition group">
                    <div class="w-8 text-center text-[#53a1b3]/50 font-mono font-normal">{{ $index + 1 }}</div>
                    <div class="w-12 h-12 bg-[#0f1319] rounded overflow-hidden shrink-0">
                        @if($song->cover_path)
                            <img src="{{ Storage::url($song->cover_path) }}" alt="{{ $song->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-[#1a2730]"><ion-icon name="musical-notes-outline" class="w-5 h-5 text-[#53a1b3]/50"></ion-icon></div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-white font-normal text-sm truncate group-hover:text-[#e96c4c] transition">{{ $song->title }}</div>
                        <div class="text-[#53a1b3] text-xs truncate">{{ number_format($song->downloads) }} downloads</div>
                    </div>
                    <button class="w-8 h-8 rounded-none border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3] hover:bg-white hover:text-black transition">
                        <ion-icon name="play-outline" class="w-4 h-4 ml-0.5"></ion-icon>
                    </button>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Albums -->
        @if($artist->albums->count() > 0)
        <div class="flex flex-col gap-4">
            <h3 class="text-white text-lg font-normal">Albums</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($artist->albums as $album)
                <a href="{{ route('albums.show', $album) }}" class="group">
                    <div class="aspect-square bg-[#0f1319] rounded-none overflow-hidden mb-2 relative">
                        @if($album->cover_path)
                            <img src="{{ Storage::url($album->cover_path) }}" alt="{{ $album->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center"><ion-icon name="musical-notes-outline" class="w-10 h-10 text-[#53a1b3]/20"></ion-icon></div>
                        @endif
                    </div>
                    <p class="text-white text-sm font-normal truncate group-hover:text-[#e96c4c] transition">{{ $album->title }}</p>
                    <p class="text-[#53a1b3] text-xs">{{ \Carbon\Carbon::parse($album->release_date)->format('Y') }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>

</div>
@endsection
