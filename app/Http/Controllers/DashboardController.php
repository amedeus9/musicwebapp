<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(\App\Services\UserLocationService $locationService)
    {
        $limit = 10;
        $userCode = $locationService->getCountryCode();
        
        // Valid trending countries from DB
        $dbCodes = \App\Models\Country::where('is_trending', true)->pluck('iso_code');
        
        $codes = $dbCodes;

        // Prioritize User's location if logic allows
        if ($userCode && $dbCodes->contains($userCode)) {
            $codes = $codes->reject(fn($c) => $c === $userCode)->prepend($userCode);
        } else {
            // User country not trending? Show Global Trending first
            $codes = $codes->prepend('GLOBAL');
        }
        
        $codes = $codes->values();

        $trendingSections = [];

        foreach ($codes as $code) {
            if ($code === 'GLOBAL') {
                $songs = \App\Models\Song::with(['artistProfile.country', 'albumRelation'])
                    ->trending(null, null)
                    ->limit($limit)
                    ->get();
                
                $title = "Global Trending";
                $scrollId = "trending-global";
                $viewAllUrl = route('country.trending', ['country' => 'global']);
            } else {
                // Fetch songs trending in this location
                $songs = \App\Models\Song::with(['artistProfile.country', 'albumRelation'])
                    ->trending(null, $code)
                    ->limit($limit)
                    ->get();

                // Get Country Name and Slug
                $title = "Trending " . $code;
                $scrollId = "trending-" . strtolower($code);
                $slug = $code; // Default fallback

                $countryModel = \App\Models\Country::where('iso_code', $code)->first();
                if ($countryModel) {
                    $title = "Trending " . $countryModel->name;
                    $slug = $countryModel->slug;
                } elseif (class_exists('\Symfony\Component\Intl\Countries')) {
                     $name = \Symfony\Component\Intl\Countries::getName($code) ?? $code;
                     $title = "Trending " . $name;
                }
                
                $viewAllUrl = route('country.trending', ['country' => $slug]);
            }

            if ($songs->isNotEmpty()) {
                $trendingSections[] = [
                    'title' => $title,
                    'songs' => $songs,
                    'scroll_id' => $scrollId,
                    'view_all_url' => $viewAllUrl
                ];
            }
        }

        $latestReleases = \App\Models\Song::with(['artistProfile.country', 'albumRelation'])->latest()->limit(5)->get();
        $songs = \App\Models\Song::with(['artistProfile.country', 'albumRelation'])->latest()->get();
        $albums = \App\Models\Album::latest()->take(5)->get();
        $artists = \App\Models\Artist::with('country')->take(10)->get(); // Increased limit for 2-row layout

        // Get top public playlists with most songs
        $topPlaylists = \App\Models\Playlist::where('is_public', true)
            ->withCount('songs')
            ->orderByDesc('songs_count')
            ->take(6)
            ->get();

        $stats = [
            'songs' => \App\Models\Song::count(),
            'albums' => \App\Models\Album::count(),
            'artists' => \App\Models\Artist::count(),
        ];

        return view('dashboard', compact('trendingSections', 'latestReleases', 'songs', 'albums', 'artists', 'topPlaylists', 'stats'));
    }
}
