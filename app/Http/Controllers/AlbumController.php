<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $albums = \App\Models\Album::with('artistProfile')->latest()->simplePaginate(20);

        return view('albums.index', compact('albums'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not implementing create for now in testmusic unless requested
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Not implementing store for now
        abort(404);
    }

    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Album $album)
    {
        $album->load(['artistProfile', 'songs']);
        return view('albums.show', compact('album'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Album $album)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\Album $album)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Album $album)
    {
        abort(404);
    }
}
