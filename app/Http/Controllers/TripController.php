<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function index()
    {
        // Remplacez '10' par le nombre d'éléments par page que vous souhaitez
        $trips = Trip::paginate(10); 
        return view('trips.index', compact('trips'));
    }

    public function create()
    {
        return view('trips.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'departure' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
        ]);

        Trip::create($request->all());

        return redirect()->route('trips.index')->with('success', 'Trajet ajouté avec succès.');
    }

    public function show(Trip $trip)
    {
        return view('trips.show', compact('trip'));
    }

    public function edit(Trip $trip)
    {
        return view('trips.edit', compact('trip'));
    }

    public function update(Request $request, Trip $trip)
    {
        $request->validate([
            'departure' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
        ]);

        $trip->update($request->all());

        return redirect()->route('trips.index')->with('success', 'Trajet mis à jour avec succès.');
    }

    public function destroy(Trip $trip)
    {
        $trip->delete();

        return redirect()->route('trips.index')->with('success', 'Trajet supprimé avec succès.');
    }
}
