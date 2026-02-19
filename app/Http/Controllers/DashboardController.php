<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $latestReleases = \App\Models\Song::with(['artistProfile.country', 'albumRelation'])->latest()->limit(5)->get();
        $songs = \App\Models\Song::with(['artistProfile.country', 'albumRelation'])->latest()->get();
        $albums = \App\Models\Album::latest()->take(5)->get();
        $artists = \App\Models\Artist::with('country')->take(10)->get(); // Increased limit for 2-row layout

        // Get top public playlists with most songs
        $topPlaylists = \App\Models\Playlist::where('is_public', true)
            ->withCount('songs')
            ->orderByDesc('songs_count')
            ->take(6)
            ->get();

        $stats = [
            'songs' => \App\Models\Song::count(),
            'albums' => \App\Models\Album::count(),
            'artists' => \App\Models\Artist::count(),
        ];

        return view('dashboard', compact('latestReleases', 'songs', 'albums', 'artists', 'topPlaylists', 'stats'));
    }
}
