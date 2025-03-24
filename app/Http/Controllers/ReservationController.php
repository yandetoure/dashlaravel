<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Trip;
use App\Models\CarDriver;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Maintenance;
use App\Models\Car;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationCreatedclient;
use App\Mail\ReservationCreated;
use App\Mail\ReservationUpdateddriver;
use App\Mail\ReservationUpdatedclient;
use App\Mail\ReservationUpdated;
use App\Mail\ReservationCancelleddriver;
use App\Mail\ReservationCancelledclient;
use Illuminate\Support\Facades\Hash;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('chauffeur', 'client', 'car', 'trip', 'carDriver')->paginate(10);
        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $trips = Trip::all(); // Récupère tous les voyages

        $chauffeurs = User::whereHas('roles', function ($query) {
            $query->where('name', 'chauffeur');
        })->get();

        $clients = User::whereHas('roles', function ($query) {
            $query->where('name', 'client');
        })->get();

        return view('reservations.agentcreate', compact('chauffeurs', 'clients', 'trips'));
    }

public function store(Request $request)
{
     // Récupérer le client connecté
     $client = Auth::user();

    $request->validate([
        'trip_id' => 'required|exists:trips,id',
        'chauffeur_id' => 'required|exists:users,id',
        'date' => 'required|date',
        'heure_ramassage' => 'required',
        'heure_vol' => 'required',
        'numero_vol' => 'required',
        'nb_personnes' => 'required|integer|min:1',
        'nb_valises' => 'required|integer|min:0',
        'nb_adresses' => 'required|integer|min:1',
        'status' => 'required|string|in:En_attente,Confirmée,Annulée',
        'tarif' => 'required|numeric|min:0',
    ]);

    $user = Auth::user();        
    $client_id = $client->id; 

    // Récupérer la relation CarDriver
    $carDriver = CarDriver::where('chauffeur_id', $request->chauffeur_id)->firstOrFail();

    if (!$carDriver) {
        return back()->withErrors(['chauffeur_id' => 'Ce chauffeur n\'a pas de voiture assignée.']);
    }

    // Vérifier la disponibilité du chauffeur
    $lastReservation = Reservation::where('cardriver_id', $carDriver->id)
        ->where('status', 'confirmed')
        ->orderBy('date', 'desc')
        ->first();

    if ($lastReservation) {
        $lastReservationTime = Carbon::parse($lastReservation->heure_ramassage);
        if ($lastReservationTime->diffInHours($request->heure_ramassage) < 3) {
            return back()->withErrors(['date' => 'Le chauffeur ne peut pas être réservé moins de 3 heures après sa dernière réservation.']);
        }
    }

    // Vérifier si la voiture est en maintenance
    $maintenance = Maintenance::where('car_id', $carDriver->car_id)
        ->where('jour', $request->date)
        ->first();

    if ($maintenance) {
        return back()->withErrors(['date' => "La voiture du chauffeur est en maintenance ce jour-là."]);
    }

    // Calcul du tarif
    $tarif = $this->calculerTarif($request->nb_personnes, $request->nb_valises, $request->nb_adresses);

    // Création de la réservation
    Reservation::create([
        'client_id' => $client_id,
        'trip_id' => $request->trip_id,
        'date' => $request->date,
        'heure_ramassage' => $request->heure_ramassage,
        'heure_vol' => $request->heure_vol,
        'numero_vol' => $request->numero_vol,
        'nb_personnes' => $request->nb_personnes,
        'nb_valises' => $request->nb_valises,
        'nb_adresses' => $request->nb_adresses,
        'tarif' => $tarif,
        'status' => 'En_attente',
        'cardriver_id' => $carDriver->id, // Stocke l'ID de la relation CarDriver
        'first_name' => $client->first_name,
        'last_name' => $client->last_name,
        'email' => $client->email,
    ]);

    return redirect()->route('reservations.index')->with('success', 'Réservation ajoutée avec succès.');
}


