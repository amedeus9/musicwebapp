@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col overflow-y-auto pb-24 gap-1">

    <!-- Header -->
    <div class="pb-2 flex items-center justify-between">
        <h3 class="font-normal text-white text-lg uppercase tracking-wider">Edit Playlist</h3>
    </div>

    <div>
        <div class="max-w-3xl">
            <form action="{{ route('playlists.update', $playlist->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-[10px] text-[#53a1b3]/50 uppercase tracking-widest mb-2">
                            Playlist Name <span class="text-[#e96c4c]">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name', $playlist->name) }}"
                            class="w-full bg-[#1a2730]/40 border border-[#53a1b3]/10 text-white text-[13px] px-4 py-2 h-[35px] focus:outline-none focus:border-[#e96c4c]/30 focus:ring-1 focus:ring-[#e96c4c]/10 rounded-[3px] transition-all placeholder-[#53a1b3]/20"
                            placeholder="Enter playlist name..."
                            required
                        >
                        @error('name')
                            <p class="text-[#e96c4c] text-[10px] mt-1 flex items-center gap-1">
                                <ion-icon name="alert-circle-outline"></ion-icon> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-[10px] text-[#53a1b3]/50 uppercase tracking-widest mb-2">
                            Description
                        </label>
                        <textarea
                            name="description"
                            id="description"
                            rows="4"
                            class="w-full bg-[#1a2730]/40 border border-[#53a1b3]/10 text-white text-[13px] px-4 py-3 focus:outline-none focus:border-[#e96c4c]/30 focus:ring-1 focus:ring-[#e96c4c]/10 rounded-[3px] transition-all placeholder-[#53a1b3]/20 resize-none leading-relaxed"
                            placeholder="Describe your playlist..."
                        >{{ old('description', $playlist->description) }}</textarea>
                        @error('description')
                            <p class="text-[#e96c4c] text-[10px] mt-1 flex items-center gap-1">
                                <ion-icon name="alert-circle-outline"></ion-icon> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Cover Image -->
                    <div>
                        <label class="block text-[10px] text-[#53a1b3]/50 uppercase tracking-widest mb-2">
                            Cover Image
                        </label>
                        
                        <div class="flex items-start gap-6">
                            <!-- Current/Preview -->
                            <div id="cover-preview" class="w-32 h-32 bg-[#141e24] shadow-lg rounded-[3px] overflow-hidden flex-shrink-0 relative group">
                                @if($playlist->cover_path)
                                    <img id="cover-preview-img" src="{{ Storage::url($playlist->cover_path) }}" alt="Cover preview" class="w-full h-full object-cover">
                                @else
                                    <div id="cover-placeholder" class="w-full h-full flex items-center justify-center text-[#53a1b3]/20">
                                        <ion-icon name="musical-notes-outline" class="w-12 h-12"></ion-icon>
                                    </div>
                                    <img id="cover-preview-img" src="" alt="Cover preview" class="w-full h-full object-cover hidden">
                                @endif
                                
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center pointer-events-none">
                                    <span class="text-white text-[10px] uppercase tracking-wider">Preview</span>
                                </div>
                            </div>

                            <!-- Upload Controls -->
                            <div class="flex-1">
                                <div class="mb-3">
                                    <label for="cover" class="inline-flex items-center gap-2 bg-[#1a2730] hover:bg-[#e96c4c] border border-[#53a1b3]/20 hover:border-[#e96c4c] text-white px-4 py-2 text-xs font-normal uppercase tracking-wider cursor-pointer transition rounded-[3px] shadow-sm">
                                        <ion-icon name="cloud-upload-outline" class="w-4 h-4"></ion-icon>
                                        <span>{{ $playlist->cover_path ? 'Change Cover' : 'Upload Cover' }}</span>
                                    </label>
                                    <input
                                        type="file"
                                        name="cover"
                                        id="cover"
                                        accept="image/*"
                                        class="hidden"
                                    >
                                </div>
                                
                                <p class="text-[#53a1b3]/60 text-[11px] leading-relaxed">
                                    Recommended size: 500x500 pixels.<br>
                                    Supported formats: JPG, PNG, GIF (Max 2MB).
                                </p>
                            </div>
                        </div>
                        @error('cover')
                            <p class="text-[#e96c4c] text-[10px] mt-1 flex items-center gap-1">
                                <ion-icon name="alert-circle-outline"></ion-icon> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Privacy -->
                    <div>
                        <label class="block text-[10px] text-[#53a1b3]/50 uppercase tracking-widest mb-3">
                            Privacy Settings
                        </label>
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="block text-white text-[13px] font-normal mb-1 transition" id="privacy-label">
                                    {{ old('is_public', $playlist->is_public) ? 'Public Playlist' : 'Private Playlist' }}
                                </span>
                                <span class="block text-[#53a1b3]/50 text-[11px] leading-tight" id="privacy-desc">
                                    {{ old('is_public', $playlist->is_public) ? 'Anyone can search for and view this playlist.' : 'Only you and invited collaborators can view this playlist.' }}
                                </span>
                            </div>

                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_public" value="0">
                                <input type="checkbox" name="is_public" value="1" class="sr-only peer" id="privacy-toggle" {{ old('is_public', $playlist->is_public) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-[#141e24] peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#e96c4c]/20 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-[#53a1b3] after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#e96c4c] peer-checked:after:bg-white peer-checked:after:border-white border border-[#53a1b3]/20"></div>
                            </label>
                        </div>

                        @error('is_public')
                            <p class="text-[#e96c4c] text-[10px] mt-1 flex items-center gap-1">
                                <ion-icon name="alert-circle-outline"></ion-icon> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <script>
                        document.getElementById('privacy-toggle').addEventListener('change', function() {
                            const label = document.getElementById('privacy-label');
                            const desc = document.getElementById('privacy-desc');
                            
                            if(this.checked) {
                                label.textContent = 'Public Playlist';
                                desc.textContent = 'Anyone can search for and view this playlist.';
                            } else {
                                label.textContent = 'Private Playlist';
                                desc.textContent = 'Only you and invited collaborators can view this playlist.';
                            }
                        });
                    </script>

                    <!-- Actions -->
                    <div class="pt-6 border-t border-[#53a1b3]/10 flex items-center gap-3">
                        <button
                            type="submit"
                            class="bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white px-4 py-2 text-xs font-normal uppercase tracking-wider rounded-[3px] transition shadow-lg shadow-[#e96c4c]/10 flex items-center gap-2"
                        >
                            <ion-icon name="save-outline" class="w-4 h-4"></ion-icon>
                            <span>Save Changes</span>
                        </button>
                        
                        <a
                            href="{{ route('playlists.show', $playlist->slug) }}"
                            class="text-[#53a1b3] hover:text-white px-4 py-2 text-xs font-normal uppercase tracking-wider rounded-[3px] transition"
                        >
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Danger Zone -->
        <div class="mt-8 max-w-3xl border border-red-500/20 bg-red-500/5 p-4 rounded-[3px]">
             <h4 class="text-red-500 text-[12px] uppercase tracking-wider font-normal mb-2 flex items-center gap-2">
                <ion-icon name="warning-outline"></ion-icon> Danger Zone
             </h4>
             <div class="flex items-center justify-between">
                 <p class="text-[#53a1b3]/60 text-[11px]">Once you delete a playlist, there is no going back. Please be certain.</p>
                 <form action="{{ route('playlists.destroy', $playlist->slug) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this playlist? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-white border border-red-500/30 hover:bg-red-500 px-4 py-2 text-xs font-normal uppercase tracking-wider rounded-[3px] transition">
                        Delete Playlist
                    </button>
                </form>
             </div>
        </div>
    </div>

</div>

<script>
    // Cover image preview
    const coverInput = document.getElementById('cover');
    const previewImg = document.getElementById('cover-preview-img');
    const placeholder = document.getElementById('cover-placeholder');

    coverInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                if(placeholder) placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
