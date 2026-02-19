<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $artists = \App\Models\Artist::withCount('songs')->latest()->paginate(24);
        return view('artists.index', compact('artists'));
    }

    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Country $country, \App\Models\Artist $artist)
    {
        // Validate relationship
        if ($artist->country_id && $artist->country_id != $country->id) {
            abort(404, 'Artist not found in this country');
        }

        $artist->load(['albums', 'songs' => function($query) {
            $query->with(['artistProfile.country', 'albumRelation'])->latest()->take(10);
        }]);
        
        return view('artists.show', compact('artist', 'country'));
    }
}
