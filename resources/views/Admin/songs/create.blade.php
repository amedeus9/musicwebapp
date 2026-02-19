@extends('Admin.layouts.app')

@section('page-title', 'Upload Song')

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-white font-normal text-xl uppercase tracking-wider">Upload New Song</h2>
            <p class="text-[#53a1b3] text-sm">Add a new track to the system</p>
        </div>
        <a href="{{ route('admin.songs.index') }}" class="text-[#53a1b3] hover:text-white transition flex items-center gap-2">
            <ion-icon name="arrow-back-outline"></ion-icon>
            Back to Songs
        </a>
    </div>

    <!-- Form -->
    <div class="bg-[#213042] border border-[#53a1b3]/10 p-6 rounded-[3px] max-w-4xl">
        <form action="{{ route('admin.songs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 p-4 rounded-[3px]">
                    <ul class="list-disc list-inside text-red-400 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="space-y-2">
                    <label class="text-[#53a1b3] text-xs uppercase tracking-wider block">Song Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full bg-[#141e24] border border-[#53a1b3]/20 text-white px-4 py-2 text-sm focus:outline-none focus:border-[#e96c4c] transition" required>
                </div>
                
                <!-- Artist Selection -->
                <div class="space-y-2">
                    <label class="text-[#53a1b3] text-xs uppercase tracking-wider block">Artist</label>
                    <div class="relative">
                        <select name="artist_id" class="w-full bg-[#141e24] border border-[#53a1b3]/20 text-white px-4 py-2 text-sm focus:outline-none focus:border-[#e96c4c] transition appearance-none" required>
                            <option value="" disabled selected>Select Artist</option>
                            @foreach($artists as $artist)
                                <option value="{{ $artist->id }}" {{ old('artist_id') == $artist->id ? 'selected' : '' }}>{{ $artist->name }}</option>
                            @endforeach
                        </select>
                         <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-[#53a1b3]">
                            <ion-icon name="chevron-down-outline"></ion-icon>
                        </div>
                    </div>
                </div>

                <!-- Audio File -->
                <div class="space-y-2">
                    <label class="text-[#53a1b3] text-xs uppercase tracking-wider block">Audio File (MP3)</label>
                    <input type="file" name="file" accept=".mp3,.wav,.ogg" class="w-full text-[#53a1b3] text-sm file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-sm file:font-semibold file:bg-[#e96c4c] file:text-white hover:file:bg-[#e96c4c]/90" required>
                </div>

                <!-- Cover Image -->
                <div class="space-y-2">
                   <label class="text-[#53a1b3] text-xs uppercase tracking-wider block">Cover Image (Optional)</label>
                   <input type="file" name="cover" accept="image/*" class="w-full text-[#53a1b3] text-sm file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-sm file:font-semibold file:bg-[#141e24] file:text-[#53a1b3] hover:file:bg-[#1a2834]">
                   <p class="text-[10px] text-[#53a1b3]/50">If left empty, Default Audio Cover settings will apply.</p>
                </div>

                <!-- Description -->
                <div class="space-y-2 md:col-span-2">
                    <label class="text-[#53a1b3] text-xs uppercase tracking-wider block">Description</label>
                    <textarea name="description" rows="3" class="w-full bg-[#141e24] border border-[#53a1b3]/20 text-white px-4 py-2 text-sm focus:outline-none focus:border-[#e96c4c] transition">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-[#53a1b3]/10 mt-6">
                <button type="submit" class="bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white px-6 py-2 text-sm uppercase tracking-wider transition">
                    Upload Song
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
