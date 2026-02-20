<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Song;
use App\Models\Album;
use App\Models\Artist;
use App\Models\User;
use App\Models\Playlist;
use App\Models\Comment;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Add admin middleware here when you create it
        // $this->middleware('admin');
    }

    public function dashboard()
    {
        $stats = [
            'total_songs' => Song::count(),
            'total_albums' => Album::count(),
            'total_artists' => Artist::count(),
            'total_users' => User::count(),
            'total_playlists' => Playlist::count(),
            'total_comments' => Comment::count(),
        ];

        // Recent activity
        $recentSongs = Song::with('artistProfile')->withCount(['likes', 'comments'])->latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();

        // System stats
        $totalLikes = DB::table('likes')->where('likeable_type', 'App\\Models\\Song')->count();
        $totalComments = Comment::count();
        $totalDownloads = Song::sum('downloads') ?? 0;
        $totalEngagement = $totalLikes + $totalComments;
        $songCount = Song::count();

        $systemStats = [
            'total_downloads' => $totalDownloads,
            'total_engagement' => $totalEngagement,
            'total_likes' => $totalLikes,
            'avg_downloads_per_song' => $songCount > 0 ? round($totalDownloads / $songCount, 2) : 0,
            'total_content' => $songCount + Album::count() + Artist::count(),
        ];

        // Top content
        $topSongs = Song::withCount('likes')->orderByDesc('likes_count')->take(5)->get();
        $topPlaylists = Playlist::where('is_public', true)->withCount('songs')->orderByDesc('songs_count')->take(5)->get();

        return view('Admin.dashboard', compact('stats', 'recentSongs', 'recentUsers', 'topSongs', 'topPlaylists', 'systemStats'));
    }
}
