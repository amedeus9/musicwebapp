@extends('Admin.layouts.app')

@section('page-title', 'Edit Country')

@section('content')
<div class="flex flex-col gap-6 max-w-2xl mx-auto">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white tracking-tight">Edit Country</h1>
        <a href="{{ route('admin.countries.index') }}" class="text-[#53a1b3] hover:text-white transition flex items-center gap-1 text-sm">
            <ion-icon name="arrow-back-outline"></ion-icon>
            Back to List
        </a>
    </div>

    <div class="bg-[#1a1f26] border border-[#53a1b3]/10 rounded-xl p-6 shadow-xl">
        <form action="{{ route('admin.countries.update', $country) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Name -->
            <div class="space-y-2">
                <label for="name" class="text-sm text-[#53a1b3] font-medium">Country Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $country->name) }}" 
                    class="w-full bg-[#0f1319] border border-[#53a1b3]/20 text-white rounded-lg px-4 py-2.5 focus:ring-1 focus:ring-[#e96c4c] focus:border-[#e96c4c] outline-none placeholder-[#53a1b3]/30"
                    placeholder="e.g. United States">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- ISO Code -->
            <div class="space-y-2">
                <label for="iso_code" class="text-sm text-[#53a1b3] font-medium">ISO Code (2 Letters)</label>
                <input type="text" name="iso_code" id="iso_code" value="{{ old('iso_code', $country->iso_code) }}" 
                    class="w-full bg-[#0f1319] border border-[#53a1b3]/20 text-white rounded-lg px-4 py-2.5 focus:ring-1 focus:ring-[#e96c4c] focus:border-[#e96c4c] outline-none placeholder-[#53a1b3]/30 uppercase font-mono"
                    placeholder="e.g. US" maxlength="2">
                @error('iso_code')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Region -->
            <div class="space-y-2">
                <label for="region" class="text-sm text-[#53a1b3] font-medium">Region (Optional)</label>
                <input type="text" name="region" id="region" value="{{ old('region', $country->region) }}" 
                    class="w-full bg-[#0f1319] border border-[#53a1b3]/20 text-white rounded-lg px-4 py-2.5 focus:ring-1 focus:ring-[#e96c4c] focus:border-[#e96c4c] outline-none placeholder-[#53a1b3]/30"
                    placeholder="e.g. North America">
                @error('region')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Trending Checkbox -->
            <div class="flex items-center gap-3 py-2">
                <input type="checkbox" name="is_trending" id="is_trending" value="1" {{ old('is_trending', $country->is_trending) ? 'checked' : '' }}
                    class="w-5 h-5 rounded border-gray-600 bg-[#0f1319] text-[#e96c4c] focus:ring-[#e96c4c]/50">
                <label for="is_trending" class="text-sm text-white font-medium select-none cursor-pointer">Mark as Trending</label>
            </div>

            <!-- Submit -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-[#e96c4c] text-white font-medium py-3 rounded-lg shadow-lg hover:bg-[#d45a3a] transition transform hover:-translate-y-0.5">
                    Update Country
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
