@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col gap-1 pb-24">

    <!-- Header Section -->
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-2">
            <div class="w-12 h-12 flex items-center justify-center bg-[#e96c4c]/10 rounded-[3px] border border-[#e96c4c]/20 shrink-0">
                <ion-icon name="disc" class="w-6 h-6 text-[#e96c4c]"></ion-icon>
            </div>
            <div>
                <h1 class="text-lg font-normal text-white uppercase tracking-wider">Explore Albums</h1>
                <p class="text-[10px] text-[#53a1b3]/50 uppercase tracking-widest mt-0.5">Discover collections from our artists</p>
            </div>
        </div>
        <div class="hidden md:flex items-center gap-2">
            <span class="text-[10px] text-[#53a1b3]/30 uppercase tracking-widest border border-[#53a1b3]/10 px-2 py-1 rounded-[3px]">{{ $albums->total() }} ALBUMS</span>
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
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-2">
            @foreach($albums as $album)
                <x-album-card :album="$album" />
            @endforeach
        </div>

        <!-- Pagination Section -->
        @if($albums->hasPages())
        <div class="mt-8 py-4 border-t border-[#53a1b3]/5 flex justify-center">
            {{ $albums->appends(request()->query())->links() }} 
        </div>
        @endif
    @endif

</div>
@endsection
