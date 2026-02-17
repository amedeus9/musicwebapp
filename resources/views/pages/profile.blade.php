@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col overflow-y-auto pb-24 gap-1">

    <!-- Profile Header -->
    <div class="relative mb-8 -mx-2">
        <!-- Cover Photo -->
        <div class="h-32 w-full bg-gradient-to-r from-[#0f1319] to-[#1a2730] border-b border-[#53a1b3]/20"></div>

        <!-- Avatar -->
        <div class="absolute -bottom-6 left-4">
            <div class="w-20 h-20 bg-[#0f1319] border-2 border-[#e96c4c] flex items-center justify-center p-1">
                <div class="w-full bg-[#213042] flex items-center justify-center">
                    <a href="{{ route('home') }}" class="w-8 h-8 flex items-center justify-center text-[#e96c4c] shrink-0">
                        <ion-icon name="musical-notes-outline" class="w-5 h-5"></ion-icon>
                    </a>
                </div>
            </div>
        </div>

        <!-- Edit Button -->
        <div class="absolute bottom-2 right-2">
            <a href="{{ route('profile.edit') }}" class="inline-block bg-[#0f1319] border border-[#53a1b3]/20 text-[#53a1b3] px-3 py-1 text-xs font-normal uppercase tracking-wider hover:bg-[#1a2730] transition">
                Edit Profile
            </a>
        </div>
    </div>

    <!-- User Info -->
    <div class="mt-2">
        <h2 class="text-white font-normal text-xl">{{ auth()->user()->name }}</h2>
        <p class="text-[#53a1b3] text-sm">@{{ auth()->user()->email }}</p>

        <div class="flex gap-4 my-4">
            <div class="flex flex-col">
                <span class="text-white font-normal">{{ auth()->user()->songs()->count() }}</span>
                <span class="text-[#53a1b3] text-xs uppercase">Uploads</span>
            </div>
        </div>

        <p class="text-white/80 text-sm mb-4">
            Welcome {{ auth()->user()->name }}! You're a premium member of MusicApp.
        </p>
    </div>

    <!-- Menu Items -->
    <div class="mt-2 border-t border-[#53a1b3]/10 pt-2">
        <a href="{{ route('favorites') }}" class="flex items-center justify-between p-3 hover:bg-[#0f1319] transition border border-transparent hover:border-[#53a1b3]/10 mb-1">
            <div class="flex items-center gap-3">
                <ion-icon name="heart-outline" class="w-5 h-5 text-[#e96c4c]"></ion-icon>
                <span class="text-white text-sm font-normal">My Favorites</span>
            </div>
            <ion-icon name="chevron-forward-outline" class="w-4 h-4 text-[#53a1b3]"></ion-icon>
        </a>

        <a href="#" class="flex items-center justify-between p-3 hover:bg-[#0f1319] transition border border-transparent hover:border-[#53a1b3]/10 mb-1">
            <div class="flex items-center gap-3">
                <ion-icon name="list-outline" class="w-5 h-5 text-[#53a1b3]"></ion-icon>
                <span class="text-white text-sm font-normal">My Playlists</span>
            </div>
            <ion-icon name="chevron-forward-outline" class="w-4 h-4 text-[#53a1b3]"></ion-icon>
        </a>

        <a href="#" class="flex items-center justify-between p-3 hover:bg-[#0f1319] transition border border-transparent hover:border-[#53a1b3]/10 mb-1">
            <div class="flex items-center gap-3">
                <ion-icon name="settings-outline" class="w-5 h-5 text-[#53a1b3]"></ion-icon>
                <span class="text-white text-sm font-normal">Settings</span>
            </div>
            <ion-icon name="chevron-forward-outline" class="w-4 h-4 text-[#53a1b3]"></ion-icon>
        </a>

        <a href="{{ route('logout') }}" class="flex items-center justify-between p-3 hover:bg-[#0f1319] transition border border-transparent hover:border-[#53a1b3]/10 mb-1 mt-4"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <div class="flex items-center gap-3">
                <ion-icon name="log-out-outline" class="w-5 h-5 text-[#e96c4c]"></ion-icon>
                <span class="text-[#e96c4c] text-sm font-normal">Sign Out</span>
            </div>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>

</div>
@endsection
