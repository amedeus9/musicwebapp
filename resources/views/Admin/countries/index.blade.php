@extends('Admin.layouts.app')

@section('page-title', 'Manage Trending Countries')

@section('content')
<div class="flex flex-col gap-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Countries</h1>
            <p class="text-sm text-[#53a1b3]/70">Manage countries and their trending status.</p>
        </div>
        
        <div class="flex items-center gap-4">
            <form action="{{ route('admin.countries.index') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="bg-[#0f1319] border border-[#53a1b3]/20 text-white text-sm rounded-full pl-10 pr-4 py-2 focus:ring-1 focus:ring-[#e96c4c] focus:border-[#e96c4c] outline-none w-64 placeholder-[#53a1b3]/40"
                    placeholder="Search country...">
                <ion-icon name="search-outline" class="absolute left-3.5 top-2.5 text-[#53a1b3]/60"></ion-icon>
            </form>
            
            <a href="{{ route('admin.countries.create') }}" class="px-4 py-2 bg-[#e96c4c] text-white text-sm font-medium rounded-full shadow-lg shadow-[#e96c4c]/20 hover:bg-[#d45a3a] transition flex items-center gap-2">
                <ion-icon name="add-outline" class="w-5 h-5"></ion-icon>
                Add Country
            </a>
        </div>
    </div>

    <div class="bg-[#1a1f26] border border-[#53a1b3]/10 rounded-xl overflow-hidden shadow-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-[#53a1b3]/10 text-[#53a1b3] text-xs uppercase tracking-wider">
                    <th class="p-4 font-medium">Country Name</th>
                    <th class="p-4 font-medium">ISO Code</th>
                    <th class="p-4 font-medium text-center">Artists</th>
                    <th class="p-4 font-medium text-center">Tracks</th>
                    <th class="p-4 font-medium text-center">Status</th>
                    <th class="p-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#53a1b3]/10">
                @foreach($countries as $country)
                <tr class="hover:bg-[#53a1b3]/5 transition group">
                    <td class="p-4 text-white text-sm font-medium">
                        {{ $country->name }}
                    </td>
                    <td class="p-4 text-[#53a1b3]/80 text-xs font-mono">
                        {{ $country->iso_code }}
                    </td>
                    <td class="p-4 text-center text-white text-sm">
                        {{ $country->artists_count }}
                    </td>
                    <td class="p-4 text-center text-white text-sm">
                        {{ $country->songs_count }}
                    </td>
                    <td class="p-4 text-center">
                        @if($country->is_trending)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-[#e96c4c]/20 text-[#e96c4c] border border-[#e96c4c]/30">
                                Trending
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-[#53a1b3]/10 text-[#53a1b3]/60 border border-[#53a1b3]/20">
                                Not Trending
                            </span>
                        @endif
                    </td>
                    <td class="p-4 text-right flex items-center justify-end gap-2">
                        <form action="{{ route('admin.countries.trending', $country) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                class="w-8 h-8 flex items-center justify-center rounded-lg border transition {{ $country->is_trending ? 'border-red-500/30 text-red-500 hover:bg-red-500/10' : 'border-[#e96c4c]/30 text-[#e96c4c] hover:bg-[#e96c4c]/10' }}"
                                title="{{ $country->is_trending ? 'Remove from Trending' : 'Add to Trending' }}">
                                <ion-icon name="{{ $country->is_trending ? 'star' : 'star-outline' }}"></ion-icon>
                            </button>
                        </form>

                        <a href="{{ route('admin.countries.edit', $country) }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-[#53a1b3]/30 text-[#53a1b3] hover:bg-[#53a1b3]/10 hover:text-white transition" title="Edit">
                            <ion-icon name="create-outline"></ion-icon>
                        </a>
                        
                        <form action="{{ route('admin.countries.destroy', $country) }}" method="POST" onsubmit="return confirm('Delete {{ $country->name }}? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg border border-red-500/30 text-red-500 hover:bg-red-500/10 transition" title="Delete">
                                <ion-icon name="trash-outline"></ion-icon>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($countries->hasPages())
        <div class="p-4 border-t border-[#53a1b3]/10">
            {{ $countries->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
