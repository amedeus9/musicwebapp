@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col gap-1 pb-24">

    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-lg font-normal text-white uppercase tracking-wider">Explore Albums</h1>
            <p class="text-[10px] text-[#53a1b3]/50 uppercase tracking-widest mt-0.5">Discover the latest collections from our artists</p>
        </div>
        <div class="hidden md:flex items-center gap-2">
            <span class="text-[10px] text-[#53a1b3]/30 uppercase tracking-widest">{{ $albums->total() }} ALBUMS FOUND</span>
        </div>
    </div>

    @if($albums->isEmpty())
        <x-empty-state
            icon="library-outline"
            title="No Albums Found"
            message="We couldn't find any albums in our collection right now."
        />
    @else
        <!-- Album Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            @foreach($albums as $album)
            <div class="group relative flex flex-col gap-3">
                <!-- Cover Image -->
                <a href="{{ route('albums.show', $album) }}" class="relative aspect-square overflow-hidden bg-[#1a2730]/20 rounded-[3px] border border-[#53a1b3]/5 block group">
                    @if($album->cover_path)
                        <img src="{{ Storage::url($album->cover_path) }}" 
                             alt="{{ $album->title }}" 
                             class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-[#53a1b3]/10">
                            <ion-icon name="musical-notes" class="w-12 h-12"></ion-icon>
                        </div>
                    @endif

                    <!-- Premium Overlay -->
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                        <div class="w-10 h-10 bg-[#e96c4c] flex items-center justify-center text-white scale-0 group-hover:scale-100 transition duration-300 shadow-xl rounded-[3px]">
                            <ion-icon name="play-outline" class="w-5 h-5 ml-0.5"></ion-icon>
                        </div>
                    </div>
                </a>

                <!-- Album Info -->
                <div class="flex flex-col min-w-0">
                    <a href="{{ route('albums.show', $album) }}" 
                       class="text-white text-xs font-normal uppercase tracking-wide truncate group-hover:text-[#e96c4c] transition duration-300">
                        {{ $album->title }}
                    </a>
                    
                    @if($album->artistProfile)
                        <a href="{{ route('artist.show', $album->artistProfile->slug) }}" 
                           class="text-[#53a1b3]/50 text-[10px] uppercase tracking-widest truncate hover:text-[#53a1b3] transition mt-0.5">
                            {{ $album->artistProfile->name }}
                        </a>
                    @else
                        <span class="text-[#53a1b3]/30 text-[10px] uppercase tracking-widest truncate mt-0.5">Unknown Artist</span>
                    @endif
                </div>

                <!-- Subtle Year Badge (Optional) -->
                @if($album->release_year)
                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                         <span class="text-[8px] bg-[#1a2730]/60 backdrop-blur-sm text-white/50 px-1.5 py-0.5 rounded-[2px] border border-white/5 uppercase tracking-tighter">{{ $album->release_year }}</span>
                    </div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Pagination Section -->
        @if($albums->hasPages())
        <div class="mt-12 py-6 border-t border-[#53a1b3]/5">
            {{ $albums->links() }}
        </div>
        @endif
    @endif

</div>
@endsection
