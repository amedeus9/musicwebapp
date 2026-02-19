<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Song;
use Illuminate\Http\Request;

class CountrySongController extends Controller
{
    /**
     * Display all songs associated with a country (Artist Origin).
     * Route: /{country}/songs
     */
    public function index($countrySlug)
    {
        if (strtolower($countrySlug) === 'global') {
            $songs = Song::latest()->paginate(20);
            return view('songs.index', [
                'songs' => $songs,
                'title' => "All Songs",
            ]);
        }

        $country = $this->resolveCountry($countrySlug);

        $songs = Song::whereHas('artistProfile', function ($query) use ($country) {
            $query->where('country_id', $country->id);
        })->latest()->paginate(20);

        return view('songs.index', [
            'songs' => $songs,
            'title' => "Songs from {$country->name}",
            'country' => $country
        ]);
    }

    /**
     * Display trending songs in a specific country (Interaction Location).
     * Route: /{country}/trending-songs
     */
    public function trending($countrySlug)
    {
        if (strtolower($countrySlug) === 'global') {
            $songs = Song::trending(null, null)->paginate(20);
            return view('songs.index', [
                'songs' => $songs,
                'title' => "Global Trending",
            ]);
        }

        $country = $this->resolveCountry($countrySlug);

        // Fetch trending songs based on interactions in this country
        $songs = Song::trending(null, $country->iso_code)->paginate(20);

        return view('songs.index', [
            'songs' => $songs,
            'title' => "Trending in {$country->name}",
            'country' => $country
        ]);
    }

    /**
     * Helper to resolve country by slug or ISO code.
     */
    protected function resolveCountry($identifier)
    {
        // Try slug
        $country = Country::where('slug', $identifier)->first();
        
        // Try ISO Code
        if (!$country) {
            $country = Country::where('iso_code', strtoupper($identifier))->first();
        }
        
        if (!$country) {
            abort(404, 'Country not found');
        }

        return $country;
    }

    /**
     * Display list of all countries for selection.
     * Route: /countries
     */
    public function list()
    {
        $countries = Country::orderBy('name')->get();
        return view('countries.index', compact('countries'));
    }
}

