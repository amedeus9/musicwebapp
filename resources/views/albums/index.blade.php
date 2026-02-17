@extends('layouts.app')

@section('content')
<div class="flex flex-col gap-4 pb-20">
    <!-- Header -->
    <div class="px-2 pt-2 text-[#53a1b3] font-normal text-xs uppercase tracking-wider flex items-center justify-between">
        <span>Albums</span>
        <span class="text-[10px] opacity-50">{{ $albums->count() }} Items</span>
    </div>

    <!-- Album Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 px-2">
        @foreach($albums as $album)
        <a href="{{ route('albums.show', $album) }}" class="group flex flex-col gap-2">
            <div class="aspect-square bg-[#0f1319] relative overflow-hidden rounded-none">
                @if($album->cover_path)
                    <img src="{{ Storage::url($album->cover_path) }}" alt="{{ $album->title }}" class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <ion-icon name="musical-notes-outline" class="w-10 h-10 text-[#53a1b3]/20"></ion-icon>
                    </div>
                @endif
                
                <!-- Overlay -->
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                    <ion-icon name="play-outline" class="w-8 h-8 text-white drop-shadow-md transform scale-50 group-hover:scale-100 transition"></ion-icon>
                </div>
            </div>
            
            <div class="flex flex-col">
                <h3 class="text-white text-sm font-normal leading-tight line-clamp-1 group-hover:text-[#e96c4c] transition">{{ $album->title }}</h3>
                <p class="text-[#53a1b3] text-xs font-medium line-clamp-1">{{ $album->artistProfile->name ?? 'Unknown Artist' }}</p>
            </div>
        </a>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="px-2 mt-4">
        {{ $albums->links() }}
    </div>
</div>
@endsection
