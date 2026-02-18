@extends('layouts.app')

@section('content')
<div class="flex flex-col gap-6">

    <div class="flex flex-col gap-1">
        <h1 class="text-2xl font-normal text-white uppercase tracking-wider">Discover Artists</h1>
        <p class="text-[#53a1b3]/60 text-xs uppercase tracking-[0.2em]">Explore the voices behind the rhythm</p>
    </div>

    @if($artists->count() > 0)
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($artists as $artist)
        <a href="{{ route('artists.show', $artist) }}" class="group flex flex-col items-center text-center gap-3">
            <div class="relative w-full aspect-square bg-[#1a2730]/40 rounded-[3px] overflow-hidden border border-white/5 shadow-lg group-hover:border-[#e96c4c]/30 transition duration-500">
                @if($artist->image_path)
                    <img src="{{ Storage::url($artist->image_path) }}" 
                         alt="{{ $artist->name }}" 
                         class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-110 transition duration-700">
                @else
                    <div class="w-full h-full flex items-center justify-center text-[#53a1b3]/10 grayscale group-hover:grayscale-0 transition duration-700">
                        <ion-icon name="mic-outline" class="w-12 h-12"></ion-icon>
                    </div>
                @endif

                <!-- Play Overlay (Visual Only) -->
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-500 flex items-center justify-center">
                    <div class="w-10 h-10 bg-[#e96c4c] rounded-[3px] flex items-center justify-center text-white transform translate-y-4 group-hover:translate-y-0 transition duration-500 shadow-xl shadow-[#e96c4c]/20">
                        <ion-icon name="arrow-forward-outline" class="w-5 h-5"></ion-icon>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col gap-1 items-center">
                <h3 class="text-white text-[11px] lg:text-[13px] font-normal uppercase tracking-wider truncate group-hover:text-[#e96c4c] transition w-full px-2">
                    {{ $artist->name }}
                </h3>
                <span class="text-[#53a1b3]/40 text-[9px] uppercase tracking-[0.2em]">
                    {{ $artist->songs_count }} {{ Str::plural('Track', $artist->songs_count) }}
                </span>
            </div>
        </a>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $artists->links() }}
    </div>

    @else
        <x-empty-state 
            title="No Artists Found" 
            message="We couldn't find any artists at the moment. Check back later!" 
            icon="mic-off-outline" 
        />
    @endif

</div>
@endsection
