@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col overflow-y-auto pb-24 gap-2">

    <!-- Upload Form Card -->
    <div class="bg-[#0f1319] border border-[#53a1b3]/20 rounded-none p-2">
        
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 rounded-none bg-[#213042] flex items-center justify-center text-[#e96c4c] border border-[#e96c4c]/20">
                    <ion-icon name="cloud-upload-outline" class="w-6 h-6"></ion-icon>
                </div>
                <div>
                    <h2 class="text-xl font-normal text-white tracking-tight">Upload New Song</h2>
                    <p class="text-xs text-[#53a1b3]">Share your music with the world</p>
                </div>
            </div>
        </div>

        <form action="{{ route('songs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <!-- Title -->
            <div>
                <label class="block text-sm font-normal text-[#53a1b3] uppercase tracking-wider mb-2">
                    <ion-icon name="musical-notes-outline" class="w-4 h-4 inline-block mr-1"></ion-icon> Song Title
                </label>
                <input 
                    type="text" 
                    name="title" 
                    class="w-full bg-[#1a2730] border border-[#53a1b3]/30 rounded-none p-3 text-white placeholder-[#53a1b3]/50 focus:outline-none focus:border-[#e96c4c] transition"
                    placeholder="Enter song title..."
                    required
                >
            </div>

            <!-- Artist -->
            <div>
                <label class="block text-sm font-normal text-[#53a1b3] uppercase tracking-wider mb-2">
                    <ion-icon name="person-outline" class="w-4 h-4 inline-block mr-1"></ion-icon> Artist Name
                </label>
                <input 
                    type="text" 
                    name="artist" 
                    class="w-full bg-[#1a2730] border border-[#53a1b3]/30 rounded-none p-3 text-white placeholder-[#53a1b3]/50 focus:outline-none focus:border-[#e96c4c] transition"
                    placeholder="Enter artist name..."
                    required
                >
            </div>

            <!-- Country -->
            <div>
                <label class="block text-sm font-normal text-[#53a1b3] uppercase tracking-wider mb-2">
                    <ion-icon name="globe-outline" class="w-4 h-4 inline-block mr-1"></ion-icon> Country
                </label>
                <div class="relative">
                    <select 
                        name="country_id" 
                        class="w-full bg-[#1a2730] border border-[#53a1b3]/30 rounded-none p-3 text-white appearance-none focus:outline-none focus:border-[#e96c4c] transition"
                        required
                    >
                        <option value="" disabled selected>Select Country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-[#53a1b3]">
                        <ion-icon name="chevron-down-outline" class="w-3 h-3"></ion-icon>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-normal text-[#53a1b3] uppercase tracking-wider mb-2">
                    <ion-icon name="list-outline" class="w-4 h-4 inline-block mr-1"></ion-icon> Description <span class="text-xs font-normal normal-case opacity-60">(Optional)</span>
                </label>
                <textarea 
                    name="description" 
                    rows="3"
                    class="w-full bg-[#1a2730] border border-[#53a1b3]/30 rounded-none p-3 text-white placeholder-[#53a1b3]/50 focus:outline-none focus:border-[#e96c4c] transition resize-none"
                    placeholder="Tell us about this track..."
                ></textarea>
            </div>

            <!-- Audio File -->
            <div>
                <label class="block text-sm font-normal text-[#53a1b3] uppercase tracking-wider mb-2">
                    <ion-icon name="mic-outline" class="w-4 h-4 inline-block mr-1"></ion-icon> Audio File <span class="text-xs font-normal normal-case opacity-60">(MP3, WAV, OGG)</span>
                </label>
                <div class="relative">
                    <input 
                        type="file" 
                        name="file" 
                        accept=".mp3,.wav,.ogg" 
                        class="w-full bg-[#1a2730] border border-[#53a1b3]/30 rounded-none p-3 text-[#53a1b3] file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-sm file:font-normal file:bg-[#e96c4c] file:text-white hover:file:bg-[#d45a3a] file:cursor-pointer"
                        required
                    >
                </div>
                <p class="text-xs text-[#53a1b3]/60 mt-1.5 flex items-center gap-1">
                    <ion-icon name="information-circle-outline" class="w-3 h-3"></ion-icon> Maximum file size: 10MB
                </p>
            </div>

            <!-- Cover Image -->
            <div>
                <label class="block text-sm font-normal text-[#53a1b3] uppercase tracking-wider mb-2">
                    <ion-icon name="attach-outline" class="w-4 h-4 inline-block mr-1"></ion-icon> Cover Image <span class="text-xs font-normal normal-case opacity-60">(Optional)</span>
                </label>
                <div class="relative">
                    <input 
                        type="file" 
                        name="cover" 
                        accept="image/*" 
                        class="w-full bg-[#1a2730] border border-[#53a1b3]/30 rounded-none p-3 text-[#53a1b3] file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-sm file:font-normal file:bg-[#31525b] file:text-white hover:file:bg-[#3d646f] file:cursor-pointer"
                    >
                </div>
                <p class="text-xs text-[#53a1b3]/60 mt-1.5 flex items-center gap-1">
                    <ion-icon name="information-circle-outline" class="w-3 h-3"></ion-icon> JPG, PNG, or GIF recommended
                </p>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button 
                    type="submit" 
                    class="w-full py-3.5 rounded-none bg-[#e96c4c] hover:bg-[#d45a3a] text-white font-normal text-base transition flex items-center justify-center gap-2 shadow-lg shadow-[#e96c4c]/20"
                >
                    <ion-icon name="cloud-upload-outline" class="w-5 h-5"></ion-icon>
                    Upload Song
                </button>
            </div>
        </form>

        <!-- Info Box -->
        <div class="mt-6 p-3 bg-[#213042]/30 border border-[#53a1b3]/10 rounded-none">
            <div class="flex items-start gap-2">
                <ion-icon name="information-circle-outline" class="w-4 h-4 text-[#e96c4c] mt-0.5"></ion-icon>
                <div>
                    <h4 class="text-xs font-normal text-white mb-1">Upload Tips</h4>
                    <ul class="text-xs text-[#53a1b3] space-y-0.5 leading-relaxed">
                        <li>• Use high-quality audio files for the best experience</li>
                        <li>• Add an eye-catching cover image to attract listeners</li>
                        <li>• Write a compelling description to engage your audience</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
