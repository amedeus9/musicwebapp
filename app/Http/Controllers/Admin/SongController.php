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
        $query = Song::with(['artistProfile.country', 'albumRelation'])->latest();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('artistProfile', function($query) use ($search) {
                      $query->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('albumRelation', function($query) use ($search) {
                      $query->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $songs = $query->paginate(20);

        return view('Admin.songs.index', compact('songs'));
    }

    public function create()
    {
        $artists = \App\Models\Artist::orderBy('name')->get();
        return view('Admin.songs.create', compact('artists'));
    }

    public function store(Request $request, \App\Services\AudioMetadataService $metaService)
    {
        $request->validate([
            'title' => 'required',
            'artist_id' => 'required|exists:artists,id',
            'file' => 'required|file|mimetypes:audio/mpeg,audio/mp3,audio/wav,audio/wave,audio/x-wav,audio/ogg,audio/x-ogg|max:20480',
            'cover' => 'nullable|image|max:2048',
        ]);
        
        $artist = \App\Models\Artist::findOrFail($request->artist_id);
        $artistName = $artist->name;

        // Custom Filename
        $originalExt = $request->file('file')->getClientOriginalExtension();
        $filename = $artistName . ' - ' . $request->title . '.' . $originalExt;
        $filename = preg_replace('/[^A-Za-z0-9\-\.\ ]/', '', $filename);
        
        $filePath = $request->file('file')->storeAs('songs', $filename, 'public');
        $absoluteFilePath = storage_path('app/public/' . $filePath);

        $coverPath = null;
        $absoluteCoverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
            $absoluteCoverPath = storage_path('app/public/' . $coverPath);
        }

        // Read Metadata
        $fileMetadata = [];
        try {
            $fileMetadata = $metaService->getMetadata($absoluteFilePath);
        } catch (\Exception $e) {}

        // Create Song
        \App\Models\Song::create([
            // 'user_id' => auth()->id(), // Removed
            'title' => $request->title,
            // 'artist' => $artistName, // Removed
            'artist_id' => $artist->id,
            // 'country_id' => $artist->country_id, // Removed
            'description' => $request->description,
            'file_path' => $filePath,
            'cover_path' => $coverPath,
            // 'album' => $fileMetadata['album'] ?? null, // Removed
            'year' => $fileMetadata['year'] ?? date('Y'),
            'duration' => $fileMetadata['duration'] ?? null,
            'duration_seconds' => $fileMetadata['duration_seconds'] ?? 0,
            'bitrate' => $fileMetadata['bitrate'] ?? null,
            'file_size' => $request->file('file')->getSize(),
        ]);

        // Update Metadata Tags
        try {
             $metaService->updateMetadata($absoluteFilePath, [
                'title' => $request->title,
                'artist' => $artistName,
                'year' => date('Y'),
            ], $absoluteCoverPath);
        } catch (\Exception $e) {
            \Log::warning("Failed to update tags: " . $e->getMessage());
        }

        return redirect()->route('admin.songs.index')->with('success', 'Song created successfully!');
    }

    public function edit(Song $song, \App\Services\AudioMetadataService $metaService)
    {
        $metadata = [];
        if ($song->file_path && Storage::disk('public')->exists($song->file_path)) {
            $path = Storage::disk('public')->path($song->file_path);
            try {
                $metadata = $metaService->getMetadata($path);
            } catch (\Exception $e) {
                // Ignore missing file errors
            }
        }

        return view('Admin.songs.edit', compact('song', 'metadata'));
    }

    public function update(Request $request, Song $song, \App\Services\AudioMetadataService $metaService)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'album' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:4',
            'lyrics' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            // 'artist' => $request->artist, // Removed
            // 'album' => $request->album, // Removed
            'genre' => $request->genre,
            'year' => $request->year,
            'lyrics' => $request->lyrics,
        ];

        // Handle cover upload
        $coverFullPath = null;
        if ($request->hasFile('cover')) {
            // Delete old cover
            if ($song->cover_path) {
                Storage::disk('public')->delete($song->cover_path);
            }
            $data['cover_path'] = $request->file('cover')->store('covers', 'public');
            $coverFullPath = Storage::disk('public')->path($data['cover_path']);
        }

        $song->update($data);

        // Update ID3 Tags using AudioMetadataService
        if ($song->file_path && Storage::disk('public')->exists($song->file_path)) {
            $path = Storage::disk('public')->path($song->file_path);
            try {
                $metaService->updateMetadata($path, [
                    'title' => $request->title,
                    'artist' => $request->artist,
                    'album' => $request->album,
                    'comment' => $request->genre, 
                    'year' => $request->year ?? date('Y'),
                    // 'unsynchronised_lyric' => $request->lyrics // getID3 might support this
                ], $coverFullPath);
            } catch (\Exception $e) {
                \Log::warning("Failed to update tags for song {$song->id}: " . $e->getMessage());
            }
        }

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
