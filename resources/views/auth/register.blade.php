@extends('layouts.guest')

@section('content')
<div class="flex-1 flex flex-col items-center justify-center px-4 py-12">
    <div class="w-full max-w-md bg-[#141e24] border border-[#53a1b3]/20 p-8 shadow-2xl relative">
        <!-- Logo/Header -->
        <div class="flex flex-col items-center gap-4 mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-[#e96c4c] to-[#d45a3a] flex items-center justify-center text-white shadow-xl shadow-[#e96c4c]/20">
                <ion-icon name="musical-notes-outline" class="w-8 h-8"></ion-icon>
            </div>
            <div class="text-center">
                <h1 class="text-2xl font-normal text-white uppercase tracking-tighter leading-none mb-1">Create Account</h1>
                <p class="text-[10px] text-[#53a1b3] uppercase tracking-widest font-normal">Join the premium music community</p>
            </div>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div class="space-y-1">
                <label for="name" class="text-[10px] text-[#53a1b3] uppercase tracking-widest font-normal px-1">Full Name</label>
                <div class="relative">
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                           class="w-full bg-[#1a2730] border border-[#53a1b3]/20 px-4 py-3 text-white text-sm focus:outline-none focus:border-[#e96c4c]/50 transition @error('name') border-red-500/50 @enderror"
                           placeholder="Enter your name">
                    @error('name')
                        <span class="text-[10px] text-red-500 mt-1 block uppercase tracking-wider">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Email -->
            <div class="space-y-1">
                <label for="email" class="text-[10px] text-[#53a1b3] uppercase tracking-widest font-normal px-1">Email Address</label>
                <div class="relative">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                           class="w-full bg-[#1a2730] border border-[#53a1b3]/20 px-4 py-3 text-white text-sm focus:outline-none focus:border-[#e96c4c]/50 transition @error('email') border-red-500/50 @enderror"
                           placeholder="Enter your email">
                    @error('email')
                        <span class="text-[10px] text-red-500 mt-1 block uppercase tracking-wider">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Password -->
            <div class="space-y-1">
                <label for="password" class="text-[10px] text-[#53a1b3] uppercase tracking-widest font-normal px-1">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           class="w-full bg-[#1a2730] border border-[#53a1b3]/20 px-4 py-3 text-white text-sm focus:outline-none focus:border-[#e96c4c]/50 transition @error('password') border-red-500/50 @enderror"
                           placeholder="Choose a password">
                    @error('password')
                        <span class="text-[10px] text-red-500 mt-1 block uppercase tracking-wider">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="space-y-1">
                <label for="password-confirm" class="text-[10px] text-[#53a1b3] uppercase tracking-widest font-normal px-1">Confirm Password</label>
                <div class="relative">
                    <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                           class="w-full bg-[#1a2730] border border-[#53a1b3]/20 px-4 py-3 text-white text-sm focus:outline-none focus:border-[#e96c4c]/50 transition"
                           placeholder="Confirm your password">
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-4 pt-4">
                <button type="submit" class="w-full bg-[#e96c4c] text-white py-4 text-xs font-normal uppercase tracking-[0.2em] shadow-lg shadow-[#e96c4c]/20 hover:scale-[1.02] active:scale-95 transition">
                    Create Account
                </button>

                <p class="text-center text-[10px] text-[#53a1b3] uppercase tracking-widest">
                    Already have an account? <a href="{{ route('login') }}" class="text-[#e96c4c] hover:underline font-normal">Login</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
