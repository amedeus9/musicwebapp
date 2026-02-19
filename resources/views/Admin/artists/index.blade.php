@extends('Admin.layouts.app')

@section('page-title', 'Artists Management')

@section('content')
<div class="flex flex-col gap-4">

    <!-- Header -->
    <div class="flex flex-col md:flex-row gap-3 items-start md:items-center justify-between">
        <div>
            <h2 class="text-white font-normal text-xl uppercase tracking-wider">Artists Management</h2>
            <p class="text-[#53a1b3] text-sm">Total: {{ $artists->total() }} artists</p>
        </div>
        
        <div class="flex gap-3">
             <a href="{{ route('admin.artists.create') }}" class="bg-[#e96c4c] hover:bg-[#e96c4c]/90 text-white px-4 py-2 text-sm uppercase tracking-wider transition flex items-center gap-2 h-full">
                <ion-icon name="add-circle-outline" class="w-5 h-5"></ion-icon>
                <span class="whitespace-nowrap">Create Artist</span>
            </a>
        </div>
    </div>

    <!-- Artists Table -->
    <div class="bg-[#213042] border border-[#53a1b3]/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#141e24]">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-normal text-[#53a1b3] uppercase tracking-wider">Image</th>
                        <th class="px-4 py-3 text-left text-xs font-normal text-[#53a1b3] uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-normal text-[#53a1b3] uppercase tracking-wider">Bio</th>
                        <th class="px-4 py-3 text-left text-xs font-normal text-[#53a1b3] uppercase tracking-wider">Songs</th>
                        <th class="px-4 py-3 text-right text-xs font-normal text-[#53a1b3] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#53a1b3]/10">
                    @forelse($artists as $artist)
                    <tr class="hover:bg-[#1a2834] transition">
                        <td class="px-4 py-3">
                            @if($artist->image_path)
                                <img src="{{ Storage::url($artist->image_path) }}" alt="{{ $artist->name }}" class="w-12 h-12 object-cover rounded-full">
                            @else
                                <div class="w-12 h-12 bg-[#141e24] flex items-center justify-center rounded-full">
                                    <ion-icon name="person-outline" class="w-5 h-5 text-[#53a1b3]"></ion-icon>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-white text-sm font-normal">{{ $artist->name }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-[#53a1b3] text-sm text-ellipsis overflow-hidden w-64 whitespace-nowrap">{{ Str::limit($artist->bio, 50) }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <!-- Assuming relationship 'songs' exists -->
                            <span class="bg-[#141e24] text-[#53a1b3] px-2 py-1 text-xs rounded-[3px]">{{ $artist->songs_count ?? $artist->songs()->count() }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.artists.edit', $artist->id) }}" class="text-[#53a1b3] hover:text-[#e96c4c] transition" title="Edit">
                                    <ion-icon name="create-outline" class="w-5 h-5"></ion-icon>
                                </a>
                                <form id="del-artist-{{ $artist->id }}" action="{{ route('admin.artists.destroy', $artist->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        data-confirm="Delete this artist? This cannot be undone."
                                        data-confirm-form="del-artist-{{ $artist->id }}"
                                        class="text-[#53a1b3] hover:text-red-500 transition" title="Delete">
                                        <ion-icon name="trash-outline" class="w-5 h-5"></ion-icon>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-[#53a1b3]">
                            No artists found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="mt-4">
        {{ $artists->links('pagination::tailwind') }}
    </div>

</div>
@endsection
