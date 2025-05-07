<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Car;
use App\Models\Trip;
use App\Models\User;
use App\Models\CarDriver;
use App\Models\Maintenance;
use App\Models\Invoice;
use App\Models\Reservation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\AccountCreatedMail;
use App\Mail\ReservationCanceled;
use App\Mail\ReservationCanceledclient;
use App\Mail\ReservationCanceledDriver;
use App\Mail\ReservationCreated;
use App\Mail\ReservationUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationCreatedclient;
use App\Mail\ReservationConfirmed;
use App\Mail\ReservationConfirmedclient;
use App\Mail\ReservationConfirmedDriver;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;



class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $chauffeurs = User::role('chauffeur')->get(); // récupère tous les utilisateurs ayant le rôle 'chauffeur'

        // Récupération des réservations avec les relations nécessaires
        $reservations = Reservation::with(['chauffeur', 'client', 'car', 'trip', 'carDriver']);
    
        // Filtrage par statut si un statut est sélectionné
        if ($request->has('status') && !empty($request->status)) {
            $reservations = $reservations->where('status', $request->status);
        }
    
        // Pagination
        $reservations = $reservations->paginate(10);
    
        return view('reservations.index', compact('reservations', 'chauffeurs'));
    }
    
    public function clientcreate()
{
    $trips = Trip::all(); // Récupère tous les voyages
    $client = Auth::user(); // Récupérer le client connecté

    $chauffeurs = User::whereHas('roles', function ($query) {
        $query->where('name', 'chauffeur');
    })->get();

    return view('reservations.clientcreate', compact('trips', 'client', 'chauffeurs'));
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
        $client_id = $client->id; 

        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'date' => 'required|date',
            'heure_ramassage' => 'required',
            'heure_vol' => 'required',
            'numero_vol' => 'required',
            'nb_personnes' => 'required|integer|min:1',
            'nb_valises' => 'required|integer|min:0',
            'adresse_rammassage' => 'required|string|max:255',
            // 'nb_adresses' => 'required|integer|min:1',
            // 'status' => 'required|string|in:En_attente,Confirmée,Annulée',
        ]);


        
        // Recherche d'un chauffeur disponible
        $chauffeur = $this->findAvailableDriver($request->date, $request->heure_ramassage);

        if (!$chauffeur) {
            return back()->withErrors(['chauffeur_id' => 'Aucun chauffeur disponible pour ce créneau.']);
        }

        // Vérification de la voiture du chauffeur
        $car = $chauffeur->car_drivers()->with('car')->first()?->car;

        if (!$car) {
            return back()->withErrors(['date' => "Le chauffeur n'a pas de voiture assignée."]);
        }

        // Récupérer la relation CarDriver
        $carDriver = CarDriver::where('chauffeur_id', $chauffeur->id)->first();
    if (!$carDriver) {
        return back()->withErrors(['chauffeur_id' => 'Ce chauffeur n\'a pas de voiture assignée.']);
    }


        // // Récupérer le trajet choisi
        // $trip = Trip::find($request->trip_id);

        // // Vérifier que le chauffeur est bien à Dakar (ou à la ville de départ du trajet)
        // if ($carDriver->current_location !== $trip->departure) {
        //     return back()->withErrors(['chauffeur_id' => 'Le chauffeur n\'est pas à la ville de départ pour effectuer cette réservation.']);
        // }
        
        // Vérifier la disponibilité du chauffeur (pas de réservation avant 3 heures)
        $lastReservation = Reservation::where('cardriver_id', $carDriver->id)
        ->where('status', 'Confirmée')
        ->orderByDesc('date')
        ->orderByDesc('heure_ramassage')
        ->first();


        if ($lastReservation) {
            $lastReservationDateTime = Carbon::parse("{$lastReservation->date} {$lastReservation->heure_ramassage}");
            $requestDateTime = Carbon::parse("{$request->date} {$request->heure_ramassage}");
        
            if ($lastReservationDateTime->diffInHours($requestDateTime) < 3) {
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

        // Calcul du tarif
        $tarif = $this->calculerTarif($request->nb_personnes, $request->nb_valises, $request->nb_adresses);

        // Création de la réservation
        $reservation = Reservation::create([
            'client_id' => $client_id,
            'trip_id' => $request->trip_id,
            'date' => $request->date,
            'heure_ramassage' => $request->heure_ramassage,
            'heure_vol' => $request->heure_vol,
            'numero_vol' => $request->numero_vol,
            'nb_personnes' => $request->nb_personnes,
            'adresse_rammassage' => $request->adresse_rammassage,
            'nb_valises' => $request->nb_valises,
            'nb_adresses' => $request->nb_adresses,
            'tarif' => $tarif,
            'status' => 'En_attente',
            'cardriver_id' => $carDriver->id, // Stocke l'ID de la relation CarDriver
            'first_name' => $client->first_name,
            'last_name' => $client->last_name,
            'email' => $client->email,
        ]);


         // Assurez-vous que la réservation est chargée avec les relations nécessaires
         $reservation->load(['client', 'carDriver.chauffeur']);
         // Envoi des e-mails de réservation
         $this->envoyerEmailReservation($reservation, 'created');
         

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
            'adresse_rammassage' => 'required|string|max:255',
            'heure_vol' => 'required',
            // 'numero_vol' => 'required',
            // 'nb_personnes' => 'required|integer|min:1',
            'nb_valises' => 'required|integer|min:0',
            // 'nb_adresses' => 'required|integer|min:0',
            'phone_number' => ['nullable', 'regex:/^[0-9]{9}$/', 'unique:users,phone_number'],
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

        // Vérification de la voiture du chauffeur
        $car = $chauffeur->car_drivers()->with('car')->first()?->car;

        if (!$car) {
            return back()->withErrors(['date' => "Le chauffeur n'a pas de voiture assignée."]);
        }

        // Récupérer la relation CarDriver
        $carDriver = CarDriver::where('chauffeur_id', $chauffeur->id)->first();
        if (!$carDriver) {
            return back()->withErrors(['chauffeur_id' => 'Ce chauffeur n\'a pas de voiture assignée.']);
        }

        // // Récupérer le trajet choisi
        // $trip = Trip::find($request->trip_id);

        // // Vérifier que le chauffeur est bien à la ville de départ du trajet
        // if ($carDriver->current_location !== $trip->departure) {
        //     return back()->withErrors(['chauffeur_id' => 'Le chauffeur n\'est pas à la ville de départ pour effectuer cette réservation.']);
        // }

        // Vérifier la disponibilité du chauffeur (pas de réservation avant 3 heures)
        $lastReservation = Reservation::where('cardriver_id', $carDriver->id)
            ->where('status', 'Confirmée')
            ->orderByDesc('date')
            ->orderByDesc('heure_ramassage')
            ->first();

        if ($lastReservation) {
            $lastReservationDateTime = Carbon::parse("{$lastReservation->date} {$lastReservation->heure_ramassage}");
            $requestDateTime = Carbon::parse("{$request->date} {$request->heure_ramassage}");

            if ($lastReservationDateTime->diffInHours($requestDateTime) < 3) {
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

        // Calcul du tarif
        $tarif = $this->calculerTarif($request->nb_personnes, $request->nb_valises, $request->nb_adresses);

        if ($request->client_id) {
            // Client existant sélectionné
            $client = User::find($request->client_id);
        } else {
            // Création d'un nouveau client
            $client = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make(Str::random(12)),
                'phone_number' => $request->phone_number,
            ]);
        
            $client->assignRole('client');
        
            Mail::to($client->email)->send(new AccountCreatedMail($client, $client->password));
        }        

        // Création de la réservation
        $reservation = Reservation::create([
            'trip_id' => $request->trip_id,
            'client_id' => $client->id,
            'chauffeur_id' => $chauffeur->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'id_agent' => $idAgent,
            'adresse_rammassage' => $request->adresse_rammassage,
            'date' => $request->date,
            'heure_ramassage' => $request->heure_ramassage,
            'heure_vol' => $request->heure_vol,
            'numero_vol' => $request->numero_vol,
            'nb_personnes' => $request->nb_personnes,
            'nb_valises' => $request->nb_valises,
            'nb_adresses' => $request->nb_adresses,
            'tarif' => $tarif,
            'status' => 'En_attente',
            'phone_number' => $request->phone_number,
            'cardriver_id' => $carDriver->id,
        ]);

         // Assurez-vous que la réservation est chargée avec les relations nécessaires
    $reservation->load(['client', 'carDriver.chauffeur']);
        // Envoi des e-mails de réservation
        $this->envoyerEmailReservation($reservation, 'created');

        return redirect()->route('reservations.index')->with('success', 'Réservation ajoutée avec succès par l’agent.');
    }


    // private function findAvailableDriver($date, $heure_ramassage)
    // {
    //     return User::whereHas('roles', function ($query) {
    //         $query->where('name', 'chauffeur');
    //     })
    //     ->whereDoesntHave('reservations', function ($query) use ($date, $heure_ramassage) {
    //         $query->where('date', $date)
    //               ->where('heure_ramassage', '>=', Carbon::parse($heure_ramassage)->subHours(3))
    //               ->where('heure_ramassage', '<=', Carbon::parse($heure_ramassage)->addHours(3))
    //               ->where('status', 'Confirmée');
    //     })
    //     ->whereDoesntHave('cars.maintenances', function ($query) use ($date) {
    //         $query->where('jour', $date);
    //     })
        
    //         ->whereDoesntHave('reservations', function ($query) use ($date, $heure_ramassage) {
    //             $query->where('status', 'Confirmée')
    //                 ->whereDate('date', '=', $date)
    //                 ->whereRaw('TIMESTAMPDIFF(HOUR, heure_ramassage, ?) < 3', [$heure_ramassage]);
    //         })
    //         ->first();
        
    //     return $chauffeur;
    // }

    private function findAvailableDriver($date, $heure_ramassage)
{
    $chauffeurs = User::role('chauffeur')->get();
    $requestDateTime = Carbon::parse("$date $heure_ramassage");

    foreach ($chauffeurs as $chauffeur) {
        // Vérifier le jour de repos
        if ($chauffeur->day_off === Carbon::parse($date)->format('l')) {
            continue; // chauffeur en repos
        }

        $carDriver = $chauffeur->car_drivers()->first();
        if (!$carDriver) {
            continue; // pas de voiture assignée
        }

        // Vérifier si la voiture est en maintenance
        $maintenance = Maintenance::where('car_id', $carDriver->car_id)
            ->where('jour', $date)
            ->first();
        if ($maintenance) {
            continue; // voiture en maintenance
        }

        // Vérifier s'il a une réservation récente
        $lastReservation = Reservation::where('cardriver_id', $carDriver->id)
            ->where('status', 'Confirmée')
            ->orderByDesc('date')
            ->orderByDesc('heure_ramassage')
            ->first();

        if ($lastReservation) {
            $lastReservationDateTime = Carbon::parse("{$lastReservation->date} {$lastReservation->heure_ramassage}");
            if ($lastReservationDateTime->diffInHours($requestDateTime) < 3) {
                continue; // pas disponible à cause de la règle des 3 heures
            }
        }

        // Chauffeur trouvé
        return $chauffeur;
    }

    // Aucun chauffeur disponible
    return null;
}




    public function confirm(Reservation $reservation)
    {
        // Charge les relations nécessaires
        $reservation->load(['client', 'carDriver.chauffeur']);

        // Met à jour le statut et l'ID de l'agent
        $reservation->update([
            'status' => 'confirmée',
            'id_agent' => auth()->id(),  // Enregistre l’agent connecté
        ]);

        // Envoyer les e-mails lors de la confirmation
        $this->envoyerEmailReservation($reservation, 'confirmée');

        // Ajouter à Google Calendar
        $this->addToGoogleCalendar($reservation);

        // Ajouter 5 points au client
        if ($reservation->client) {
            $reservation->client->points += 5;
            $reservation->client->loyalty_points += 1;
            $reservation->client->save();
        }
        

        // Ajouter 1 point à l'agent (utilisateur connecté)
        $agent = auth()->user();
        if ($agent) {
            $agent->points += 5;
            $agent->save();
        }

        Invoice::create([
            'reservation_id' => $reservation->id,
            'amount' => $reservation->tarif,
            'status' => 'unpaid',
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'invoice_date' => now(),
        ]);

        return back()->with('success', 'Réservation confirmée.');
    }


    public function cancel(Reservation $reservation)
    {
        $now = Carbon::now();
        $heureRamassage = Carbon::parse($reservation->heure_ramassage);
    
        $reservation->load(['client', 'carDriver.chauffeur']);
    
        // if ($now->diffInMinutes($heureRamassage, false) <= 120) {
        //     return back()->withErrors(['status' => 'Annulation impossible moins de 2h avant le départ.']);
        // }
    
        // Mettre à jour le statut et l'agent qui a annulé
        try {
            $reservation->update([
                'status' => 'annulée',
                'id_agent' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    
        // Retirer 5 points au client
        if ($reservation->client) {
            $reservation->client->points = max(0, $reservation->client->points - 5);
            $reservation->client->loyalty_points = max(0, $reservation->client->loyalty_points - 0.5);
            $reservation->client->save();
        }
        
    
        // Envoyer email
        $this->envoyerEmailReservation($reservation, 'annulée');
    
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
         Mail::to('tourendeyeyande@gmail.com')->send(new ReservationCreated($reservation));
 
         if ($status === 'confirmée') {
             Mail::to($reservation->chauffeur->email)->send(new ReservationConfirmedDriver($reservation));
             Mail::to($reservation->client->email)->send(new ReservationConfirmedclient($reservation));
             Mail::to('tourendeyeyande@gmail.com')->send(new ReservationConfirmed($reservation));

         } elseif ($status === 'annulée') {
             Mail::to($reservation->chauffeur->email)->send(new ReservationCanceledDriver($reservation));
             Mail::to($reservation->client->email)->send(new ReservationCanceledclient($reservation));
             Mail::to('tourendeyeyande@gmail.com')->send(new ReservationCanceled($reservation));
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
        $reservations = Reservation::where('status', 'confirmée')->get();
        return view('reservations.confirmed', compact('reservations'));
    }

    public function cancelled()
    {
        // Récupérer toutes les réservations annulées
        $reservations = Reservation::where('status', 'annulée')->get();
    
        // Retourner la vue avec les réservations annulées
        return view('reservations.cancelled', compact('reservations'));
    }
    
    /**
     * Affiche une réservation spécifique.
     */
    public function show($id)
{
    $reservation = Reservation::with(['carDriver.chauffeur', 'carDriver.car', 'client', 'trip', 'carDriver'])->findOrFail($id);
    return view('reservations.show', compact('reservation'));
}


    /**
     * Met à jour une réservation.
     */
    public function update(Request $request, $id)
    {
        // Validation
        $validatedData = $request->validate([
            'date' => 'required|date',
            'heure_ramassage' => 'required|date_format:H:i',
            'heure_vol' => 'nullable|date_format:H:i',
            'chauffeur_id' => 'required|exists:users,id'
        ]);
    
        // Récupérer la réservation
        $reservation = Reservation::findOrFail($id);
    
        // Mettre à jour les champs
        $reservation->date = Carbon::parse($request->date)->format('Y-m-d');
        $reservation->heure_ramassage = $request->heure_ramassage;
        $reservation->heure_vol = $request->heure_vol;
    
        // Récupérer le CarDriver lié au chauffeur
        $carDriver = CarDriver::where('chauffeur_id', $request->chauffeur_id)->first();
    
        if ($carDriver) {
            $reservation->cardriver_id = $carDriver->id;
        }
    
        $reservation->save();
    
        return redirect()->route('reservations.index')->with('success', 'Réservation mise à jour avec succès.');
    }
    
    public function confirmedReservations()
    {
        $reservations = Reservation::with('chauffeur', 'client', 'car', 'trip', 'carDriver')
            ->where('status', 'Confirmée')
            ->paginate(10);

        return view('reservations.confirmed', compact('reservations'));
    }

    private function addToGoogleCalendar($reservation)
    {
        $client = $reservation->client;
        $chauffeur = $reservation->carDriver->chauffeur;
        $fly_number = $reservation-> numero_vol;
        $clientName = "{$client->first_name} {$client->last_name}";
        $clientPhone = $client->phone_number;
        $driverName =  "{$chauffeur->first_name}";
        $nb_personnes =  "{$reservation->nb_personnes}";
        $nb_valises =  "{$reservation->nb_valises}";
        $tarif =  "{$reservation->tarif}";
        $heure_ramassage =  "{$reservation->heure_ramassage}";
        $clientSummary = " $driverName/ {$reservation->trip->departure}{$reservation->trip->destination}/ $heure_ramassage/";
        $description = "Réservation avec $clientName 
        Téléphone : $clientPhone
        Numéro vol : $fly_number
        nb_personnes : $nb_personnes
        nb_valises : $nb_valises
        tarif : $tarif";
    
        // Formatage sécurisé des dates
        $start = Carbon::parse("{$reservation->date} {$reservation->heure_ramassage}");
        $end = $start->copy()->addHour();
    
        $googleClient = new \Google_Client();
        $googleClient->setAuthConfig(storage_path('app/google-calendar/credentials.json'));
        $googleClient->addScope(\Google_Service_Calendar::CALENDAR);
        $googleClient->setAccessType('offline');
    
        $tokenPath = storage_path('app/google-calendar/token.json');
        if (!file_exists($tokenPath)) {
            throw new \Exception("Le fichier de jeton n'existe pas. Veuillez authentifier votre application.");
        }
    
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $googleClient->setAccessToken($accessToken);
    
        if ($googleClient->isAccessTokenExpired()) {
            if (isset($accessToken['refresh_token'])) {
                $googleClient->fetchAccessTokenWithRefreshToken($accessToken['refresh_token']);
                file_put_contents($tokenPath, json_encode($googleClient->getAccessToken()));
            } else {
                throw new \Exception("Le jeton d'accès a expiré et aucun refresh token n'est disponible.");
            }
        }
    
        $service = new \Google_Service_Calendar($googleClient);
        $calendarId = config('services.google_calendar.calendar_id');
    
        $event = new \Google_Service_Calendar_Event([
            'summary' => $clientSummary,
            'description' => $description,
            'start' => [
                'dateTime' => $start->toIso8601String(),
                'timeZone' => config('services.google_calendar.timezone'),
            ],
            'end' => [
                'dateTime' => $end->toIso8601String(),
                'timeZone' => config('services.google_calendar.timezone'),
            ],
        ]);
    
        try {
            $service->events->insert($calendarId, $event);
        } catch (\Google_Service_Exception $e) {
            logger()->error('Erreur Google Calendar : ' . $e->getMessage());
            throw $e;
        }
    }
    
    public function mesReservationsClient()
{
    $client = Auth::user();

    // Vérifie que l'utilisateur est bien un client
    if (!$client->hasRole('client')) {
        abort(403, 'Accès non autorisé.');
    }

    // Récupère les réservations du client connecté
    $reservations = Reservation::with(['trip', 'carDriver.chauffeur', 'carDriver.car'])
        ->where('client_id', $client->id)
        ->orderByDesc('date')
        ->paginate(10);

    return view('reservations.client', compact('reservations'));
}


public function mesReservationsChauffeur()
{
    $chauffeur = Auth::user();

    // Vérifie que l'utilisateur est bien un chauffeur
    if (!$chauffeur->hasRole('chauffeur')) {
        abort(403, 'Accès non autorisé.');
    }

    // Récupère les IDs des relations CarDriver du chauffeur
    $carDriverIds = CarDriver::where('chauffeur_id', $chauffeur->id)->pluck('id');

    // Récupère les réservations assignées au chauffeur connecté via carDriver_id
    $reservations = Reservation::with(['trip', 'client', 'carDriver.car', 'chauffeur','car', 'trip', 'carDriver'])
        ->whereIn('cardriver_id', $carDriverIds)
        ->orderByDesc('date')
        ->paginate(10);

    return view('reservations.chauffeur', compact('reservations'));
}



public function agentCreateReservation()
{
    $trips = Trip::all();

    $chauffeurs = User::whereHas('roles', function ($query) {
        $query->where('name', 'chauffeur');
    })->get();

    $clients = User::whereHas('roles', function ($query) {
        $query->where('name', 'client');
    })->get();

    return view('reservations.agentcreatereservation', compact('chauffeurs', 'clients', 'trips'));
}


public function agentStoreReservation(Request $request)
{
    $request->validate([
        'trip_id' => 'required|exists:trips,id',
        'chauffeur_id' => 'required|exists:users,id',
        'client_id' => 'required|exists:users,id',
        'date' => 'required|date',
        'heure_ramassage' => 'required',
        'heure_vol' => 'required',
        'numero_vol' => 'required',
        'nb_personnes' => 'required|integer|min:1',
        'nb_valises' => 'required|integer|min:0',
        'nb_adresses' => 'required|integer|min:1',
        'status' => 'required|string|in:En_attente,Confirmée,Annulée',
        'adresse_rammassage' => 'required|string', // Ajoute cette ligne pour valider ce champ

    ]);

    $client = User::findOrFail($request->client_id);
    $chauffeur = User::findOrFail($request->chauffeur_id);

    $car = $chauffeur->car_drivers()->with('car')->first()?->car;
    if (!$car) {
        return back()->withErrors(['chauffeur_id' => "Ce chauffeur n'a pas de voiture assignée."]);
    }

    $carDriver = CarDriver::where('chauffeur_id', $chauffeur->id)->first();
    if (!$carDriver) {
        return back()->withErrors(['chauffeur_id' => 'Ce chauffeur n\'a pas de voiture assignée.']);
    }

    // Vérifications habituelles
    $lastReservation = Reservation::where('cardriver_id', $carDriver->id)
        ->where('status', 'Confirmée')
        ->orderByDesc('date')
        ->orderByDesc('heure_ramassage')
        ->first();

    if ($lastReservation) {
        $lastReservationDateTime = Carbon::parse("{$lastReservation->date} {$lastReservation->heure_ramassage}");
        $requestDateTime = Carbon::parse("{$request->date} {$request->heure_ramassage}");

        if ($lastReservationDateTime->diffInHours($requestDateTime) < 3) {
            return back()->withErrors(['date' => 'Le chauffeur ne peut pas être réservé moins de 3 heures après sa dernière réservation.']);
        }
    }

    if ($chauffeur->day_off === Carbon::parse($request->date)->format('l')) {
        return back()->withErrors(['date' => "Le chauffeur est en repos ce jour-là ({$chauffeur->day_off})."]);
    }

    $maintenance = Maintenance::where('car_id', $car->id)
        ->where('jour', $request->date)
        ->first();

    if ($maintenance) {
        return back()->withErrors(['date' => "La voiture du chauffeur est en maintenance ce jour-là."]);
    }

    $tarif = $this->calculerTarif($request->nb_personnes, $request->nb_valises, $request->nb_adresses);

    $reservation = Reservation::create([
        'client_id' => $client->id,
        'trip_id' => $request->trip_id,
        'date' => $request->date,
        'heure_ramassage' => $request->heure_ramassage,
        'heure_vol' => $request->heure_vol,
        'numero_vol' => $request->numero_vol,
        'nb_personnes' => $request->nb_personnes,
        'nb_valises' => $request->nb_valises,
        'nb_adresses' => $request->nb_adresses,
        'tarif' => $tarif,
        'status' => $request->status,
        'cardriver_id' => $carDriver->id,
        'first_name' => $client->first_name,
        'last_name' => $client->last_name,
        'email' => $client->email,
        'adresse_rammassage' => $request->adresse_rammassage, // Ajoute ce champ ici

    ]);

    $reservation->load(['client', 'carDriver.chauffeur']);
    $this->envoyerEmailReservation($reservation, 'created');

    return redirect()->route('reservations.index')->with('success', 'Réservation créée par l’agent avec succès.');
}


public function calendar(Request $request)
{
    // Récupérer l'ID du chauffeur connecté
    $chauffeurId = Auth::user()->id;
    
    // Récupérer les réservations du chauffeur pour le mois courant
    $month = $request->input('month', Carbon::now()->month); // Par défaut, le mois courant
    $year = $request->input('year', Carbon::now()->year); // Par défaut, l'année courante

    $reservations = Reservation::where('cardriver_id', $chauffeurId) // Utilisation de cardriver_id
    ->whereYear('date', $year)
    ->whereMonth('date', $month)
    ->orderBy('date')
    ->get();


    // Organiser les réservations par date
    $reservationsByDay = $reservations->groupBy(function ($item) {
        return Carbon::parse($item->date)->format('Y-m-d'); // Format pour les jours
    });

    // Passer les réservations à la vue
    return view('reservations.drivercalendar', compact('reservationsByDay', 'month', 'year'));
}

public function storeAvis(Request $request, Reservation $reservation)
{
    $request->validate([
        'note' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);

    $reservation->avis()->create([
        'note' => $request->note,
        'comment' => $request->commentaire,
    ]);

    return redirect()->back()->with('success', 'Merci pour votre avis !');
}


public function checkAvailability(Request $request)
{
    $request->validate([
        'trip_id' => 'required|exists:trips,id',
        'date' => 'required|date',
        'heure_ramassage' => 'required',
    ]);

    $trip = Trip::find($request->trip_id);
    $date = $request->date;
    $heureRamassage = $request->heure_ramassage;

    $chauffeurs = User::role('chauffeur')->get();

    foreach ($chauffeurs as $chauffeur) {
        // Vérification jour de repos
        if ($chauffeur->day_off === Carbon::parse($date)->format('l')) {
            continue;
        }

        // Vérification voiture
        $carDriver = CarDriver::where('chauffeur_id', $chauffeur->id)->first();
        if (!$carDriver || !$carDriver->car) {
            continue;
        }

        // Vérification maintenance
        $maintenance = Maintenance::where('car_id', $carDriver->car->id)
            ->where('jour', $date)
            ->first();
        if ($maintenance) {
            continue;
        }

        // ⚡️ NOUVEAU : vérifier TOUTES les réservations du chauffeur
        $reservations = Reservation::where('cardriver_id', $carDriver->id)
            ->where('status', 'Confirmée')
            ->where('date', $date) // on peut même vérifier toutes dates proches si besoin
            ->get();

        $disponible = true;

        foreach ($reservations as $reservation) {
            $reservationDateTime = Carbon::parse("{$reservation->date} {$reservation->heure_ramassage}");
            $requestDateTime = Carbon::parse("{$date} {$heureRamassage}");

            if ($reservationDateTime->diffInHours($requestDateTime) < 3) {
                $disponible = false;
                break; // Ce chauffeur a un conflit de réservation
            }
        }

        if ($disponible) {
            // Chauffeur trouvé disponible
            return response()->json([
                'available' => true,
                'chauffeur' => $chauffeur->only(['id', 'first_name', 'last_name']),
                'car' => $carDriver->car->only(['id', 'immatriculation', 'model']),
            ]);
        }
    }

    // Aucun chauffeur disponible
    return response()->json([
        'available' => false,
        'message' => 'Aucun chauffeur disponible pour cette date et heure.',
    ]);
}

public function showCalendar()
{
    $googleClient = new \Google_Client();
    $googleClient->setAuthConfig(storage_path('app/google-calendar/credentials.json'));
    $googleClient->addScope(\Google_Service_Calendar::CALENDAR);
    $googleClient->setAccessType('offline');

    $tokenPath = storage_path('app/google-calendar/token.json');
    if (!file_exists($tokenPath)) {
        throw new \Exception("Token manquant.");
    }

    $accessToken = json_decode(file_get_contents($tokenPath), true);
    $googleClient->setAccessToken($accessToken);

    if ($googleClient->isAccessTokenExpired()) {
        if (isset($accessToken['refresh_token'])) {
            $googleClient->fetchAccessTokenWithRefreshToken($accessToken['refresh_token']);
            file_put_contents($tokenPath, json_encode($googleClient->getAccessToken()));
        } else {
            throw new \Exception("Jeton expiré.");
        }
    }

    $service = new \Google_Service_Calendar($googleClient);
    $calendarId = config('services.google_calendar.calendar_id');
    $googleEvents = $service->events->listEvents($calendarId);

    $events = [];
    foreach ($googleEvents->getItems() as $event) {
        $events[] = [
            'title' => $event->getSummary(),
            'start' => $event->getStart()->getDateTime() ?: $event->getStart()->getDate(),
            'end' => $event->getEnd()->getDateTime() ?: $event->getEnd()->getDate(),
            'description' => $event->getDescription(), // <== Ajout
        ];
    }

    return view('calendars.calendar', compact('events'));
}

}
