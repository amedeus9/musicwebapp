@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col overflow-y-auto pb-24 gap-1">

    <!-- Header Section -->
    <div class="flex items-center gap-4 mb-6">
        <div class="w-12 h-12 flex items-center justify-center bg-[#e96c4c]/10 rounded-[3px] border border-[#e96c4c]/20 shrink-0">
            <ion-icon name="cloud-upload-outline" class="w-6 h-6 text-[#e96c4c]"></ion-icon>
        </div>
        <div>
            <h1 class="text-lg font-normal text-white uppercase tracking-wider">Upload New Track</h1>
            <p class="text-[10px] text-[#53a1b3]/50 uppercase tracking-widest mt-0.5">Share your music with the world</p>
        </div>
    </div>

    <!-- Form Section -->
    <div class="max-w-2xl">
        <form action="{{ route('songs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Title -->
                <div class="space-y-1.5">
                    <label class="text-[10px] text-[#53a1b3]/50 uppercase tracking-widest block">Song Title</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="title" 
                            class="w-full h-[35px] bg-[#1a2730]/40 border border-[#53a1b3]/10 text-white text-xs px-2 rounded-[3px] focus:outline-none focus:border-[#e96c4c]/30 transition placeholder-[#53a1b3]/20"
                            placeholder="Enter song title..."
                            required
                        >
                    </div>
                </div>

                <!-- Artist -->
                <div class="space-y-1.5">
                    <label class="text-[10px] text-[#53a1b3]/50 uppercase tracking-widest block">Artist Name</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="artist" 
                            class="w-full h-[35px] bg-[#1a2730]/40 border border-[#53a1b3]/10 text-white text-xs px-2 rounded-[3px] focus:outline-none focus:border-[#e96c4c]/30 transition placeholder-[#53a1b3]/20"
                            placeholder="Enter artist name..."
                            required
                        >
                    </div>
                </div>
            </div>

            <!-- Country & Category (If applicable) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-[10px] text-[#53a1b3]/50 uppercase tracking-widest block">Country</label>
                    <div class="relative">
                        <select 
                            name="country_id" 
                            class="w-full h-[35px] bg-[#1a2730]/40 border border-[#53a1b3]/10 text-white text-xs px-2 rounded-[3px] focus:outline-none focus:border-[#e96c4c]/30 transition appearance-none"
                            required
                        >
                            <option value="" disabled selected class="bg-[#1a2730]">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" class="bg-[#1a2730]">{{ $country->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-[#53a1b3]/40">
                            <ion-icon name="chevron-down-outline" class="w-3 h-3"></ion-icon>
                        </div>
                    </div>
                </div>

                <div class="space-y-1.5 flex flex-col justify-end">
                    <p class="text-[10px] text-[#53a1b3]/30 uppercase tracking-widest flex items-center gap-1.5">
                        <ion-icon name="information-circle-outline" class="w-3 h-3"></ion-icon>
                        Pick the origin of the sound
                    </p>
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-1.5">
                <label class="text-[10px] text-[#53a1b3]/50 uppercase tracking-widest block">Description <span class="normal-case opacity-40 ml-1">(Optional)</span></label>
                <textarea 
                    name="description" 
                    rows="3"
                    class="w-full bg-[#1a2730]/40 border border-[#53a1b3]/10 text-white text-xs p-2 rounded-[3px] focus:outline-none focus:border-[#e96c4c]/30 transition resize-none placeholder-[#53a1b3]/20 leading-relaxed"
                    placeholder="Tell us about this track..."
                ></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Audio File -->
                <div class="space-y-2">
                    <label class="text-[10px] text-[#53a1b3]/50 uppercase tracking-widest block">Audio File</label>
                    <label class="group relative flex flex-col items-center justify-center w-full h-32 border border-dashed border-[#53a1b3]/20 rounded-[3px] hover:border-[#e96c4c]/40 hover:bg-[#e96c4c]/5 transition-all cursor-pointer">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <ion-icon name="musical-note-outline" class="w-6 h-6 text-[#53a1b3]/30 group-hover:text-[#e96c4c] transition mb-2"></ion-icon>
                            <span class="text-[10px] text-[#53a1b3]/40 group-hover:text-white transition uppercase tracking-widest" id="audio-filename">Select MP3/WAV File</span>
                        </div>
                        <input 
                            type="file" 
                            name="file" 
                            accept=".mp3,.wav,.ogg" 
                            class="hidden"
                            required
                            onchange="document.getElementById('audio-filename').innerText = this.files[0].name"
                        >
                    </label>
                    <p class="text-[9px] text-[#53a1b3]/20 uppercase tracking-tighter">Max 10MB • Recommended high bitrate</p>
                </div>

                <!-- Cover Image -->
                <div class="space-y-2">
                    <label class="text-[10px] text-[#53a1b3]/50 uppercase tracking-widest block">Cover Image <span class="normal-case opacity-40 ml-1">(Optional)</span></label>
                    <label class="group relative flex flex-col items-center justify-center w-full h-32 border border-dashed border-[#53a1b3]/20 rounded-[3px] hover:border-[#e96c4c]/40 hover:bg-[#e96c4c]/5 transition-all cursor-pointer">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <ion-icon name="image-outline" class="w-6 h-6 text-[#53a1b3]/30 group-hover:text-[#e96c4c] transition mb-2"></ion-icon>
                            <span class="text-[10px] text-[#53a1b3]/40 group-hover:text-white transition uppercase tracking-widest" id="cover-filename">Select Artwork</span>
                        </div>
                        <input 
                            type="file" 
                            name="cover" 
                            accept="image/*" 
                            class="hidden"
                            onchange="document.getElementById('cover-filename').innerText = this.files[0].name"
                        >
                    </label>
                    <p class="text-[9px] text-[#53a1b3]/20 uppercase tracking-tighter">Square JPG/PNG recommended</p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 flex justify-end">
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white text-xs font-normal uppercase tracking-widest transition rounded-[3px] flex items-center gap-2"
                >
                    <ion-icon name="cloud-upload-outline" class="w-4 h-4"></ion-icon>
                    Upload Track
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
