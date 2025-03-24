<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use App\Models\CarDriver;
use Illuminate\Http\Request;

class CardriverController extends Controller
{
    public function index()
    {
        $cars = Car::whereHas('drivers', function ($query) {
            $query->whereHas('roles', function ($roleQuery) {
                $roleQuery->where('name', 'chauffeur');
            });
        })->with('drivers')->paginate(10);
    
        return view('cardrivers.index', compact('cars'));
    }

    public function create()
    {
        // Récupérer les voitures non assignées
        $assignedCars = CarDriver::pluck('car_id')->toArray();
        $cars = Car::whereNotIn('id', $assignedCars)->get();

        // Récupérer les chauffeurs non assignés
        $assignedDrivers = CarDriver::pluck('chauffeur_id')->toArray();
        $drivers = User::role('chauffeur')->whereNotIn('id', $assignedDrivers)->get();

        return view('cardrivers.create', compact('cars', 'drivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'chauffeur_id' => 'required|exists:users,id',
        ]);

        // Vérifier si la voiture est déjà assignée
        $exists = CarDriver::where('car_id', $request->car_id)->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Cette voiture est déjà assignée à un chauffeur.');
        }

        // Vérifier si le chauffeur est déjà assigné
        $exists = CarDriver::where('chauffeur_id', $request->chauffeur_id)->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Ce chauffeur est déjà assigné à une voiture.');
        }

        // Assigner le chauffeur à la voiture
        CarDriver::create([
            'car_id' => $request->car_id,
            'chauffeur_id' => $request->chauffeur_id,
        ]);

        return redirect()->route('cardrivers.index')->with('success', 'Chauffeur assigné avec succès.');
    }

    public function destroy($car_id, $chauffeur_id)
    {
        CarDriver::where('car_id', $car_id)->where('chauffeur_id', $chauffeur_id)->delete();

        return redirect()->route('cardrivers.index')->with('success', 'Chauffeur retiré de la voiture.');
    }
}
