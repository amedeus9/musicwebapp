@extends('Admin.layouts.app')

@section('page-title', 'Create Artist')

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-white font-normal text-xl uppercase tracking-wider">Create New Artist</h2>
            <p class="text-[#53a1b3] text-sm">Add a new artist profile</p>
        </div>
        <a href="{{ route('admin.artists.index') }}" class="text-[#53a1b3] hover:text-white transition flex items-center gap-2">
            <ion-icon name="arrow-back-outline"></ion-icon>
            Back to Artists
        </a>
    </div>

    <!-- Form -->
    <div class="bg-[#213042] border border-[#53a1b3]/10 p-6 rounded-[3px] max-w-2xl">
        <form action="{{ route('admin.artists.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 p-4 rounded-[3px]">
                   <ul class="list-disc list-inside text-red-400 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-4">
                <!-- Name -->
                <div class="space-y-2">
                    <label class="text-[#53a1b3] text-xs uppercase tracking-wider block">Artist Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full bg-[#141e24] border border-[#53a1b3]/20 text-white px-4 py-2 text-sm focus:outline-none focus:border-[#e96c4c] transition" required>
                </div>

                <!-- Country -->
                <div class="space-y-2">
                    <label class="text-[#53a1b3] text-xs uppercase tracking-wider block">Country <span class="normal-case opacity-40 ml-1">(Optional)</span></label>
                    <div class="relative">
                        <select name="country_id" class="w-full bg-[#141e24] border border-[#53a1b3]/20 text-white px-4 py-2 text-sm focus:outline-none focus:border-[#e96c4c] transition appearance-none">
                            <option value="" selected>Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-[#53a1b3]">
                            <ion-icon name="chevron-down-outline"></ion-icon>
                        </div>
                    </div>
                </div>

                <!-- Bio -->
                <div class="space-y-2">
                    <label class="text-[#53a1b3] text-xs uppercase tracking-wider block">Biography</label>
                    <textarea name="bio" rows="4" class="w-full bg-[#141e24] border border-[#53a1b3]/20 text-white px-4 py-2 text-sm focus:outline-none focus:border-[#e96c4c] transition">{{ old('bio') }}</textarea>
                </div>

                <!-- Image -->
                 <div class="space-y-2">
                    <label class="text-[#53a1b3] text-xs uppercase tracking-wider block">Artist Image</label>
                    <div class="flex items-center gap-4">
                        <div class="w-20 h-20 bg-[#141e24] rounded-full flex items-center justify-center border border-[#53a1b3]/20 overflow-hidden" id="preview-container">
                             <ion-icon name="person-outline" class="w-8 h-8 text-[#53a1b3]"></ion-icon>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="image" accept="image/*" class="w-full text-[#53a1b3] text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#e96c4c] file:text-white hover:file:bg-[#e96c4c]/90"
                            onchange="document.getElementById('preview-container').innerHTML = '<img src=\'' + window.URL.createObjectURL(this.files[0]) + '\' class=\'w-full h-full object-cover\'>'">
                             <p class="text-[10px] text-[#53a1b3]/50 mt-1">Square image recommended.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-[#53a1b3]/10 mt-6">
                <button type="submit" class="bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white px-6 py-2 text-sm uppercase tracking-wider transition">
                    Create Artist
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
