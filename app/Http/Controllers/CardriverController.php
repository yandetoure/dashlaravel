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
        // Récupérer toutes les voitures avec leur chauffeur assigné
        $cars = Car::all();
        $carAssignments = CarDriver::with('chauffeur')->get()->keyBy('car_id');
        
        // Ajouter l'information du chauffeur assigné à chaque voiture
        $cars = $cars->map(function ($car) use ($carAssignments) {
            $assignment = $carAssignments->get($car->id);
            $car->assigned_driver = $assignment ? $assignment->chauffeur : null;
            return $car;
        });

        // Récupérer tous les chauffeurs avec leur voiture assignée
        $allDrivers = User::role('chauffeur')->get();
        $driverAssignments = CarDriver::with('car')->get()->keyBy('chauffeur_id');
        
        // Ajouter l'information de la voiture assignée à chaque chauffeur
        $drivers = $allDrivers->map(function ($driver) use ($driverAssignments) {
            $assignment = $driverAssignments->get($driver->id);
            $driver->assigned_car = $assignment ? $assignment->car : null;
            $driver->is_assigned = $assignment ? true : false;
            return $driver;
        });

        return view('cardrivers.create', compact('cars', 'drivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'chauffeur_id' => 'required|exists:users,id',
        ]);

        // Supprimer l'ancienne assignation de la voiture si elle existe
        CarDriver::where('car_id', $request->car_id)->delete();

        // Supprimer l'ancienne assignation du chauffeur s'il est déjà assigné à une autre voiture
        CarDriver::where('chauffeur_id', $request->chauffeur_id)->delete();

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
