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
    public function show(\App\Models\Artist $artist)
    {
        $artist->load(['albums', 'songs' => function($query) {
            $query->latest()->take(10);
        }]);
        
        return view('artists.show', compact('artist'));
    }
}
