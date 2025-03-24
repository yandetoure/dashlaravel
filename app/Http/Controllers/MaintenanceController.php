<?php declare(strict_types=1);
namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Car;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = Maintenance::with('car')->paginate(10);
        return view('maintenances.index', compact('maintenances'));
    }

    public function create()
    {
        $cars = Car::all();
        return view('maintenances.create', compact('cars'));
    }




public function store(Request $request)
{
    $request->validate([
        'car_id' => 'required',
        'jour' => 'required|date',
        'heure' => 'required',
        'motif' => 'required',
    ]);

    // Trouver le chauffeur de la voiture
    $chauffeur = User::whereHas('roles', function ($query) {
        $query->where('name', 'chauffeur');
    })->where('car_id', $request->car_id)->first();

    if (!$chauffeur) {
        return back()->withErrors(['car_id' => "Aucun chauffeur n'est assigné à cette voiture."]);
    }

    // Vérifier et mettre à jour son jour de repos si nécessaire
    $this->mettreAJourJourRepos($chauffeur);

    // Vérifier si le jour sélectionné correspond au jour de repos
    if ($chauffeur->jour_repos === Carbon::parse($request->jour)->translatedFormat('l')) {
        return back()->withErrors(['jour' => "Ce chauffeur est en repos ce jour-là ({$chauffeur->jour_repos})."]);
    }

   // Créer la nouvelle maintenance
   Maintenance::create([
    'car_id' => $request->car_id,
    'jour' => $request->jour,
    'heure' => $request->heure,
    'motif' => $request->motif,
    'garagiste' => $request->garagiste,
    'prix' => $request->prix,
    'statut' => false, // ou true selon votre logique
]);

    return redirect()->route('maintenances.index')->with('success', 'Maintenance ajoutée avec succès.');
}

/**
 * Met à jour le jour de repos d'un chauffeur s'il n'a pas été modifié depuis une semaine.
 */
private function mettreAJourJourRepos(User $chauffeur)
{
    $derniereMiseAJour = Carbon::parse($chauffeur->updated_at);

    if ($derniereMiseAJour->diffInDays(now()) >= 7) {
        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $jourRepos = $jours[array_rand($jours)]; // Jour aléatoire
        $chauffeur->update(['jour_repos' => $jourRepos]);
    }
}

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();

        return redirect()->route('maintenances.index')->with('success', 'Maintenance supprimée.');
    }
}
