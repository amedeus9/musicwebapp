<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Song::query();

        // Handle search by title, artist, or genre
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%'.$searchTerm.'%')
                    ->orWhereHas('artistProfile', function($query) use ($searchTerm) {
                        $query->where('name', 'like', '%'.$searchTerm.'%');
                    })
                    ->orWhere('description', 'like', '%'.$searchTerm.'%');
            });
        }

        // Handle country filter if passed
        if ($request->has('country_id') && $request->country_id != '') {
            $query->whereHas('artistProfile', function($q) use ($request) {
                $q->where('country_id', $request->country_id);
            });
        }

        // Trending Sort
        if ($request->has('sort') && $request->sort == 'trending') {
            $query->trending($request->country_id);
        } else {
            $query->latest();
        }

        $songs = $query->with(['artistProfile.country', 'albumRelation'])->paginate(20);

        if ($request->ajax()) {
            return view('songs.partials.list', compact('songs'));
        }

        return view('songs.index', compact('songs'));
    }

    public function create()
    {
        if (!Auth::check() || !Auth::user()->is_admin) {       
            abort(403, 'Only admins can upload tracks.');
        }

        $artists = \App\Models\Artist::orderBy('name')->get();
        return view('songs.create', compact('artists'));
    }

    public function store(Request $request, \App\Services\AudioMetadataService $metaService)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403);
        }

        $request->validate([
            'title' => 'required',
            'artist_id' => 'required|exists:artists,id',
            'file' => 'required|file|mimetypes:audio/mpeg,audio/mp3,audio/wav,audio/wave,audio/x-wav,audio/ogg,audio/x-ogg|max:20480',
            'cover' => 'nullable|image|max:2048',
        ]);

        $artist = \App\Models\Artist::findOrFail($request->artist_id);
        $artistName = $artist->name;

        // Generate Custom Filename: Artist - Title.mp3
        $originalExt = $request->file('file')->getClientOriginalExtension();
        $filename = $artistName . ' - ' . $request->title . '.' . $originalExt;
        
        // Sanitize filename (remove special chars except space, dot, dash)
        $filename = preg_replace('/[^A-Za-z0-9\-\.\ ]/', '', $filename);
        
        $filePath = $request->file('file')->storeAs('songs', $filename, 'public');
        $absoluteFilePath = storage_path('app/public/' . $filePath);
        
        $coverPath = null;
        $absoluteCoverPath = null;

        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
            $absoluteCoverPath = storage_path('app/public/' . $coverPath);
        }

        // Read Metadata for Database
        $fileMetadata = [];
        try {
            $fileMetadata = $metaService->getMetadata($absoluteFilePath);
        } catch (\Exception $e) {
            \Log::warning('Failed to read MP3 metadata: ' . $e->getMessage());
        }

        // Save to Database
        $song = \App\Models\Song::create([
            // 'user_id' => Auth::id(), // Removed
            'title' => $request->title,
            // 'artist' => $artistName, // Removed
            'artist_id' => $artist->id, 
            'description' => $request->description,
            // 'country_id' => $artist->country_id, // Removed
            'file_path' => $filePath,
            'cover_path' => $coverPath,
            // New Metadata Fields
            // 'album' => $fileMetadata['album'] ?? null, // Removed
            'year' => $fileMetadata['year'] ?? date('Y'),
            'duration' => $fileMetadata['duration'] ?? null,
            'duration_seconds' => $fileMetadata['duration_seconds'] ?? 0,
            'bitrate' => $fileMetadata['bitrate'] ?? null,
            'file_size' => $request->file('file')->getSize(),
        ]);

        // Update MP3 Metadata (ID3 Tags) with form data (overwriting file tags if needed)
        try {
            $metaService->updateMetadata($absoluteFilePath, [
                'title' => $request->title,
                'artist' => $artistName,
                'album' => $fileMetadata['album'] ?? 'TestMusic App Uploads',
                'comment' => $request->description,
                'year' => date('Y'),
            ], $absoluteCoverPath);
        } catch (\Exception $e) {
            // Log error but continue (don't fail the upload just because tags failed)
            \Illuminate\Support\Facades\Log::warning('Failed to update ID3 tags: ' . $e->getMessage());
        }

        return redirect()->route('songs.index')->with('success', 'Song uploaded successfully!');
    }

    public function show(\App\Models\Country $country, \App\Models\Artist $artist, \App\Models\Song $song)
    {
        // Validate relationships
        if ($song->artist_id !== $artist->id) {
            abort(404, 'Song does not belong to this artist');
        }
        if ($artist->country_id && $artist->country_id !== $country->id) {
             abort(404, 'Artist does not belong to this country');
        }

        // Get related songs (same artist or recent uploads)
        $relatedSongs = \App\Models\Song::where('artist_id', $artist->id)
            ->where('id', '!=', $song->id)
            ->with(['artistProfile.country', 'albumRelation'])
            ->latest()
            ->take(6)
            ->get();

        // If not enough songs from same artist, fill with recent songs
        if ($relatedSongs->count() < 6) {
            $additionalSongs = \App\Models\Song::where('id', '!=', $song->id)
                ->whereNotIn('id', $relatedSongs->pluck('id'))
                ->with(['artistProfile.country', 'albumRelation'])
                ->latest()
                ->take(6 - $relatedSongs->count())
                ->get();

            $relatedSongs = $relatedSongs->merge($additionalSongs);
        }

        return view('songs.show', compact('song', 'relatedSongs', 'artist', 'country'));
    }

    public function destroy(\App\Models\Song $song)
    {
        if (!Auth::check() || $song->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $song->delete();

        return redirect()->route('songs.index')->with('success', 'Song deleted successfully!');
    }

    public function download(\App\Models\Song $song, \App\Services\UserLocationService $locationService)
    {
        $song->increment('downloads');
        
        // Log interaction for trending
        \App\Models\SongInteraction::create([
            'song_id' => $song->id,
            'type' => 'download',
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'country_code' => $locationService->getCountryCode(),
        ]);

        return response()->download(storage_path('app/public/' . $song->file_path));
    }

    public function registerPlay(\App\Models\Song $song, \App\Services\UserLocationService $locationService)
    {
        \App\Models\SongInteraction::create([
            'song_id' => $song->id,
            'type' => 'play',
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'country_code' => $locationService->getCountryCode(),
        ]);

        return response()->json(['success' => true]);
    }

    public function favorites()
    {
        if (Auth::check()) {
            // Show user's liked songs
            $likes = \App\Models\Like::where('user_id', Auth::id())
                              ->where('likeable_type', \App\Models\Song::class)
                              ->with(['likeable' => function($query) {
                                  $query->with('artistProfile.country');
                              }])
                              ->latest()
                              ->get();

            $songs = $likes->map(function($like) {
                return $like->likeable;
            })->filter();

            $hasFavorites = $songs->isNotEmpty();
        } else {
            // Show empty state for guests
            $songs = collect();
            $hasFavorites = false;
        }

        return view('pages.favorites', compact('songs', 'hasFavorites'));
    }

    public function profile()
    {
        return view('pages.profile');
    }

    public function editProfile()
    {
        return view('pages.profile-edit');
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'required',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update name and email
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }
}
