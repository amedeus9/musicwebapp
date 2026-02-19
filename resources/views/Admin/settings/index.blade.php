@extends('Admin.layouts.app')

@section('page-title', 'System Settings')

@section('content')
<div class="flex flex-col gap-6">

    <!-- Header -->
    <div class="flex items-center justify-between border-b border-[#53a1b3]/10 pb-4">
        <div>
            <h1 class="text-white font-normal text-2xl uppercase tracking-wider">Settings</h1>
            <p class="text-[#53a1b3] text-sm mt-1">Configure global application settings</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 px-4 py-3 rounded-[3px] flex items-center gap-2">
            <ion-icon name="checkmark-circle" class="w-5 h-5"></ion-icon>
            <span class="text-sm font-medium uppercase tracking-wide">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Settings Form -->
    <div class="bg-[#213042] border border-[#53a1b3]/10 rounded-[3px] p-6 shadow-xl shadow-black/20">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Section: General -->
            <div class="space-y-4">
                <h3 class="text-[#e96c4c] text-xs font-bold uppercase tracking-widest border-b border-[#53a1b3]/10 pb-2 flex items-center gap-2">
                    <ion-icon name="globe-outline" class="w-4 h-4"></ion-icon> 
                    General Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Site Name -->
                    <div class="space-y-2">
                        <label class="text-[#53a1b3] text-xs uppercase tracking-wider font-medium">Site Name</label>
                        <input type="text" name="site_name" value="{{ $settings['site_name'] ?? config('app.name') }}" 
                            class="w-full bg-[#141e24] border border-[#53a1b3]/20 text-white px-3 py-2.5 text-sm rounded-[3px] focus:outline-none focus:border-[#e96c4c] focus:ring-1 focus:ring-[#e96c4c]/50 transition placeholder-[#53a1b3]/30">
                    </div>

                    <!-- Support Email -->
                    <div class="space-y-2">
                        <label class="text-[#53a1b3] text-xs uppercase tracking-wider font-medium">Support Email</label>
                        <input type="email" name="site_email" value="{{ $settings['site_email'] ?? 'support@example.com' }}" 
                            class="w-full bg-[#141e24] border border-[#53a1b3]/20 text-white px-3 py-2.5 text-sm rounded-[3px] focus:outline-none focus:border-[#e96c4c] transition placeholder-[#53a1b3]/30">
                    </div>

                    <!-- Footer Text -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[#53a1b3] text-xs uppercase tracking-wider font-medium">Footer Copyright Text</label>
                        <input type="text" name="footer_text" value="{{ $settings['footer_text'] ?? '© 2026 MusicApp. All rights reserved.' }}" 
                            class="w-full bg-[#141e24] border border-[#53a1b3]/20 text-white px-3 py-2.5 text-sm rounded-[3px] focus:outline-none focus:border-[#e96c4c] transition placeholder-[#53a1b3]/30">
                    </div>
                </div>
            </div>

            <!-- Section: SEO -->
            <div class="space-y-4 pt-2">
                <h3 class="text-[#e96c4c] text-xs font-bold uppercase tracking-widest border-b border-[#53a1b3]/10 pb-2 flex items-center gap-2">
                    <ion-icon name="search-outline" class="w-4 h-4"></ion-icon> 
                    SEO & Meta
                </h3>
                
                <div class="space-y-2">
                    <label class="text-[#53a1b3] text-xs uppercase tracking-wider font-medium">Meta Description</label>
                    <textarea name="site_description" rows="3" 
                        class="w-full bg-[#141e24] border border-[#53a1b3]/20 text-white px-3 py-2.5 text-sm rounded-[3px] focus:outline-none focus:border-[#e96c4c] transition placeholder-[#53a1b3]/30">{{ $settings['site_description'] ?? '' }}</textarea>
                    <p class="text-[10px] text-[#53a1b3]/50">Brief description of your site for search engines.</p>
                </div>
            </div>

            <!-- Section: Branding -->
            <div class="space-y-4 pt-2">
                <h3 class="text-[#e96c4c] text-xs font-bold uppercase tracking-widest border-b border-[#53a1b3]/10 pb-2 flex items-center gap-2">
                    <ion-icon name="image-outline" class="w-4 h-4"></ion-icon> 
                    Branding
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Logo -->
                    <div class="bg-[#141e24] p-4 rounded-[3px] border border-[#53a1b3]/10">
                        <label class="text-[#53a1b3] text-xs uppercase tracking-wider font-medium block mb-2">Website Logo</label>
                        <input type="file" name="logo" class="block w-full text-xs text-[#53a1b3] file:mr-4 file:py-2 file:px-4 file:rounded-[3px] file:border-0 file:text-xs file:font-semibold file:bg-[#e96c4c] file:text-white hover:file:bg-[#e96c4c]/90 cursor-pointer">
                        @if(isset($settings['site_logo']))
                            <div class="mt-4 p-2 bg-[#213042] inline-block rounded border border-[#53a1b3]/20">
                                <img src="{{ Storage::url($settings['site_logo']) }}" alt="Current Logo" class="h-8 w-auto object-contain">
                            </div>
                            <p class="text-[10px] text-[#53a1b3]/50 mt-1">Current Logo</p>
                        @endif
                    </div>

                    <!-- Favicon -->
                    <div class="bg-[#141e24] p-4 rounded-[3px] border border-[#53a1b3]/10">
                        <label class="text-[#53a1b3] text-xs uppercase tracking-wider font-medium block mb-2">Favicon</label>
                        <input type="file" name="favicon" class="block w-full text-xs text-[#53a1b3] file:mr-4 file:py-2 file:px-4 file:rounded-[3px] file:border-0 file:text-xs file:font-semibold file:bg-[#e96c4c] file:text-white hover:file:bg-[#e96c4c]/90 cursor-pointer">
                        @if(isset($settings['site_favicon']))
                            <div class="mt-4 p-2 bg-[#213042] inline-block rounded border border-[#53a1b3]/20">
                                <img src="{{ Storage::url($settings['site_favicon']) }}" alt="Favicon" class="h-8 w-8 object-contain">
                            </div>
                            <p class="text-[10px] text-[#53a1b3]/50 mt-1">Current Favicon</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Section: System -->
            <div class="space-y-4 pt-2">
                <h3 class="text-[#e96c4c] text-xs font-bold uppercase tracking-widest border-b border-[#53a1b3]/10 pb-2 flex items-center gap-2">
                    <ion-icon name="settings-outline" class="w-4 h-4"></ion-icon> 
                    System Configuration
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Registration Toggle -->
                    <label class="flex items-center gap-3 p-3 bg-[#141e24] border border-[#53a1b3]/10 rounded-[3px] cursor-pointer hover:border-[#53a1b3]/30 transition group">
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="registration_enabled" value="1" class="sr-only peer" {{ ($settings['registration_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-[#53a1b3]/20 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#e96c4c]"></div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-white text-sm font-medium group-hover:text-[#e96c4c] transition">Allow User Registration</span>
                            <span class="text-[10px] text-[#53a1b3]/50">If disabled, new users cannot sign up.</span>
                        </div>
                    </label>

                    <!-- Maintenance Mode Toggle -->
                    <label class="flex items-center gap-3 p-3 bg-[#141e24] border border-[#53a1b3]/10 rounded-[3px] cursor-pointer hover:border-[#53a1b3]/30 transition group">
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer" {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-[#53a1b3]/20 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#e96c4c]"></div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-white text-sm font-medium group-hover:text-[#e96c4c] transition">Maintenance Mode</span>
                            <span class="text-[10px] text-[#53a1b3]/50">Put the site offline for updates.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Section: Audio Configuration -->
            <div class="space-y-4 pt-2">
                <h3 class="text-[#e96c4c] text-xs font-bold uppercase tracking-widest border-b border-[#53a1b3]/10 pb-2 flex items-center gap-2">
                    <ion-icon name="musical-notes-outline" class="w-4 h-4"></ion-icon> 
                    Audio Metadata Configuration
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Default Artist -->
                    <div class="space-y-2">
                        <label class="text-[#53a1b3] text-xs uppercase tracking-wider font-medium">Default Artist</label>
                        <input type="text" name="audio_default_artist" value="{{ $settings['audio_default_artist'] ?? 'Unknown Artist' }}" 
                            class="w-full bg-[#141e24] border border-[#53a1b3]/20 text-white px-3 py-2.5 text-sm rounded-[3px] focus:outline-none focus:border-[#e96c4c] transition placeholder-[#53a1b3]/30">
                        <p class="text-[10px] text-[#53a1b3]/50">Fallback if artist is missing.</p>
                    </div>

                    <!-- Default Album -->
                    <div class="space-y-2">
                        <label class="text-[#53a1b3] text-xs uppercase tracking-wider font-medium">Default Album Name</label>
                        <input type="text" name="audio_default_album" value="{{ $settings['audio_default_album'] ?? config('app.name') . ' Uploads' }}" 
                            class="w-full bg-[#141e24] border border-[#53a1b3]/20 text-white px-3 py-2.5 text-sm rounded-[3px] focus:outline-none focus:border-[#e96c4c] transition placeholder-[#53a1b3]/30">
                    </div>

                    <!-- Default Genre -->
                    <div class="space-y-2">
                        <label class="text-[#53a1b3] text-xs uppercase tracking-wider font-medium">Default Genre</label>
                        <input type="text" name="audio_default_genre" value="{{ $settings['audio_default_genre'] ?? 'Unknown' }}" 
                            class="w-full bg-[#141e24] border border-[#53a1b3]/20 text-white px-3 py-2.5 text-sm rounded-[3px] focus:outline-none focus:border-[#e96c4c] transition placeholder-[#53a1b3]/30">
                    </div>

                    <!-- Default Copyright -->
                    <div class="space-y-2">
                        <label class="text-[#53a1b3] text-xs uppercase tracking-wider font-medium">Default Copyright</label>
                        <input type="text" name="audio_default_copyright" value="{{ $settings['audio_default_copyright'] ?? '© ' . date('Y') . ' ' . config('app.name') }}" 
                            class="w-full bg-[#141e24] border border-[#53a1b3]/20 text-white px-3 py-2.5 text-sm rounded-[3px] focus:outline-none focus:border-[#e96c4c] transition placeholder-[#53a1b3]/30">
                    </div>

                    <!-- Default Comment -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[#53a1b3] text-xs uppercase tracking-wider font-medium">Default Comment / Description</label>
                        <textarea name="audio_default_comment" rows="2" 
                            class="w-full bg-[#141e24] border border-[#53a1b3]/20 text-white px-3 py-2.5 text-sm rounded-[3px] focus:outline-none focus:border-[#e96c4c] transition placeholder-[#53a1b3]/30">{{ $settings['audio_default_comment'] ?? 'Downloaded from ' . config('app.url') }}</textarea>
                        <p class="text-[10px] text-[#53a1b3]/50">This text will be embedded in the MP3 comments.</p>
                    </div>

                    <!-- Default Cover Upload -->
                    <div class="md:col-span-2 bg-[#141e24] p-4 rounded-[3px] border border-[#53a1b3]/10">
                        <label class="text-[#53a1b3] text-xs uppercase tracking-wider font-medium block mb-2">Default Audio Cover Art</label>
                        <input type="file" name="audio_default_cover" class="block w-full text-xs text-[#53a1b3] file:mr-4 file:py-2 file:px-4 file:rounded-[3px] file:border-0 file:text-xs file:font-semibold file:bg-[#e96c4c] file:text-white hover:file:bg-[#e96c4c]/90 cursor-pointer">
                        <p class="text-[10px] text-[#53a1b3]/50 mt-2">Used when no cover is provided. If blank, will try to use Site Logo if enabled below.</p>
                        
                        @if(isset($settings['audio_default_cover']))
                            <div class="mt-4 p-2 bg-[#213042] inline-block rounded border border-[#53a1b3]/20">
                                <img src="{{ Storage::url($settings['audio_default_cover']) }}" alt="Default Audio Cover" class="h-24 w-24 object-cover">
                            </div>
                            <p class="text-[10px] text-[#53a1b3]/50 mt-1">Current Default Cover</p>
                        @endif
                    </div>

                    <!-- Force Site Cover -->
                    <label class="flex items-center gap-3 p-3 bg-[#141e24] border border-[#53a1b3]/10 rounded-[3px] cursor-pointer hover:border-[#53a1b3]/30 transition group">
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="audio_force_site_cover" value="1" class="sr-only peer" {{ ($settings['audio_force_site_cover'] ?? '0') == '1' ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-[#53a1b3]/20 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#e96c4c]"></div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-white text-sm font-medium group-hover:text-[#e96c4c] transition">Fallback to Site Logo</span>
                            <span class="text-[10px] text-[#53a1b3]/50">Use Site Logo if Default Cover is also missing.</span>
                        </div>
                    </label>

                     <!-- Remove ID3v1 -->
                     <label class="flex items-center gap-3 p-3 bg-[#141e24] border border-[#53a1b3]/10 rounded-[3px] cursor-pointer hover:border-[#53a1b3]/30 transition group">
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="audio_remove_id3v1" value="1" class="sr-only peer" {{ ($settings['audio_remove_id3v1'] ?? '0') == '1' ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-[#53a1b3]/20 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#e96c4c]"></div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-white text-sm font-medium group-hover:text-[#e96c4c] transition">Remove ID3v1 Tags</span>
                            <span class="text-[10px] text-[#53a1b3]/50">Keep only ID3v2 for cleaner metadata.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-6 border-t border-[#53a1b3]/10 flex justify-end">
                <button type="submit" class="bg-[#e96c4c] hover:bg-[#d45a3a] text-white px-8 py-3 text-sm font-medium uppercase tracking-widest rounded-[3px] transition shadow-lg shadow-[#e96c4c]/20 flex items-center gap-2">
                    <ion-icon name="save-outline" class="w-4 h-4"></ion-icon>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
