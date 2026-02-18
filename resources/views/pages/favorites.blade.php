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
                                 <x-stat-badge icon="heart" :value="$song->likes()->count()" icon-color="text-[#e96c4c]" />
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
                <x-empty-state
                    icon="heart-outline"
                    title="No Favorites Yet"
                    message="Songs you like will appear here. Start exploring and add your favorite music!"
                    action-label="Browse Songs"
                    :action-url="route('songs.index')"
                />
            @endif
        @else
            <x-empty-state
                icon="lock-closed-outline"
                title="Login Required"
                message="Please login to view and manage your favorite songs."
                action-label="Login"
                :action-url="route('login')"
            />
        @endauth
    </div>

</div>
@endsection
