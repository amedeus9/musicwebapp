<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Playlist;
use App\Models\Song;

class PlaylistController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            // Show user's playlists
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $playlists = $user->playlists->merge($user->collaboratedPlaylists)->sortByDesc('created_at');
        } else {
            // Show public playlists
            $playlists = Playlist::where('is_public', true)->latest()->take(20)->get();
        }

        return view('playlists.index', compact('playlists'));
    }

    public function create()
    {
        return view('playlists.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'required|boolean',
            'cover' => 'nullable|image|max:2048',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('playlist-covers', 'public');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $playlist = $user->playlists()->create([
            'name' => $request->name,
            'description' => $request->description,
            'is_public' => $request->is_public,
            'cover_path' => $coverPath,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'slug' => $playlist->slug,
                'name' => $playlist->name,
                'message' => 'Playlist created successfully!'
            ]);
        }

        return redirect()->route('playlists.show', $playlist->slug)
                        ->with('success', 'Playlist created successfully!');
    }

    public function show(Playlist $playlist)
    {
        // Check if user can view playlist
        if (!$playlist->is_public && (!Auth::check() || Auth::id() !== $playlist->user_id)) {
            abort(403, 'This playlist is private.');
        }

        $playlist->load('songs', 'user');

        return view('playlists.show', compact('playlist'));
    }

    public function edit(Playlist $playlist)
    {
        // Check ownership
        if (Auth::id() !== $playlist->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('playlists.edit', compact('playlist'));
    }

    public function update(Request $request, Playlist $playlist)
    {
        // Check ownership
        if (Auth::id() !== $playlist->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'required|boolean',
            'cover' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('playlist-covers', 'public');
            $playlist->cover_path = $coverPath;
        }

        $playlist->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_public' => $request->is_public,
        ]);

        return redirect()->route('playlists.show', $playlist->slug)
                        ->with('success', 'Playlist updated successfully!');
    }

    public function destroy(Playlist $playlist)
    {
        // Check ownership
        if (Auth::id() !== $playlist->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $playlist->delete();

        return redirect()->route('playlists.index')
                        ->with('success', 'Playlist deleted successfully!');
    }

    public function addSong(Request $request, Playlist $playlist)
    {
        // Check ownership or collaboration
        if (Auth::id() !== $playlist->user_id && !$playlist->collaborators->contains(Auth::id())) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        try {
            $validated = $request->validate([
                'song_id' => 'required|exists:songs,id',
            ]);

            // Check if song is already in playlist
            if ($playlist->songs()->where('songs.id', $validated['song_id'])->exists()) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Song already in playlist.']);
                }
                return back()->with('error', 'Song already in playlist.');
            }

            // Get next position
            $nextPosition = $playlist->songs()->count();

            // Add song to playlist
            $playlist->songs()->attach($validated['song_id'], ['position' => $nextPosition]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Song added to playlist!']);
            }

            return back()->with('success', 'Song added to playlist!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . $e->getMessage(),
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    public function removeSong(Request $request, Playlist $playlist, Song $song)
    {
        // Check ownership or collaboration
        if (Auth::id() !== $playlist->user_id && !$playlist->collaborators->contains('id', Auth::id())) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        $playlist->songs()->detach($song->id);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Song removed from playlist!']);
        }

        return back()->with('success', 'Song removed from playlist!');
    }

    public function invite(Request $request, Playlist $playlist)
    {
        // Check ownership
        if (Auth::id() !== $playlist->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user->id === $playlist->user_id) {
            return back()->with('error', 'You cannot invite yourself.');
        }

        if ($playlist->collaborators()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'User is already a collaborator.');
        }

        $playlist->collaborators()->attach($user->id);

        return back()->with('success', 'Collaborator invited successfully!');
    }

    public function removeCollaborator(Playlist $playlist, \App\Models\User $user)
    {
        // Check ownership
        if (Auth::id() !== $playlist->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $playlist->collaborators()->detach($user->id);

        return back()->with('success', 'Collaborator removed successfully!');
    }
}
