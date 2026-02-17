<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Song;
use Illuminate\Support\Facades\Storage;

class SongController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Song::with('user')->latest();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('artist', 'like', "%{$search}%")
                  ->orWhere('album', 'like', "%{$search}%");
            });
        }

        $songs = $query->paginate(20);

        return view('Admin.songs.index', compact('songs'));
    }

    public function edit(Song $song)
    {
        return view('Admin.songs.edit', compact('song'));
    }

    public function update(Request $request, Song $song)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'album' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:255',
            'cover' => 'nullable|image|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'artist' => $request->artist,
            'album' => $request->album,
            'genre' => $request->genre,
        ];

        // Handle cover upload
        if ($request->hasFile('cover')) {
            // Delete old cover
            if ($song->cover_path) {
                Storage::disk('public')->delete($song->cover_path);
            }
            $data['cover_path'] = $request->file('cover')->store('covers', 'public');
        }

        $song->update($data);

        return redirect()->route('admin.songs.index')->with('success', 'Song updated successfully!');
    }

    public function destroy(Song $song)
    {
        // Delete associated files
        if ($song->cover_path) {
            Storage::disk('public')->delete($song->cover_path);
        }
        if ($song->file_path) {
            Storage::disk('public')->delete($song->file_path);
        }

        $song->delete();

        return redirect()->route('admin.songs.index')->with('success', 'Song deleted successfully!');
    }
}
