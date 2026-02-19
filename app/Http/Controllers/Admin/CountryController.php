<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $query = Country::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('iso_code', 'like', "%{$search}%");
        }

        // Show trending first, then alphabetical
        $countries = $query->orderBy('is_trending', 'desc')
                           ->orderBy('name')
                           ->paginate(20);

        return view('admin.countries.index', compact('countries'));
    }

    public function updateTrending(Request $request, Country $country)
    {
        $country->update(['is_trending' => !$country->is_trending]);
        
        return back()->with('success', 'Country status updated successfully.');
    }
}
