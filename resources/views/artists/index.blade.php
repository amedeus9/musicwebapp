@extends('layouts.app')

@section('content')
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-2">
            <div class="w-12 h-12 flex items-center justify-center bg-[#e96c4c]/10 rounded-[3px] border border-[#e96c4c]/20 shrink-0">
                <ion-icon name="mic" class="w-6 h-6 text-[#e96c4c]"></ion-icon>
            </div>
            <div>
                <h1 class="text-lg font-normal text-white uppercase tracking-wider">Discover Artists</h1>
                <p class="text-[10px] text-[#53a1b3]/50 uppercase tracking-widest mt-0.5">Explore the voices behind the rhythm</p>
            </div>
        </div>
        <div class="hidden md:flex items-center gap-2">
            <span class="text-[10px] text-[#53a1b3]/30 uppercase tracking-widest border border-[#53a1b3]/10 px-2 py-1 rounded-[3px]">{{ $artists->total() }} ARTISTS</span>
        </div>
    </div>

    @if($artists->count() > 0)
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2">
        @foreach($artists as $artist)
            <x-artist-card :artist="$artist" />
        @endforeach
    </div>

    <!-- Pagination -->
    @if($artists->hasPages())
    <div class="mt-8 py-4 border-t border-[#53a1b3]/5 flex justify-center">
        {{ $artists->links() }}
    </div>
    @endif

    @else
        <x-empty-state 
            title="No Artists Found" 
            message="We couldn't find any artists at the moment. Check back later!" 
            icon="mic-off-outline" 
        />
    @endif

</div>
@endsection
