@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col overflow-y-auto pb-24 gap-1">
    
    <!-- Header -->
    <div class="flex items-center gap-2 mb-6 px-8 pt-8">
        <div class="w-12 h-12 flex items-center justify-center bg-[#e96c4c]/10 rounded-[3px] border border-[#e96c4c]/20 shrink-0">
            <ion-icon name="person" class="w-6 h-6 text-[#e96c4c]"></ion-icon>
        </div>
        <div>
            <h1 class="text-lg font-normal text-white uppercase tracking-wider">Edit Profile</h1>
            <p class="text-[10px] text-[#53a1b3]/50 uppercase tracking-widest mt-0.5">Update your personal information</p>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="max-w-2xl px-8">
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            @if(session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-[3px] p-3 flex items-center gap-2">
                    <ion-icon name="checkmark-circle" class="w-4 h-4 text-emerald-500"></ion-icon>
                    <span class="text-emerald-500 text-[11px] uppercase tracking-wider">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Personal Info Section -->
            <div class="space-y-4">
                <h3 class="text-white text-xs font-normal uppercase tracking-widest border-b border-[#53a1b3]/10 pb-2">Personal Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Name -->
                    <div class="space-y-1.5">
                        <label for="name" class="text-[10px] text-[#53a1b3]/50 uppercase tracking-widest block">Full Name</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', auth()->user()->name) }}" 
                            class="w-full h-[35px] bg-[#1a2730]/40 border border-[#53a1b3]/10 text-white text-xs px-2 rounded-[3px] focus:outline-none focus:border-[#e96c4c]/30 transition placeholder-[#53a1b3]/20"
                            required
                        >
                        @error('name')
                            <span class="text-red-400 text-[10px]">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="space-y-1.5">
                        <label for="email" class="text-[10px] text-[#53a1b3]/50 uppercase tracking-widest block">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', auth()->user()->email) }}" 
                            class="w-full h-[35px] bg-[#1a2730]/40 border border-[#53a1b3]/10 text-white text-xs px-2 rounded-[3px] focus:outline-none focus:border-[#e96c4c]/30 transition placeholder-[#53a1b3]/20"
                            required
                        >
                        @error('email')
                            <span class="text-red-400 text-[10px]">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Password Section -->
            <div class="space-y-4">
                <h3 class="text-white text-xs font-normal uppercase tracking-widest border-b border-[#53a1b3]/10 pb-2">Change Password <span class="text-[#53a1b3]/30 normal-case text-[9px] ml-2">(Optional)</span></h3>

                <div class="space-y-4">
                    <!-- Current Password -->
                    <div class="space-y-1.5">
                        <label for="current_password" class="text-[10px] text-[#53a1b3]/50 uppercase tracking-widest block">Current Password <span class="text-[#e96c4c]">*</span></label>
                        <input 
                            type="password" 
                            id="current_password" 
                            name="current_password" 
                            class="w-full h-[35px] bg-[#1a2730]/40 border border-[#53a1b3]/10 text-white text-xs px-2 rounded-[3px] focus:outline-none focus:border-[#e96c4c]/30 transition placeholder-[#53a1b3]/20"
                            placeholder="Required to save changes"
                        >
                        @error('current_password')
                            <span class="text-red-400 text-[10px]">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- New Password -->
                        <div class="space-y-1.5">
                            <label for="password" class="text-[10px] text-[#53a1b3]/50 uppercase tracking-widest block">New Password</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="w-full h-[35px] bg-[#1a2730]/40 border border-[#53a1b3]/10 text-white text-xs px-2 rounded-[3px] focus:outline-none focus:border-[#e96c4c]/30 transition placeholder-[#53a1b3]/20"
                                placeholder="Leave blank to keep current"
                            >
                            @error('password')
                                <span class="text-red-400 text-[10px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-1.5">
                            <label for="password_confirmation" class="text-[10px] text-[#53a1b3]/50 uppercase tracking-widest block">Confirm New Password</label>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                class="w-full h-[35px] bg-[#1a2730]/40 border border-[#53a1b3]/10 text-white text-xs px-2 rounded-[3px] focus:outline-none focus:border-[#e96c4c]/30 transition placeholder-[#53a1b3]/20"
                                placeholder="Retype new password"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3 pt-4 border-t border-[#53a1b3]/10">
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white text-xs font-normal uppercase tracking-widest rounded-[3px] transition shadow-lg hover:shadow-[#e96c4c]/20"
                >
                    Save Changes
                </button>
                <a 
                    href="{{ route('profile') }}" 
                    class="px-6 py-2 bg-transparent border border-[#53a1b3]/20 text-[#53a1b3] hover:text-white hover:border-[#53a1b3]/50 text-xs font-normal uppercase tracking-widest rounded-[3px] transition"
                >
                     Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
