<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /**
     * Follow an artist.
     */
    public function followArtist(Artist $artist)
    {
        if (!Auth::check()) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        Auth::user()->follow($artist);
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_following' => true,
                'followers_count' => $artist->followers()->count() // Re-fetch count
            ]);
        }

        return back()->with('success', 'You are now following ' . $artist->name);
    }
    
    /**
     * Unfollow an artist.
     */
    public function unfollowArtist(Artist $artist)
    {
        if (!Auth::check()) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        Auth::user()->unfollow($artist);
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_following' => false,
                'followers_count' => $artist->followers()->count()
            ]);
        }

        return back()->with('success', 'You have unfollowed ' . $artist->name);
    }

    /**
     * Follow a playlist.
     */
    public function followPlaylist(\App\Models\Playlist $playlist)
    {
        if (!Auth::check()) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        // Prevent following own playlist
        if ($playlist->user_id === Auth::id()) {
            $msg = "You cannot follow your own playlist.";
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 403);
            }
            return back()->with('error', $msg);
        }

        Auth::user()->follow($playlist);
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_following' => true,
                'followers_count' => $playlist->followers()->count()
            ]);
        }

        return back()->with('success', 'You are now following ' . $playlist->name);
    }

    /**
     * Unfollow a playlist.
     */
    public function unfollowPlaylist(\App\Models\Playlist $playlist)
    {
        if (!Auth::check()) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        Auth::user()->unfollow($playlist);
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_following' => false,
                'followers_count' => $playlist->followers()->count()
            ]);
        }

        return back()->with('success', 'You have unfollowed ' . $playlist->name);
    }
}
