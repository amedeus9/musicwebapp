@extends('Admin.layouts.app')

@section('page-title', 'Manage Trending Countries')

@section('content')
<div class="flex flex-col gap-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Trending Countries</h1>
            <p class="text-sm text-[#53a1b3]/70">Select which countries appear on the home page dashboard.</p>
        </div>
        
        <form action="{{ route('admin.countries.index') }}" method="GET" class="relative">
            <input type="text" name="search" value="{{ request('search') }}" 
                class="bg-[#0f1319] border border-[#53a1b3]/20 text-white text-sm rounded-full pl-10 pr-4 py-2 focus:ring-1 focus:ring-[#e96c4c] focus:border-[#e96c4c] outline-none w-64 placeholder-[#53a1b3]/40"
                placeholder="Search country...">
            <ion-icon name="search-outline" class="absolute left-3.5 top-2.5 text-[#53a1b3]/60"></ion-icon>
        </form>
    </div>

    <div class="bg-[#1a1f26] border border-[#53a1b3]/10 rounded-xl overflow-hidden shadow-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-[#53a1b3]/10 text-[#53a1b3] text-xs uppercase tracking-wider">
                    <th class="p-4 font-medium">Country Name</th>
                    <th class="p-4 font-medium">ISO Code</th>
                    <th class="p-4 font-medium text-center">Status</th>
                    <th class="p-4 font-medium text-right">Action</th>
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
                    <td class="p-4 text-center">
                        @if($country->is_trending)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-[#e96c4c]/20 text-[#e96c4c] border border-[#e96c4c]/30">
                                Trending
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-[#53a1b3]/10 text-[#53a1b3]/60 border border-[#53a1b3]/20">
                                Inactive
                            </span>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <form action="{{ route('admin.countries.trending', $country) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                class="text-xs px-3 py-1.5 rounded-lg border transition {{ $country->is_trending ? 'border-red-500/30 text-red-500 hover:bg-red-500/10' : 'border-[#e96c4c]/30 text-[#e96c4c] hover:bg-[#e96c4c]/10' }}">
                                {{ $country->is_trending ? 'Remove' : 'Add to Trending' }}
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
