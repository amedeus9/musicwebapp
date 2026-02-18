@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col overflow-y-auto pb-24 gap-1">
    
    <div class="flex items-center gap-2 mb-2">
        <div class="w-12 h-12 flex items-center justify-center bg-[#e96c4c]/10 rounded-[3px] border border-[#e96c4c]/20 shrink-0">
            <ion-icon name="heart" class="w-6 h-6 text-[#e96c4c]"></ion-icon>
        </div>
        <div>
            <h1 class="text-lg font-normal text-white uppercase tracking-wider">My Favorites</h1>
            <p class="text-[10px] text-[#53a1b3]/50 uppercase tracking-widest mt-0.5">Songs you have liked</p>
        </div>
    </div>
    @auth
        @if($hasFavorites)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-2">
                @foreach($songs as $song)
                    <x-song-card :song="$song" />
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
@endsection
