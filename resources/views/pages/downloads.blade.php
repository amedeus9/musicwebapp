@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col overflow-y-auto pb-24 gap-1">

    <div class="my-0.5">
        <h3 class="font-normal text-[#53a1b3] text-xl uppercase tracking-wider py-1 mb-2">My Favorites</h3>

        @auth
            @if($hasFavorites)
                <div class="space-y-1">
                    @foreach($songs as $song)
                    <a href="{{ route('songs.show', $song->slug) }}" class="flex items-center gap-3 group transition hover:bg-[#0f1319] border border-transparent hover:border-[#53a1b3]/10">

                        @if($song->cover_path)
                            <div class="relative w-12 h-12 shrink-0 group">
                                <img src="{{ Storage::url($song->cover_path) }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <ion-icon name="play" class="w-5 h-5 text-white"></ion-icon>
                                </div>
                            </div>
                        @else
                            <div class="w-12 h-12 bg-[#213042] flex items-center justify-center shrink-0">
                                <ion-icon name="musical-notes-outline" class="w-5 h-5 text-[#53a1b3]"></ion-icon>
                            </div>
                        @endif

                        <div class="flex-1 min-w-0 py-2">
                            <h4 class="text-white font-normal text-sm mb-0.5 truncate group-hover:text-[#e96c4c] transition">{{ $song->title }}</h4>
                            <div class="flex items-center gap-2">
                                <p class="text-[11px] text-[#53a1b3] truncate">{{ $song->artist }}</p>

                                <!-- Likes Count Badge -->
                                <div class="flex items-center h-4 border border-[#53a1b3]/20 overflow-hidden">
                                    <div class="bg-[#141e24] px-1.5 h-full flex items-center justify-center border-r border-[#53a1b3]/10">
                                        <ion-icon name="heart" class="w-2 h-2 text-[#e96c4c]"></ion-icon>
                                    </div>
                                    <div class="bg-[#53a1b3]/10 px-1.5 h-full flex items-center justify-center text-[9px] font-normal text-[#53a1b3] font-mono">
                                        {{ $song->likes()->count() }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Play Button -->
                        <button class="w-8 h-8 border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3] hover:text-[#e96c4c] hover:border-[#e96c4c]/50 transition shrink-0"
                                onclick="event.stopPropagation();">
                            <ion-icon name="play" class="w-4 h-4"></ion-icon>
                        </button>
                    </a>
                    @endforeach
                </div>
            @else
                <div class="p-8 flex flex-col items-center justify-center gap-3">
                    <div class="w-16 h-16 bg-[#213042] flex items-center justify-center">
                        <ion-icon name="heart-outline" class="w-10 h-10 text-[#e96c4c]"></ion-icon>
                    </div>
                    <h4 class="text-white font-normal text-base">No Favorites Yet</h4>
                    <p class="text-[#53a1b3] text-sm text-center max-w-xs">Songs you like will appear here. Start exploring and add your favorite music!</p>
                    <a href="{{ route('songs.index') }}" class="mt-2 bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white px-4 py-2 text-xs font-normal uppercase tracking-wider transition">
                        Browse Songs
                    </a>
                </div>
            @endif
        @else
            <div class="bg-[#0f1319] p-8 flex flex-col items-center justify-center gap-3 border border-[#53a1b3]/20">
                <div class="w-16 h-16 bg-[#213042] flex items-center justify-center">
                    <ion-icon name="lock-closed-outline" class="w-10 h-10 text-[#e96c4c]"></ion-icon>
                </div>
                <h4 class="text-white font-normal text-base">Login Required</h4>
                <p class="text-[#53a1b3] text-sm text-center max-w-xs">Please login to view and manage your favorite songs.</p>
                <a href="{{ route('login') }}" class="mt-2 bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white px-4 py-2 text-xs font-normal uppercase tracking-wider transition">
                    Login
                </a>
            </div>
        @endauth
    </div>

</div>
@endsection
