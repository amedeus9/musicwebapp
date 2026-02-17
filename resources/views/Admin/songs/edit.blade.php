@extends('Admin.layouts.app')

@section('page-title', 'Edit Song')

@section('content')
<div class="flex flex-col gap-4">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-white font-normal text-xl uppercase tracking-wider">Edit Song</h2>
            <p class="text-[#53a1b3] text-sm">Update song information</p>
        </div>
        <a href="{{ route('admin.songs.index') }}" class="text-[#53a1b3] hover:text-white text-sm uppercase tracking-wider transition flex items-center gap-2">
            <ion-icon name="arrow-back-outline" class="w-4 h-4"></ion-icon>
            <span>Back to Songs</span>
        </a>
    </div>

    <!-- Edit Form -->
    <div class="bg-[#213042] p-6 border border-[#53a1b3]/10">
        <form action="{{ route('admin.songs.update', $song->id) }}" method="POST" enctype="multipart/form-data" class="max-w-2xl">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <!-- Current Cover -->
                @if($song->cover_path)
                <div>
                    <label class="block text-[#53a1b3] text-sm font-normal uppercase tracking-wider mb-2">
                        Current Cover
                    </label>
                    <img src="{{ Storage::url($song->cover_path) }}" alt="{{ $song->title }}" class="w-32 h-32 object-cover">
                </div>
                @endif

                <!-- Title -->
                <div>
                    <label for="title" class="block text-[#53a1b3] text-sm font-normal uppercase tracking-wider mb-2">
                        Title <span class="text-[#e96c4c]">*</span>
                    </label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        value="{{ old('title', $song->title) }}"
                        class="w-full bg-[#141e24] text-white px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#e96c4c] text-sm"
                        required
                    >
                    @error('title')
                        <p class="text-[#e96c4c] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Artist -->
                <div>
                    <label for="artist" class="block text-[#53a1b3] text-sm font-normal uppercase tracking-wider mb-2">
                        Artist <span class="text-[#e96c4c]">*</span>
                    </label>
                    <input
                        type="text"
                        name="artist"
                        id="artist"
                        value="{{ old('artist', $song->artist) }}"
                        class="w-full bg-[#141e24] text-white px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#e96c4c] text-sm"
                        required
                    >
                    @error('artist')
                        <p class="text-[#e96c4c] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Album -->
                <div>
                    <label for="album" class="block text-[#53a1b3] text-sm font-normal uppercase tracking-wider mb-2">
                        Album
                    </label>
                    <input
                        type="text"
                        name="album"
                        id="album"
                        value="{{ old('album', $song->album) }}"
                        class="w-full bg-[#141e24] text-white px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#e96c4c] text-sm"
                    >
                    @error('album')
                        <p class="text-[#e96c4c] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Genre -->
                <div>
                    <label for="genre" class="block text-[#53a1b3] text-sm font-normal uppercase tracking-wider mb-2">
                        Genre
                    </label>
                    <input
                        type="text"
                        name="genre"
                        id="genre"
                        value="{{ old('genre', $song->genre) }}"
                        class="w-full bg-[#141e24] text-white px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#e96c4c] text-sm"
                    >
                    @error('genre')
                        <p class="text-[#e96c4c] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Cover Upload -->
                <div>
                    <label for="cover" class="block text-[#53a1b3] text-sm font-normal uppercase tracking-wider mb-2">
                        Update Cover Image
                    </label>
                    <div class="flex items-start gap-4">
                        <!-- Preview -->
                        <div id="cover-preview" class="hidden w-24 h-24 bg-[#141e24] overflow-hidden">
                            <img id="cover-preview-img" src="" alt="Cover preview" class="w-full h-full object-cover">
                        </div>

                        <!-- Upload button -->
                        <div class="flex-1">
                            <label for="cover" class="inline-block bg-[#141e24] hover:bg-[#1a2834] text-[#53a1b3] px-4 py-2 text-xs font-normal uppercase tracking-wider cursor-pointer transition">
                                Choose New Cover
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

                <!-- Song Info -->
                <div class="bg-[#141e24] p-4 border-l-2 border-[#e96c4c]">
                    <h4 class="text-white text-sm font-normal mb-2">Song Information</h4>
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div>
                            <span class="text-[#53a1b3]">Uploaded by:</span>
                            <span class="text-white">{{ $song->user->name }}</span>
                        </div>
                        <div>
                            <span class="text-[#53a1b3]">Upload date:</span>
                            <span class="text-white">{{ $song->created_at->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-[#53a1b3]">Slug:</span>
                            <span class="text-white">{{ $song->slug }}</span>
                        </div>
                        <div>
                            <span class="text-[#53a1b3]">Downloads:</span>
                            <span class="text-white">{{ number_format($song->downloads) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center gap-3 pt-4">
                    <button
                        type="submit"
                        class="bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white px-6 py-2 text-xs font-normal uppercase tracking-wider transition"
                    >
                        Update Song
                    </button>
                    <a
                        href="{{ route('admin.songs.index') }}"
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
