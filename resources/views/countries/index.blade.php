@extends('layouts.app')

@section('content')
<div class="flex flex-col gap-6 p-2 md:p-4">

    <div class="border-b border-[#53a1b3]/10 pb-4">
        <h1 class="text-2xl font-light text-white tracking-tight">Browse Countries</h1>
        <p class="text-sm text-[#53a1b3]/70">Select a country to explore its trending music.</p>
    </div>

    <!-- Country Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        
        <!-- Global Option -->
        <a href="{{ route('country.songs', ['country' => 'global']) }}" 
           class="group block bg-[#1a1f26] border border-[#53a1b3]/10 hover:border-[#e96c4c]/50 rounded-xl p-4 transition text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-[#e96c4c]/10 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
            
            <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-[#0f1319] flex items-center justify-center text-xl font-bold text-[#e96c4c] group-hover:scale-110 transition border border-[#e96c4c]/20">
                <ion-icon name="earth"></ion-icon>
            </div>
            
            <h3 class="text-white font-medium text-sm group-hover:text-[#e96c4c] transition relative z-10">Global / Worldwide</h3>
            <p class="text-xs text-[#53a1b3]/50 mt-1 relative z-10">All Songs</p>
        </a>

        @foreach($countries as $country)
            <a href="{{ route('country.songs', ['country' => $country->slug]) }}" 
               class="group block bg-[#1a1f26] border border-[#53a1b3]/10 hover:border-[#e96c4c]/50 rounded-xl p-4 transition text-center">
                
                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-[#0f1319] flex items-center justify-center text-xs font-mono font-bold text-[#53a1b3] group-hover:text-[#e96c4c] transition border border-[#53a1b3]/20">
                    {{ $country->iso_code }}
                </div>
                
                <h3 class="text-white font-medium text-sm group-hover:text-[#e96c4c] transition">{{ $country->name }}</h3>
                <p class="text-xs text-[#53a1b3]/50 mt-1">View Songs</p>
            </a>
        @endforeach
    </div>

</div>
@endsection
