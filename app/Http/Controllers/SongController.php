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
                    ->orWhere('artist', 'like', '%'.$searchTerm.'%')
                    // Simple genre simulation if no actual genre column exists yet,
                    // or check description. But if musicapp has genre logic (it didn't seem to have a genre column, just simulating), we keep it simple.
                    ->orWhere('description', 'like', '%'.$searchTerm.'%');
            });
        }

        // Handle country filter if passed
        if ($request->has('country_id') && $request->country_id != '') {
            $query->where('country_id', $request->country_id);
        }

        $songs = $query->with(['country', 'artistProfile', 'album'])->latest()->simplePaginate(20);

        if ($request->ajax()) {
            return view('songs.partials.list', compact('songs'));
        }

        return view('songs.index', compact('songs'));
    }

    public function create()
    {
        $countries = \App\Models\Country::orderBy('name')->get();
        return view('songs.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'artist' => 'required',
            'country_id' => 'required|exists:countries,id',
            'file' => 'required|file|mimes:mp3,wav,ogg|max:20480', // 20MB max
            'cover' => 'nullable|image|max:2048',
        ]);

        $filePath = $request->file('file')->store('songs', 'public');
        $coverPath = null;

        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        \App\Models\Song::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'artist' => $request->artist,
            'description' => $request->description,
            'country_id' => $request->country_id,
            'file_path' => $filePath,
            'cover_path' => $coverPath,
        ]);

        return redirect()->route('home')->with('success', 'Song uploaded successfully!');
    }

    public function show(\App\Models\Song $song)
    {
        // Get related songs (same artist or recent uploads)
        $relatedSongs = \App\Models\Song::where('artist', $song->artist)
            ->where('id', '!=', $song->id)
            ->latest()
            ->take(6)
            ->get();

        // If not enough songs from same artist, fill with recent songs
        if ($relatedSongs->count() < 6) {
            $additionalSongs = \App\Models\Song::where('id', '!=', $song->id)
                ->whereNotIn('id', $relatedSongs->pluck('id'))
                ->latest()
                ->take(6 - $relatedSongs->count())
                ->get();

            $relatedSongs = $relatedSongs->merge($additionalSongs);
        }

        return view('songs.show', compact('song', 'relatedSongs'));
    }

    public function destroy(\App\Models\Song $song)
    {
        if (!Auth::check() || $song->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $song->delete();

        return redirect()->route('songs.index')->with('success', 'Song deleted successfully!');
    }

    public function download(\App\Models\Song $song)
    {
        $song->increment('downloads');
        return response()->download(storage_path('app/public/' . $song->file_path));
    }

    public function favorites()
    {
        if (Auth::check()) {
            // Show user's liked songs
            $likes = \App\Models\Like::where('user_id', Auth::id())
                              ->where('likeable_type', \App\Models\Song::class)
                              ->with('likeable')
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
