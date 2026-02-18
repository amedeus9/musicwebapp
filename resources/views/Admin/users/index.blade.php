@extends('Admin.layouts.app')

@section('page-title', 'Users Management')

@section('content')
<div class="flex flex-col gap-4">

    <!-- Header with Search -->
    <div class="flex flex-col md:flex-row gap-3 items-start md:items-center justify-between">
        <div>
            <h2 class="text-white font-normal text-xl uppercase tracking-wider">Users Management</h2>
            <p class="text-[#53a1b3] text-sm">Total: {{ $users->total() }} users</p>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2 w-full md:w-auto">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search users..."
                class="flex-1 md:w-64 bg-[#213042] text-white px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#e96c4c]"
            >
            <button type="submit" class="bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white px-4 py-2 text-sm uppercase tracking-wider transition">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('admin.users.index') }}" class="bg-[#213042] hover:bg-[#2a3d4f] text-[#53a1b3] px-4 py-2 text-sm uppercase tracking-wider transition">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-[#213042] border border-[#53a1b3]/10 overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#141e24]">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-normal text-[#53a1b3] uppercase tracking-wider">User</th>
                        <th class="px-4 py-3 text-left text-xs font-normal text-[#53a1b3] uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-normal text-[#53a1b3] uppercase tracking-wider">Songs</th>
                        <th class="px-4 py-3 text-left text-xs font-normal text-[#53a1b3] uppercase tracking-wider">Playlists</th>
                        <th class="px-4 py-3 text-left text-xs font-normal text-[#53a1b3] uppercase tracking-wider">Joined</th>
                        <th class="px-4 py-3 text-right text-xs font-normal text-[#53a1b3] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#53a1b3]/10">
                    @forelse($users as $user)
                    <tr class="hover:bg-[#1a2834] transition">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#e96c4c] flex items-center justify-center text-white font-normal">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-white text-sm font-normal">{{ $user->name }}</p>
                                    @if($user->id === auth()->id())
                                        <span class="text-[#e96c4c] text-xs uppercase">You</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-[#53a1b3] text-sm">{{ $user->email }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-[#53a1b3] text-sm">{{ $user->songs_count }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-[#53a1b3] text-sm">{{ $user->playlists_count }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-[#53a1b3] text-xs">{{ $user->created_at->format('M d, Y') }}</p>
                            <p class="text-[#53a1b3] text-[10px]">{{ $user->created_at->diffForHumans() }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                @if($user->id !== auth()->id())
                                    <form id="admin-del-user-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            data-confirm="Delete this user and all their content? This cannot be undone."
                                            data-confirm-title="Delete User"
                                            data-confirm-form="admin-del-user-{{ $user->id }}"
                                            class="text-[#53a1b3] hover:text-red-500 transition" title="Delete">
                                            <ion-icon name="trash-outline" class="w-5 h-5"></ion-icon>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[#53a1b3]/30 text-xs">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-[#53a1b3]">
                            No users found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden divide-y divide-[#53a1b3]/10">
            @forelse($users as $user)
            <div class="p-4">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-12 h-12 bg-[#e96c4c] flex items-center justify-center text-white font-normal text-lg flex-shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-white text-sm font-normal mb-1">
                            {{ $user->name }}
                            @if($user->id === auth()->id())
                                <span class="text-[#e96c4c] text-xs uppercase">(You)</span>
                            @endif
                        </h3>
                        <p class="text-[#53a1b3] text-xs truncate">{{ $user->email }}</p>
                        <div class="flex items-center gap-3 mt-1 text-xs text-[#53a1b3]">
                            <span>{{ $user->songs_count }} songs</span>
                            <span>•</span>
                            <span>{{ $user->playlists_count }} playlists</span>
                        </div>
                        <p class="text-[#53a1b3] text-xs mt-1">Joined {{ $user->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @if($user->id !== auth()->id())
                <form id="admin-del-user-mob-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                        data-confirm="Delete this user and all their content?"
                        data-confirm-title="Delete User"
                        data-confirm-form="admin-del-user-mob-{{ $user->id }}"
                        class="w-full bg-[#141e24] hover:bg-red-500 text-[#53a1b3] hover:text-white px-3 py-2 text-xs uppercase tracking-wider transition">
                        Delete User
                    </button>
                </form>
                @endif
            </div>
            @empty
            <div class="p-8 text-center text-[#53a1b3]">
                No users found
            </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="flex items-center justify-between">
        <p class="text-[#53a1b3] text-sm">
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
        </p>
        <div class="flex gap-2">
            @if($users->onFirstPage())
                <span class="px-3 py-2 bg-[#213042] text-[#53a1b3]/50 text-sm cursor-not-allowed">Previous</span>
            @else
                <a href="{{ $users->previousPageUrl() }}" class="px-3 py-2 bg-[#213042] hover:bg-[#2a3d4f] text-[#53a1b3] hover:text-white text-sm transition">Previous</a>
            @endif

            @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="px-3 py-2 bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white text-sm transition">Next</a>
            @else
                <span class="px-3 py-2 bg-[#213042] text-[#53a1b3]/50 text-sm cursor-not-allowed">Next</span>
            @endif
        </div>
    </div>
    @endif

</div>
@endsection
