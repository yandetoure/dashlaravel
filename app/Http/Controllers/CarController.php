<?php declare(strict_types=1);
namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function create()
    {
        return view('cars.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'marque' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:'.(date('Y')),
            'matricule' => 'required|string|min:0',
        ]);

        // Créer la voiture
        Car::create([
            'marque' => $request->marque,
            'model' => $request->model,
            'year' => $request->year,
            'matricule' => $request->matricule,
        ]);

        return redirect()->route('cars.index')->with('success', 'Voiture ajoutée avec succès !');
    }

    public function index()
    {
        // Utilisation de la pagination (par exemple 10 par page)
        $cars = Car::paginate(10);

        // Retourner la vue avec la pagination
        return view('cars.index', compact('cars'));
    }

    public function show(Car $car)
    {
        return view('cars.show', compact('car'));
    }

    public function edit(Car $car)
    {
        return view('cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        $request->validate([
            'marque' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:'.(date('Y')),
            'matricule' => 'required|string|unique:cars,matricule,' . $car->id .'|max:50',
        ]);

        $car->update($request->only(['marque', 'model', 'color', 'year', 'matricule']));

        return redirect()->route('cars.index')->with('success', 'Voiture mise à jour avec succès !');
    }

    public function destroy(Car $car)
    {
        $car->delete();
        return redirect()->route('cars.index')->with('success', 'Voiture supprimée avec succès !');
    }
}
