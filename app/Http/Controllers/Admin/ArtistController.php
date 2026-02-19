<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artist;
use Illuminate\Support\Facades\Storage;

class ArtistController extends Controller
{
    public function index()
    {
        $artists = Artist::withCount('songs')->latest()->paginate(20);
        return view('Admin.artists.index', compact('artists'));
    }

    public function create()
    {
        $countries = \App\Models\Country::orderBy('name')->get();
        return view('Admin.artists.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:artists,name',
            'image' => 'nullable|image|max:2048',
            'bio' => 'nullable|string',
            'country_id' => 'nullable|exists:countries,id',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('artists', 'public');
        }

        Artist::create([
            'name' => $request->name,
            'image_path' => $imagePath,
            'bio' => $request->bio,
            'country_id' => $request->country_id,
        ]);

        return redirect()->route('admin.artists.index')->with('success', 'Artist created successfully!');
    }

    public function edit(Artist $artist)
    {
        $countries = \App\Models\Country::orderBy('name')->get();
        return view('Admin.artists.edit', compact('artist', 'countries'));
    }

    public function update(Request $request, Artist $artist)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:artists,name,' . $artist->id,
            'image' => 'nullable|image|max:2048',
            'bio' => 'nullable|string',
            'country_id' => 'nullable|exists:countries,id',
        ]);

        $data = [
            'name' => $request->name,
            'bio' => $request->bio,
            'country_id' => $request->country_id,
        ];

        if ($request->hasFile('image')) {
            if ($artist->image_path) {
                Storage::disk('public')->delete($artist->image_path);
            }
            $data['image_path'] = $request->file('image')->store('artists', 'public');
        }

        $artist->update($data);

        return redirect()->route('admin.artists.index')->with('success', 'Artist updated successfully!');
    }

    public function destroy(Artist $artist)
    {
        if ($artist->image_path) {
            Storage::disk('public')->delete($artist->image_path);
        }
        $artist->delete();
        return redirect()->route('admin.artists.index')->with('success', 'Artist deleted successfully!');
    }
}
