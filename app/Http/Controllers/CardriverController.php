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
        // Récupérer toutes les voitures (pas seulement les non assignées)
        $cars = Car::all();

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

        // Vérifier si le chauffeur est déjà assigné à une autre voiture
        $existingDriverAssignment = CarDriver::where('chauffeur_id', $request->chauffeur_id)->first();
        if ($existingDriverAssignment) {
            return redirect()->back()->with('error', 'Ce chauffeur est déjà assigné à une voiture.');
        }

        // Supprimer l'ancienne assignation de la voiture si elle existe
        CarDriver::where('car_id', $request->car_id)->delete();

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
