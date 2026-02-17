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
                <h1 class="text-2xl font-normal text-white uppercase tracking-tighter leading-none mb-1">Welcome Back</h1>
                <p class="text-[10px] text-[#53a1b3] uppercase tracking-widest font-normal">Login to your premium account</p>
            </div>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email -->
            <div class="space-y-2">
                <label for="email" class="text-[10px] text-[#53a1b3] uppercase tracking-widest font-normal px-1">Email Address</label>
                <div class="relative">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                           class="w-full bg-[#1a2730] border border-[#53a1b3]/20 px-4 py-3 text-white text-sm focus:outline-none focus:border-[#e96c4c]/50 transition @error('email') border-red-500/50 @enderror"
                           placeholder="Enter your email">
                    @error('email')
                        <span class="text-[10px] text-red-500 mt-1 block uppercase tracking-wider">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <div class="flex items-center justify-between px-1">
                    <label for="password" class="text-[10px] text-[#53a1b3] uppercase tracking-widest font-normal">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-[10px] text-[#e96c4c] uppercase tracking-widest hover:underline">Forgot?</a>
                    @endif
                </div>
                <div class="relative">
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="w-full bg-[#1a2730] border border-[#53a1b3]/20 px-4 py-3 text-white text-sm focus:outline-none focus:border-[#e96c4c]/50 transition @error('password') border-red-500/50 @enderror"
                           placeholder="Enter your password">
                    @error('password')
                        <span class="text-[10px] text-red-500 mt-1 block uppercase tracking-wider">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center gap-2 px-1">
                <input class="w-4 h-4 rounded-none bg-[#1a2730] border-[#53a1b3]/20 text-[#e96c4c] focus:ring-0 focus:ring-offset-0" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="text-[10px] text-[#53a1b3] uppercase tracking-widest cursor-pointer" for="remember">
                    Remember Me
                </label>
            </div>

            <!-- Actions -->
            <div class="space-y-4 pt-2">
                <button type="submit" class="w-full bg-[#e96c4c] text-white py-4 text-xs font-normal uppercase tracking-[0.2em] shadow-lg shadow-[#e96c4c]/20 hover:scale-[1.02] active:scale-95 transition">
                    Sign In
                </button>

                <p class="text-center text-[10px] text-[#53a1b3] uppercase tracking-widest">
                    Don't have an account? <a href="{{ route('register') }}" class="text-[#e96c4c] hover:underline font-normal">Register</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
