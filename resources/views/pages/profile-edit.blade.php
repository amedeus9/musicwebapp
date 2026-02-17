@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col overflow-y-auto pb-24 gap-1">
    
    <!-- Header -->
    <div class="border-b border-[#53a1b3]/10 pb-2">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('profile') }}" class="w-8 h-8 border border-[#53a1b3]/20 flex items-center justify-center text-[#53a1b3] hover:text-white hover:border-[#e96c4c] transition">
                <ion-icon name="arrow-back-outline" class="w-4 h-4"></ion-icon>
            </a>
            <h1 class="text-white font-normal text-xl uppercase tracking-wider">Edit Profile</h1>
        </div>
        <p class="text-[#53a1b3] text-sm pl-10">Update your profile information</p>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('profile.update') }}" method="POST" class="flex flex-col gap-4 pt-2">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div class="flex flex-col gap-2">
            <label for="name" class="text-[#53a1b3] text-xs font-normal uppercase tracking-wider">Full Name</label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                value="{{ old('name', auth()->user()->name) }}" 
                class="bg-[#0f1319] border border-[#53a1b3]/20 text-white px-3 py-2 text-sm focus:outline-none focus:border-[#e96c4c]/50 focus:ring-1 focus:ring-[#e96c4c]/50 transition"
                required
            >
            @error('name')
                <span class="text-red-400 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email -->
        <div class="flex flex-col gap-2">
            <label for="email" class="text-[#53a1b3] text-xs font-normal uppercase tracking-wider">Email Address</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="{{ old('email', auth()->user()->email) }}" 
                class="bg-[#0f1319] border border-[#53a1b3]/20 text-white px-3 py-2 text-sm focus:outline-none focus:border-[#e96c4c]/50 focus:ring-1 focus:ring-[#e96c4c]/50 transition"
                required
            >
            @error('email')
                <span class="text-red-400 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <!-- Divider -->
        <div class="border-t border-[#53a1b3]/10 my-2"></div>

        <!-- Current Password (required for changes) -->
        <div class="flex flex-col gap-2">
            <label for="current_password" class="text-[#53a1b3] text-xs font-normal uppercase tracking-wider">Current Password</label>
            <input 
                type="password" 
                id="current_password" 
                name="current_password" 
                class="bg-[#0f1319] border border-[#53a1b3]/20 text-white px-3 py-2 text-sm focus:outline-none focus:border-[#e96c4c]/50 focus:ring-1 focus:ring-[#e96c4c]/50 transition"
                placeholder="Enter current password to confirm"
            >
            @error('current_password')
                <span class="text-red-400 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <!-- New Password (optional) -->
        <div class="flex flex-col gap-2">
            <label for="password" class="text-[#53a1b3] text-xs font-normal uppercase tracking-wider">New Password</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                class="bg-[#0f1319] border border-[#53a1b3]/20 text-white px-3 py-2 text-sm focus:outline-none focus:border-[#e96c4c]/50 focus:ring-1 focus:ring-[#e96c4c]/50 transition"
                placeholder="Leave blank to keep current"
            >
            @error('password')
                <span class="text-red-400 text-xs">{{ $message }}</span>
            @enderror
            <p class="text-[#53a1b3] text-[10px]">Leave blank if you don't want to change your password</p>
        </div>

        <!-- Confirm New Password -->
        <div class="flex flex-col gap-2">
            <label for="password_confirmation" class="text-[#53a1b3] text-xs font-normal uppercase tracking-wider">Confirm New Password</label>
            <input 
                type="password" 
                id="password_confirmation" 
                name="password_confirmation" 
                class="bg-[#0f1319] border border-[#53a1b3]/20 text-white px-3 py-2 text-sm focus:outline-none focus:border-[#e96c4c]/50 focus:ring-1 focus:ring-[#e96c4c]/50 transition"
                placeholder="Confirm new password"
            >
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-2 mt-4">
            <button 
                type="submit" 
                class="flex-1 bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white px-4 py-2 text-sm font-normal uppercase tracking-wider transition"
            >
                Save Changes
            </button>
            <a 
                href="{{ route('profile') }}" 
                class="flex-1 bg-[#0f1319] border border-[#53a1b3]/20 text-[#53a1b3] hover:text-white hover:border-[#e96c4c] px-4 py-2 text-sm font-normal uppercase tracking-wider transition text-center"
            >
                Cancel
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 px-3 py-2 text-sm font-normal flex items-center gap-2">
                <ion-icon name="checkmark-outline" class="w-4 h-4"></ion-icon>
                {{ session('success') }}
            </div>
        @endif
    </form>

</div>
@endsection
