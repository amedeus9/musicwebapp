@extends('Admin.layouts.app')

@section('page-title', 'Songs Management')

@section('content')
<div class="flex flex-col gap-4">

    <!-- Header with Search -->
    <div class="flex flex-col md:flex-row gap-3 items-start md:items-center justify-between">
        <div>
            <h2 class="text-white font-normal text-xl uppercase tracking-wider">Songs Management</h2>
            <p class="text-[#53a1b3] text-sm">Total: {{ $songs->total() }} songs</p>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.songs.index') }}" class="flex gap-2 w-full md:w-auto">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search songs..."
                class="flex-1 md:w-64 bg-[#213042] text-white px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#e96c4c]"
            >
            <button type="submit" class="bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white px-4 py-2 text-sm uppercase tracking-wider transition">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('admin.songs.index') }}" class="bg-[#213042] hover:bg-[#2a3d4f] text-[#53a1b3] px-4 py-2 text-sm uppercase tracking-wider transition">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Songs Table -->
    <div class="bg-[#213042] border border-[#53a1b3]/10 overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#141e24]">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-normal text-[#53a1b3] uppercase tracking-wider">Cover</th>
                        <th class="px-4 py-3 text-left text-xs font-normal text-[#53a1b3] uppercase tracking-wider">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-normal text-[#53a1b3] uppercase tracking-wider">Artist</th>
                        <th class="px-4 py-3 text-left text-xs font-normal text-[#53a1b3] uppercase tracking-wider">Album</th>
                        <th class="px-4 py-3 text-left text-xs font-normal text-[#53a1b3] uppercase tracking-wider">Uploader</th>
                        <th class="px-4 py-3 text-left text-xs font-normal text-[#53a1b3] uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-right text-xs font-normal text-[#53a1b3] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#53a1b3]/10">
                    @forelse($songs as $song)
                    <tr class="hover:bg-[#1a2834] transition">
                        <td class="px-4 py-3">
                            @if($song->cover_path)
                                <img src="{{ Storage::url($song->cover_path) }}" alt="{{ $song->title }}" class="w-12 h-12 object-cover">
                            @else
                                <div class="w-12 h-12 bg-[#141e24] flex items-center justify-center">
                                    <ion-icon name="musical-notes-outline" class="w-5 h-5 text-[#53a1b3]"></ion-icon>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-white text-sm font-normal">{{ $song->title }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-[#53a1b3] text-sm">{{ $song->artist }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-[#53a1b3] text-sm">{{ $song->album ?? '-' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-[#53a1b3] text-sm">{{ $song->user?->name ?? 'Unknown' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-[#53a1b3] text-xs">{{ $song->created_at->format('M d, Y') }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('songs.show', $song->slug) }}" target="_blank" class="text-[#53a1b3] hover:text-white transition" title="View">
                                    <ion-icon name="eye-outline" class="w-5 h-5"></ion-icon>
                                </a>
                                <a href="{{ route('admin.songs.edit', $song->id) }}" class="text-[#53a1b3] hover:text-[#e96c4c] transition" title="Edit">
                                    <ion-icon name="create-outline" class="w-5 h-5"></ion-icon>
                                </a>
                                <form action="{{ route('admin.songs.destroy', $song->id) }}" method="POST" onsubmit="return confirm('Delete this song? This action cannot be undone.');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[#53a1b3] hover:text-red-500 transition" title="Delete">
                                        <ion-icon name="trash-outline" class="w-5 h-5"></ion-icon>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-[#53a1b3]">
                            No songs found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden divide-y divide-[#53a1b3]/10">
            @forelse($songs as $song)
            <div class="p-4">
                <div class="flex items-start gap-3 mb-3">
                    @if($song->cover_path)
                        <img src="{{ Storage::url($song->cover_path) }}" alt="{{ $song->title }}" class="w-16 h-16 object-cover flex-shrink-0">
                    @else
                        <div class="w-16 h-16 bg-[#141e24] flex items-center justify-center flex-shrink-0">
                            <ion-icon name="musical-notes-outline" class="w-6 h-6 text-[#53a1b3]"></ion-icon>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h3 class="text-white text-sm font-normal mb-1">{{ $song->title }}</h3>
                        <p class="text-[#53a1b3] text-xs">{{ $song->artist }}</p>
                        <p class="text-[#53a1b3] text-xs">By: {{ $song->user?->name ?? 'Unknown' }}</p>
                        <p class="text-[#53a1b3] text-xs">{{ $song->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('songs.show', $song->slug) }}" target="_blank" class="flex-1 bg-[#141e24] hover:bg-[#1a2834] text-[#53a1b3] px-3 py-2 text-xs uppercase tracking-wider text-center transition">
                        View
                    </a>
                    <a href="{{ route('admin.songs.edit', $song->id) }}" class="flex-1 bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white px-3 py-2 text-xs uppercase tracking-wider text-center transition">
                        Edit
                    </a>
                    <form action="{{ route('admin.songs.destroy', $song->id) }}" method="POST" onsubmit="return confirm('Delete this song?');" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-[#141e24] hover:bg-red-500 text-[#53a1b3] hover:text-white px-3 py-2 text-xs uppercase tracking-wider transition">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-[#53a1b3]">
                No songs found
            </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($songs->hasPages())
    <div class="flex items-center justify-between">
        <p class="text-[#53a1b3] text-sm">
            Showing {{ $songs->firstItem() }} to {{ $songs->lastItem() }} of {{ $songs->total() }} songs
        </p>
        <div class="flex gap-2">
            @if($songs->onFirstPage())
                <span class="px-3 py-2 bg-[#213042] text-[#53a1b3]/50 text-sm cursor-not-allowed">Previous</span>
            @else
                <a href="{{ $songs->previousPageUrl() }}" class="px-3 py-2 bg-[#213042] hover:bg-[#2a3d4f] text-[#53a1b3] hover:text-white text-sm transition">Previous</a>
            @endif

            @if($songs->hasMorePages())
                <a href="{{ $songs->nextPageUrl() }}" class="px-3 py-2 bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white text-sm transition">Next</a>
            @else
                <span class="px-3 py-2 bg-[#213042] text-[#53a1b3]/50 text-sm cursor-not-allowed">Next</span>
            @endif
        </div>
    </div>
    @endif

</div>
@endsection