public function storeByAgent(Request $request)
{
    $request->validate([
        'trip_id' => 'required|exists:trips,id',
        'client_id' => 'nullable|exists:users,id',
        'first_name' => 'nullable|string|max:255',
        'last_name' => 'nullable|string|max:255',
        'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
        'date' => 'required|date',
        'heure_ramassage' => 'required',
        'heure_vol' => 'required',
        'numero_vol' => 'required',
        'nb_personnes' => 'required|integer|min:1',
        'nb_valises' => 'required|integer|min:0',
        'nb_adresses' => 'required|integer|min:0',
    ]);

    // Vérification qu'au moins un choix de client est fait
    if (!$request->client_id && (!$request->last_name || !$request->first_name)) {
        return back()->withErrors(['last_name' => 'Veuillez sélectionner un client existant ou entrer un nom et un prénom.']);
    }

    $user = Auth::user();        
    $idAgent = $user->id;

    // Recherche d'un chauffeur disponible
    $chauffeur = $this->findAvailableDriver($request->date, $request->heure_ramassage);

    if (!$chauffeur) {
        return back()->withErrors(['chauffeur_id' => 'Aucun chauffeur disponible pour ce créneau.']);
    }

    $car = Car::find($chauffeur->car_id);

    if (!$car) {
        return back()->withErrors(['cardriver_id' => 'Ce chauffeur n\'a pas de voiture assignée.']);
    }

    // Récupérer la relation CarDriver
    $carDriver = CarDriver::where('chauffeur_id', $chauffeur->id)->firstOrFail();

    if (!$carDriver) {
        return back()->withErrors(['chauffeur_id' => 'Ce chauffeur n\'a pas de voiture assignée.']);
    }

    // Vérifier la disponibilité du chauffeur (pas de réservation avant 3 heures)
    $lastReservation = Reservation::where('cardriver_id', $carDriver->id)
        ->where('status', 'Confirmée')
        ->orderBy('date', 'desc')
        ->first();     

    if ($lastReservation) {
        $lastReservationTime = Carbon::parse($lastReservation->heure_ramassage);
        $requestHeureRamassage = Carbon::parse($request->heure_ramassage);
        if ($lastReservationTime->diffInHours($requestHeureRamassage) < 3) {
            return back()->withErrors(['date' => 'Le chauffeur ne peut pas être réservé moins de 3 heures après sa dernière réservation.']);
        }
    }

    // Vérification du jour de repos du chauffeur
    if ($chauffeur->day_off === Carbon::parse($request->date)->format('l')) {
        return back()->withErrors(['date' => "Le chauffeur est en repos ce jour-là ({$chauffeur->day_off})."]);
    }

    // Vérification des maintenances
    $maintenance = Maintenance::where('car_id', $car->id)
        ->where('jour', $request->date)
        ->first();

    if ($maintenance) {
        return back()->withErrors(['date' => "La voiture du chauffeur est en maintenance ce jour-là."]);
    }

    $password = Str::random(12);

    // Si le client n'existe pas, on le crée
    $existingClient = User::where('email', $request->email)->first();

    if (!$existingClient) {
        $client = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);
        $clientId = $client->id;
    } else {
        $clientId = $existingClient->id;
    }
