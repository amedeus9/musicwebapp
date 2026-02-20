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
        $countries = $query->withCount(['songs', 'artists'])
                           ->orderBy('is_trending', 'desc')
                           ->orderBy('name')
                           ->paginate(20);

        return view('admin.countries.index', compact('countries'));
    }

    public function create()
    {
        return view('admin.countries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:countries',
            'iso_code' => 'required|string|size:2|unique:countries',
            'region' => 'nullable|string|max:255',
        ]);

        $validated['is_trending'] = $request->has('is_trending');

        Country::create($validated);

        return redirect()->route('admin.countries.index')->with('success', 'Country created successfully.');
    }

    public function edit(Country $country)
    {
        return view('admin.countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:countries,name,' . $country->id,
            'iso_code' => 'required|string|size:2|unique:countries,iso_code,' . $country->id,
            'region' => 'nullable|string|max:255',
        ]);
        
        $validated['is_trending'] = $request->has('is_trending');

        $country->update($validated);

        return redirect()->route('admin.countries.index')->with('success', 'Country updated successfully.');
    }

    public function updateTrending(Request $request, Country $country)
    {
        $country->update(['is_trending' => !$country->is_trending]);
        
        return back()->with('success', 'Country status updated successfully.');
    }

    public function destroy(Country $country)
    {
        if ($country->artists()->exists() || $country->songs()->exists()) {
            return back()->with('error', 'Cannot delete country with associated artists or songs.');
        }

        $country->delete();
        return back()->with('success', 'Country deleted successfully.');
    }
}
