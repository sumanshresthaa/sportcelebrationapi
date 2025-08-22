<?php
namespace App\Http\Controllers;
use App\Models\Country;
use App\Models\Matches;
use Illuminate\Http\Request;




class CountryAdminController extends Controller
{
    



public function index()
{
    $countries = Country::all();
    return view('countries.index', compact('countries'));
}

public function create() {
    return view('countries.create');
}



public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'flag_url' => 'required|string',
        'matches' => 'nullable|array',
        'matches.*.title' => 'required|string',
        'matches.*.date' => 'required|date',
        'matches.*.score_germany' => 'nullable|integer',
        'matches.*.score_france' => 'nullable|integer',
    ]);
    // First, create the country
    $country = Country::create([
        'name' => $request->name,
        'description' => $request->description,
        'flag_url' => $request->flag_url,
    ]);

    // Then, create related matches if any
    if ($request->has('matches')) {
        foreach ($request->matches as $match) {
            Matches::create([
                'country_id' => $country->id, // assumes foreign key column
                'title' => $match['title'],
                'date' => $match['date'],
                'score_germany' => $match['score_germany'] ?? null,
                'score_france' => $match['score_france'] ?? null,
            ]);
        }
    }

    return redirect()->route('countries.index')->with('success', 'Country and matches added.');
}


}