$date = $request->date;

    // Calcul du tarif
    $tarif = $this->calculerTarif($request->nb_personnes, $request->nb_valises, $request->nb_adresses);

    // Création de la réservation
    Reservation::create([
        'trip_id' => $request->trip_id,
        'client_id' =>  $clientId,
        'chauffeur_id' =>  $chauffeur->id,
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'id_agent' => $idAgent,
        'date' => $request->date,
        'heure_ramassage' => $request->heure_ramassage,
        'heure_vol' => $request->heure_vol,
        'numero_vol' => $request->numero_vol,
        'nb_personnes' => $request->nb_personnes,
        'nb_valises' => $request->nb_valises,
        'nb_adresses' => $request->nb_adresses,
        'tarif' => $tarif,
        'status' => 'En_attente',
        'cardriver_id' => $carDriver->id, // Stocke l'ID de la relation CarDriver
    ]);

    return redirect()->route('reservations.index')->with('success', 'Réservation ajoutée avec succès par l’agent.');
}

    private function findAvailableDriver($date, $heure_ramassage)
    {
        // Recherche du chauffeur disponible
        $chauffeur = User::whereDoesntHave('reservations', function ($query) use ($date, $heure_ramassage) {
            $query->where('status', 'Confirmée')
                ->whereDate('date', '=', $date)
                ->where('heure_ramassage', '=', $heure_ramassage);
        })
        ->whereNotIn('id', function ($query) use ($date) { 
            $query->select('chauffeur_id')
                ->from('maintenances')
                ->whereDate('jour', '=', Carbon::parse($date)->format('Y-m-d'));
        })
        ->first();

        return $chauffeur;
    }

    public function confirm(Reservation $reservation)
    {
        $reservation->update(['status' => 'confirmée']);
        $this->envoyerEmailReservation($reservation);
        return back()->with('success', 'Réservation confirmée.');
    }

    public function cancel(Reservation $reservation)
    {
        $now = Carbon::now();
        $heureRamassage = Carbon::parse($reservation->heure_ramassage);

        if ($now->diffInMinutes($heureRamassage, false) <= 120) {
            return back()->withErrors(['status' => 'Annulation impossible moins de 2h avant le départ.']);
        }

        $reservation->update(['status' => 'annulée']);
        $this->envoyerEmailReservation($reservation);
        return back()->with('success', 'Réservation annulée.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Réservation supprimée.');
    }

    private function calculerTarif($nbPersonnes, $nbValises, $nbAdresses)
    {
        $tarifBase = 32500;

        if ($nbPersonnes > 3) {
            $tarifBase += ($nbPersonnes - 3) * 5000;
        }

        $valisesIncluses = $nbPersonnes * 2;
        if ($nbValises > $valisesIncluses) {
            $tarifBase += ($nbValises - $valisesIncluses) * 5000;
        }

        if ($nbAdresses > 1) {
            $tarifBase += ($nbAdresses - 1) * 2500;
        }

        return $tarifBase;
    }

     // Méthode pour enregistrer un email lors de la création ou modification de la réservation
     private function envoyerEmailReservation(Reservation $reservation, $status = 'created')
     {
         // Envoyer des emails au client, chauffeur et à l'entreprise
         Mail::to($reservation->client->email)->send(new ReservationCreatedclient($reservation));
         Mail::to('dht321@gmail.com')->send(new ReservationCreated($reservation));
 
         if ($status === 'updated') {
             Mail::to($reservation->chauffeur->email)->send(new ReservationCreatedclient($reservation));
             Mail::to($reservation->client->email)->send(new ReservationCreatedclient($reservation));
             Mail::to('dht321@gmail.com')->send(new ReservationCreatedclient($reservation));

         } elseif ($status === 'canceled') {
             Mail::to($reservation->chauffeur->email)->send(new ReservationCreatedclient($reservation));
             Mail::to($reservation->client->email)->send(new ReservationCreatedclient($reservation));
             Mail::to('dht321@gmail.com')->send(new ReservationCreatedclient($reservation));
         }
     }
 
     // Méthode pour archiver les anciennes versions de réservation en cas de modification
     public function archiveOldReservation(Reservation $reservation)
     {
         $oldReservation = $reservation->replicate(); // Crée une copie de l'ancienne réservation
         $oldReservation->status = 'archived';
         $oldReservation->save();
     }

     public function confirmed()
    {
        $reservations = Reservation::where('status', 'confirmed')->get();
        return view('reservations.confirmed', compact('reservations'));
    }

    public function cancelled()
    {
        $reservations = Reservation::where('status', 'cancelled')->get();
        return view('reservations.cancelled', compact('reservations'));
    }

    /**
     * Affiche une réservation spécifique.
     */
    public function show($id)
{
    $reservation = Reservation::with(['carDriver.chauffeur', 'carDriver.car', 'client', 'trip', 'carDriver'])->findOrFail($id);
    
    // dd($reservation);  // Cela vous permet de voir l'objet complet de la réservation avec toutes les relations.

    return view('reservations.show', compact('reservation'));
}


    /**
     * Met à jour une réservation.
     */
    public function update(Request $request, $id)
    {
        // Valider les données
        $validatedData = $request->validate([
            'date' => 'required|date',
            'heure_ramassage' => 'required|date_format:H:i',
            'heure_vol' => 'nullable|date_format:H:i',
        ]);

        // Trouver la réservation à mettre à jour
        $reservation = Reservation::findOrFail($id);

        // Mettre à jour les informations de la réservation
        $reservation->date = Carbon::parse($request->date)->format('Y-m-d');
        $reservation->heure_ramassage = $request->heure_ramassage;
        $reservation->heure_vol = $request->heure_vol;

        // Sauvegarder les modifications
        $reservation->save();

        // Rediriger avec un message de succès
        return redirect()->route('reservations.index')->with('success', 'Réservation mise à jour avec succès.');
    }
    

}
