@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col overflow-y-auto pb-24 gap-1">

    <div class="mb-4 flex items-center justify-between">
        <h3 class="font-normal text-[#53a1b3] text-xl uppercase tracking-wider">Edit Playlist</h3>
        <a href="{{ route('playlists.show', $playlist->slug) }}" class="text-[#53a1b3] hover:text-white text-xs font-normal uppercase tracking-wider transition flex items-center gap-2">
            <ion-icon name="arrow-back-outline" class="w-4 h-4"></ion-icon>
            <span>Back</span>
        </a>
    </div>

    <div class="bg-[#213042] p-6">
        <form action="{{ route('playlists.update', $playlist->slug) }}" method="POST" enctype="multipart/form-data" class="max-w-2xl">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-[#53a1b3] text-sm font-normal uppercase tracking-wider mb-2">
                        Playlist Name <span class="text-[#e96c4c]">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name', $playlist->name) }}"
                        class="w-full bg-[#141e24] text-white px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#e96c4c] text-sm"
                        required
                    >
                    @error('name')
                        <p class="text-[#e96c4c] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-[#53a1b3] text-sm font-normal uppercase tracking-wider mb-2">
                        Description
                    </label>
                    <textarea
                        name="description"
                        id="description"
                        rows="4"
                        class="w-full bg-[#141e24] text-white px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#e96c4c] text-sm resize-none"
                    >{{ old('description', $playlist->description) }}</textarea>
                    @error('description')
                        <p class="text-[#e96c4c] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cover Image -->
                <div>
                    <label for="cover" class="block text-[#53a1b3] text-sm font-normal uppercase tracking-wider mb-2">
                        Cover Image
                    </label>
                    <div class="flex items-start gap-4">
                        <!-- Current/Preview -->
                        <div id="cover-preview" class="w-24 h-24 bg-[#141e24] overflow-hidden {{ $playlist->cover_path ? '' : 'hidden' }}">
                            @if($playlist->cover_path)
                                <img id="cover-preview-img" src="{{ Storage::url($playlist->cover_path) }}" alt="Cover preview" class="w-full h-full object-cover">
                            @else
                                <img id="cover-preview-img" src="" alt="Cover preview" class="w-full h-full object-cover">
                            @endif
                        </div>

                        <!-- Upload button -->
                        <div class="flex-1">
                            <label for="cover" class="inline-block bg-[#141e24] hover:bg-[#1a2834] text-[#53a1b3] px-4 py-2 text-xs font-normal uppercase tracking-wider cursor-pointer transition">
                                {{ $playlist->cover_path ? 'Change Cover' : 'Choose File' }}
                            </label>
                            <input
                                type="file"
                                name="cover"
                                id="cover"
                                accept="image/*"
                                class="hidden"
                            >
                            <p class="text-[#53a1b3] text-xs mt-2">JPG, PNG, GIF (Max 2MB)</p>
                        </div>
                    </div>
                    @error('cover')
                        <p class="text-[#e96c4c] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Privacy -->
                <div>
                    <label class="block text-[#53a1b3] text-sm font-normal uppercase tracking-wider mb-2">
                        Privacy
                    </label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="radio"
                                name="is_public"
                                value="1"
                                {{ old('is_public', $playlist->is_public ? '1' : '0') === '1' ? 'checked' : '' }}
                                class="w-4 h-4 text-[#e96c4c] bg-[#141e24] border-[#53a1b3] focus:ring-[#e96c4c]"
                            >
                            <span class="text-white text-sm">Public</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="radio"
                                name="is_public"
                                value="0"
                                {{ old('is_public', $playlist->is_public ? '1' : '0') === '0' ? 'checked' : '' }}
                                class="w-4 h-4 text-[#e96c4c] bg-[#141e24] border-[#53a1b3] focus:ring-[#e96c4c]"
                            >
                            <span class="text-white text-sm">Private</span>
                        </label>
                    </div>
                    <p class="text-[#53a1b3] text-xs mt-2">Public playlists can be viewed by anyone. Private playlists are only visible to you.</p>
                    @error('is_public')
                        <p class="text-[#e96c4c] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex items-center gap-3 pt-4">
                    <button
                        type="submit"
                        class="bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white px-6 py-2 text-xs font-normal uppercase tracking-wider transition"
                    >
                        Update Playlist
                    </button>
                    <a
                        href="{{ route('playlists.show', $playlist->slug) }}"
                        class="text-[#53a1b3] hover:text-white text-xs font-normal uppercase tracking-wider transition"
                    >
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

</div>

<script>
    // Cover image preview
    document.getElementById('cover').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('cover-preview').classList.remove('hidden');
                document.getElementById('cover-preview-img').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
