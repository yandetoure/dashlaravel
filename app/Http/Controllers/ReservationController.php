<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Carbon\Carbon;
use Google_Client;
use App\Models\Car;
use App\Models\Actu;
use App\Models\Info;
use App\Models\Trip;
use App\Models\Course;
use App\Models\User;
use App\Models\Invoice;
use App\Models\CarDriver;
use App\Models\DriverGroup;
use App\Models\Maintenance;
use App\Models\Reservation;
use Illuminate\Support\Str;
use Google_Service_Calendar;
use Illuminate\Http\Request;
use App\Mail\AccountCreatedMail;
use App\Mail\ReservationCreated as ReservationCreatedMail;
use App\Mail\ReservationUpdated;
use App\Mail\ReservationCanceled;
use App\Mail\ReservationConfirmed;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationCreatedclient;
use App\Mail\ReservationCanceledclient;
use App\Mail\ReservationCanceledDriver;
use App\Mail\ReservationConfirmedclient;
use App\Mail\ReservationConfirmedDriver;
use App\Mail\ReservationCreatedProspect;
use App\Mail\ReservationAdminNotification;
use App\Mail\ReservationClientNotification;


class ReservationController extends Controller
{
    /**
     * Affiche la liste des réservations.
     */
    public function index()
    {
        $reservations = Reservation::with(['carDriver.chauffeur', 'carDriver.car', 'client', 'trip'])
            ->orderBy('date', 'desc')
            ->orderBy('heure_ramassage', 'desc')
            ->paginate(10);

        // Récupérer tous les chauffeurs avec leur disponibilité pour aujourd'hui
        $chauffeurs = User::role('chauffeur')->get();

        // Pour chaque réservation, calculer les chauffeurs disponibles pour sa date
        foreach ($reservations as $reservation) {
            $reservation->available_drivers = $this->getAvailableDriversForDate($reservation->date, $reservation->heure_ramassage);
        }

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
        $trips = Trip::all();

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

        // Calcul du tarif
        $tarif = $this->calculerTarif($request->nb_personnes, $request->nb_valises, $request->nb_adresses);

        // Création de la réservation SANS chauffeur assigné (sera assigné plus tard par un agent)
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
            'cardriver_id' => null, // Pas de chauffeur assigné au départ
            'first_name' => $client->first_name,
            'last_name' => $client->last_name,
            'email' => $client->email,
        ]);

        // Assurez-vous que la réservation est chargée avec les relations nécessaires
        $reservation->load(['client']);
        // Envoi des e-mails de réservation
        $this->envoyerEmailReservation($reservation, 'created');

        return redirect()->route('reservations.index')->with('success', 'Réservation ajoutée avec succès. Un agent vous contactera pour confirmer et assigner un chauffeur.');
    }

    public function storeByAgent(Request $request)
    {
        // Nettoyer le numéro de téléphone (enlever +221, espaces, etc.)
        $phone_number = null;
        if ($request->phone_number) {
            $phone_number = preg_replace('/[^0-9]/', '', $request->phone_number);
            // Si le numéro commence par 221, le retirer
            if (strlen($phone_number) === 12 && substr($phone_number, 0, 3) === '221') {
                $phone_number = substr($phone_number, 3);
            }
        }

        $request->merge(['phone_number' => $phone_number]);

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
            'phone_number' => ['nullable', 'regex:/^(77|76|70|78)[0-9]{7}$/'],
        ]);

        // Vérification qu'au moins un choix de client est fait
        if (!$request->client_id && (!$request->last_name || !$request->first_name)) {
            return back()->withErrors(['last_name' => 'Veuillez sélectionner un client existant ou entrer un nom et un prénom.']);
        }

        $user = Auth::user();
        $idAgent = $user->id;

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
                'password' => Hash::make('password123'),
                'phone_number' => $phone_number,
            ]);

            $client->assignRole('client');

            Mail::to($client->email)->send(new AccountCreatedMail($client, $client->password));
        }

        // Création de la réservation SANS chauffeur assigné (sera assigné plus tard)
        $reservation = Reservation::create([
            'trip_id' => $request->trip_id,
            'client_id' => $client->id,
            'chauffeur_id' => null, // Pas de chauffeur assigné au départ
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
            'phone_number' => $phone_number,
            'cardriver_id' => null, // Pas de chauffeur assigné au départ
        ]);

         // Assurez-vous que la réservation est chargée avec les relations nécessaires
        $reservation->load(['client']);
        // Envoi des e-mails de réservation
        $this->envoyerEmailReservation($reservation, 'created');

        return redirect()->route('reservations.index')->with('success', 'Réservation créée par l\'agent avec succès. Un chauffeur sera assigné lors de la confirmation.');
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
    // Toujours retourner le premier chauffeur disponible
    $chauffeur = User::role('chauffeur')->first();

    if ($chauffeur) {
        return $chauffeur;
    }

    // Si aucun chauffeur n'existe, retourner null
    return null;
}

    public function confirm(Reservation $reservation)
    {
        // Charge les relations nécessaires
        $reservation->load(['client']);

        // Si aucun chauffeur n'est assigné, en rechercher un disponible
        if (!$reservation->cardriver_id) {
            $chauffeur = $this->findAvailableDriver($reservation->date, $reservation->heure_ramassage);
            
            if ($chauffeur) {
                // Récupérer la relation CarDriver
                $carDriver = CarDriver::where('chauffeur_id', $chauffeur->id)->first();
                
                if ($carDriver) {
                    $reservation->update([
                        'status' => 'confirmée',
                        'id_agent' => auth()->id(),
                        'cardriver_id' => $carDriver->id,
                        'chauffeur_id' => $chauffeur->id,
                    ]);
                } else {
                    return back()->withErrors(['chauffeur' => 'Aucun chauffeur disponible pour cette réservation.']);
                }
            } else {
                return back()->withErrors(['chauffeur' => 'Aucun chauffeur disponible pour cette réservation.']);
            }
        } else {
            // Met à jour le statut et l'ID de l'agent
            $reservation->update([
                'status' => 'confirmée',
                'id_agent' => auth()->id(),  // Enregistre l'agent connecté
            ]);
        }

        // Recharger les relations après la mise à jour
        $reservation->load(['client', 'carDriver.chauffeur']);

        // Créer une Course automatiquement lors de la confirmation
        if (!$reservation->course) {
            Course::create([
                'reservation_id' => $reservation->id,
                'statut' => Course::STATUT_EN_ATTENTE,
            ]);
        }

        // Envoyer les e-mails lors de la confirmation
        $this->envoyerEmailReservation($reservation, 'confirmée');

        // Ajouter à Google Calendar
        try {
            $this->addToGoogleCalendar($reservation);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'ajout à Google Calendar: ' . $e->getMessage());
            // Continuer même si Google Calendar échoue
        }

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

        $invoice = Invoice::create([
            'reservation_id' => $reservation->id,
            'amount' => $reservation->tarif,
            'status' => 'en_attente',
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'invoice_date' => now(),
        ]);

        // Générer automatiquement l'URL de checkout NabooPay
        $this->generateCheckoutUrlForInvoice($invoice);

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
         // Email à l'admin (toujours envoyé)
         try {
             Mail::to('cproservices221@gmail.com')->send(new ReservationAdminNotification($reservation, $status));
         } catch (\Exception $e) {
             \Log::error('Erreur envoi email admin: ' . $e->getMessage());
         }

         // Email au client (si ce n'est pas un prospect)
         if ($reservation->client_id && $reservation->client) {
             try {
                 Mail::to($reservation->client->email)->send(new ReservationCreatedclient($reservation));
             } catch (\Exception $e) {
                 \Log::error('Erreur envoi email client: ' . $e->getMessage());
             }
         } elseif ($reservation->email) {
             // Si c'est un prospect, utiliser l'email de la réservation
             try {
                 Mail::to($reservation->email)->send(new ReservationCreatedclient($reservation));
             } catch (\Exception $e) {
                 \Log::error('Erreur envoi email prospect: ' . $e->getMessage());
             }
         }

         if ($status === 'confirmée') {
             // Email au chauffeur
             if ($reservation->carDriver && $reservation->carDriver->chauffeur) {
                 try {
                     Mail::to($reservation->carDriver->chauffeur->email)->send(new ReservationConfirmedDriver($reservation));
                 } catch (\Exception $e) {
                     \Log::error('Erreur envoi email chauffeur: ' . $e->getMessage());
                 }
             }

             // Email au client ou prospect avec numéro du chauffeur
             $clientEmail = $reservation->client ? $reservation->client->email : $reservation->email;
             if ($clientEmail) {
                 try {
                     Mail::to($clientEmail)->send(new ReservationClientNotification($reservation, 'confirmed'));
                 } catch (\Exception $e) {
                     \Log::error('Erreur envoi email confirmation client: ' . $e->getMessage());
                 }
             }

             // Email à l'admin
             try {
                 Mail::to('cproservices221@gmail.com')->send(new ReservationAdminNotification($reservation, 'confirmed'));
             } catch (\Exception $e) {
                 \Log::error('Erreur envoi email confirmation admin: ' . $e->getMessage());
             }

         } elseif ($status === 'annulée') {
             // Email au chauffeur
             if ($reservation->carDriver && $reservation->carDriver->chauffeur) {
                 try {
                     Mail::to($reservation->carDriver->chauffeur->email)->send(new ReservationCanceledDriver($reservation));
                } catch (\Exception $e) {
                     \Log::error('Erreur envoi email chauffeur annulation: ' . $e->getMessage());
                 }
             }

             // Email au client ou prospect avec numéro du chauffeur
             $clientEmail = $reservation->client ? $reservation->client->email : $reservation->email;
             if ($clientEmail) {
                 try {
                     Mail::to($clientEmail)->send(new ReservationClientNotification($reservation, 'cancelled'));
                 } catch (\Exception $e) {
                     \Log::error('Erreur envoi email annulation client: ' . $e->getMessage());
                 }
             }

                          // Email à l'admin
             try {
                 Mail::to('cproservices221@gmail.com')->send(new ReservationAdminNotification($reservation, 'cancelled'));
             } catch (\Exception $e) {
                 \Log::error('Erreur envoi email annulation admin: ' . $e->getMessage());
             }
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
     * Affiche le formulaire d'édition d'une réservation.
     */
    public function edit($id)
    {
        $reservation = Reservation::with(['carDriver.chauffeur', 'carDriver.car', 'client', 'trip'])->findOrFail($id);

        // Récupérer les chauffeurs disponibles pour la date de la réservation
        $availableDrivers = $this->getAvailableDriversForDate($reservation->date, $reservation->heure_ramassage);

        return view('reservations.edit', compact('reservation', 'availableDrivers'));
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

        // Récupérer ou créer le CarDriver lié au chauffeur
        $carDriver = CarDriver::where('chauffeur_id', $request->chauffeur_id)->first();

        if (!$carDriver) {
            // Créer un nouveau CarDriver si il n'existe pas
            $carDriver = CarDriver::create([
                'chauffeur_id' => $request->chauffeur_id,
                'car_id' => null // Pas de voiture assignée pour l'instant
            ]);
        }

        $reservation->cardriver_id = $carDriver->id;
        $reservation->save();

        // Charger les relations nécessaires pour l'email
        $reservation->load(['client', 'carDriver.chauffeur', 'trip']);

        // Envoyer notification à l'admin
        try {
            Mail::to('cproservices221@gmail.com')->send(new ReservationAdminNotification($reservation, 'updated'));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email notification modification admin: ' . $e->getMessage());
        }

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
        // Vérifications de sécurité
        if (!$reservation->client) {
            \Log::error('Réservation sans client: ' . $reservation->id);
            return false;
        }

        if (!$reservation->carDriver || !$reservation->carDriver->chauffeur) {
            \Log::error('Réservation sans chauffeur assigné: ' . $reservation->id);
            return false;
        }

        if (!$reservation->trip) {
            \Log::error('Réservation sans trajet: ' . $reservation->id);
            return false;
        }

        $client = $reservation->client;
        $chauffeur = $reservation->carDriver->chauffeur;
        $fly_number = $reservation->numero_vol;
        $clientName = "{$client->first_name} {$client->last_name}";
        $clientPhone = $client->phone_number ?? 'Non renseigné';
        $driverName = "{$chauffeur->first_name}";
        $nb_personnes = "{$reservation->nb_personnes}";
        $nb_valises = "{$reservation->nb_valises}";
        $tarif = "{$reservation->tarif}";
        $heure_ramassage = "{$reservation->heure_ramassage}";
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
            return true;
        } catch (\Google_Service_Exception $e) {
            \Log::error('Erreur Google Calendar : ' . $e->getMessage());
            return false;
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

    return redirect()->route("reservations.index")->with("success", "Réservation créée par l'agent avec succès.");
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

    // Toujours retourner que c'est disponible
    return response()->json([
        'available' => true,
        'message' => '✅ Parfait ! Nous avons de la disponibilité pour cette date et heure. Pour confirmer votre réservation, veuillez nous contacter au +221 77 705 67 67 ou via WhatsApp au +221 77 705 69 69.'
    ]);
}

// public function showCalendar()
// {
//     $googleClient = new \Google_Client();
//     $googleClient->setAuthConfig(storage_path('app/google-calendar/credentials.json'));
//     $googleClient->addScope(\Google_Service_Calendar::CALENDAR);
//     $googleClient->setAccessType('offline');

//     $tokenPath = storage_path('app/google-calendar/token.json');
//     if (!file_exists($tokenPath)) {
//         throw new \Exception("Token manquant.");
//     }

//     $accessToken = json_decode(file_get_contents($tokenPath), true);
//     $googleClient->setAccessToken($accessToken);

//     if ($googleClient->isAccessTokenExpired()) {
//         if (isset($accessToken['refresh_token'])) {
//             $googleClient->fetchAccessTokenWithRefreshToken($accessToken['refresh_token']);
//             file_put_contents($tokenPath, json_encode($googleClient->getAccessToken()));
//         } else {
//             throw new \Exception("Jeton expiré.");
//         }
//     }

//     $service = new \Google_Service_Calendar($googleClient);
//     $calendarId = config('services.google_calendar.calendar_id');
//     $googleEvents = $service->events->listEvents($calendarId);

//     $events = [];
//     foreach ($googleEvents->getItems() as $event) {
//         $events[] = [
//             'title' => $event->getSummary(),
//             'start' => $event->getStart()->getDateTime() ?: $event->getStart()->getDate(),
//             'end' => $event->getEnd()->getDateTime() ?: $event->getEnd()->getDate(),
//             'description' => $event->getDescription(), // <== Ajout
//         ];
//     }

//     return view('calendars.calendar', compact('events'));
// }

public function showCalendar()
{
    $user = auth()->user();

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
        // Récupérer les propriétés customisées
        $extendedProps = $event->getExtendedProperties();
        $sharedProps = $extendedProps ? $extendedProps->getShared() : [];

        // Extraire client_id ou chauffeur_id si présents
        $clientId = isset($sharedProps['client_id']) ? $sharedProps['client_id'] : null;
        $chauffeurId = isset($sharedProps['chauffeur_id']) ? $sharedProps['chauffeur_id'] : null;

        // Filtrage selon l'utilisateur connecté
        if ($user->hasRole('chauffeur') && $chauffeurId != $user->id) {
            continue; // Non concerné
        }
        if ($user->hasRole('client') && $clientId != $user->id) {
            continue; // Non concerné
        }
        // Si c'est un admin ou autre, affiche tout

        $events[] = [
            'title' => $event->getSummary(),
            'start' => $event->getStart()->getDateTime() ?: $event->getStart()->getDate(),
            'end' => $event->getEnd()->getDateTime() ?: $event->getEnd()->getDate(),
            'description' => $event->getDescription(),
        ];
    }

    return view('calendars.calendar', compact('events'));
}



    public function storeByProspect(Request $request)
    {
        // Nettoyer le numéro de téléphone (enlever +221, espaces, etc.)
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        // Si le numéro commence par 221, le retirer
        if (strlen($phone) === 12 && substr($phone, 0, 3) === '221') {
            $phone = substr($phone, 3);
        }
        $request->merge(['phone' => $phone]);

        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'regex:/^(77|76|70|78)[0-9]{7}$/'],
            'date' => 'required|date',
            'heure_ramassage' => 'required',
            'adresse_rammassage' => 'required|string|max:255',
            'nb_personnes' => 'required|integer|min:1',
            'nb_valises' => 'required|integer|min:0',
        ]);

        try {
            // Calcul du tarif
            $tarif = $this->calculerTarif($request->nb_personnes, $request->nb_valises, 0);

            // Création de la réservation PROSPECT (sans client_id ni chauffeur_id)
            $reservation = Reservation::create([
                'trip_id' => $request->trip_id,
                'client_id' => null, // PAS DE CLIENT - C'EST UN PROSPECT
                'chauffeur_id' => null, // PAS DE CHAUFFEUR ASSIGNÉ ENCORE
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'adresse_rammassage' => $request->adresse_rammassage,
                'date' => $request->date,
                'heure_ramassage' => $request->heure_ramassage,
                'nb_personnes' => $request->nb_personnes,
                'nb_valises' => $request->nb_valises,
                'tarif' => $tarif,
                'status' => 'En_attente', // EN ATTENTE DE CONFIRMATION PAR UN AGENT
                'phone_number' => $phone, // Utiliser le numéro nettoyé
                'cardriver_id' => null, // PAS DE CHAUFFEUR ASSIGNÉ ENCORE
            ]);

            // Envoi des e-mails de notification pour prospects
            $this->envoyerEmailProspect($reservation);

            // Récupérer les informations du trajet pour l'affichage
            $trip = Trip::find($request->trip_id);

            // Retourner la vue avec les données de la réservation
            return view('welcome', [
                'reservation' => $reservation,
                'trip' => $trip,
                'showReservationModal' => true,
                'trips' => Trip::all(), // Nécessaire pour le formulaire
                'actus' => Actu::all(), // Nécessaire pour la page d'accueil
                'infos' => Info::all(), // Nécessaire pour la page d'accueil
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de la réservation: ' . $e->getMessage());

            // Retourner la vue avec l'erreur
            return view('welcome', [
                'error' => 'Une erreur est survenue lors de la création de votre réservation. Veuillez réessayer.',
                'trips' => Trip::all(),
                'actus' => Actu::all(),
                'infos' => Info::all(),
            ]);
        }
    }

    // Nouvelle méthode pour envoyer des emails pour les prospects
    private function envoyerEmailProspect(Reservation $reservation)
    {
        // Email à l'entreprise pour notification d'une nouvelle demande prospect
        try {
            Mail::to('cproservices221@gmail.com')->send(new ReservationCreatedMail($reservation));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email entreprise prospect: ' . $e->getMessage());
        }

        // Email de confirmation au prospect
        try {
            Mail::to($reservation->email)->send(new ReservationCreatedProspect($reservation));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email prospect: ' . $e->getMessage());
        }
    }

    /**
     * Obtenir la disponibilité d'un chauffeur selon le planning des groupes
     */
    private function getChauffeurDisponibilite(User $chauffeur, $date = null)
    {
        try {
            // Si aucune date n'est fournie, utiliser la date actuelle
            if (!$date) {
                $date = Carbon::today();
            } else {
                $date = Carbon::parse($date);
            }

            $disponibilite = [];

            // Vérifier d'abord le planning des groupes de chauffeurs
            $disponibilitePlanning = $this->getDisponibilitePlanning($chauffeur, $date);

            // Vérifier ensuite les réservations existantes
            $reservationsAujourdhui = Reservation::where('cardriver_id', $chauffeur->id)
                ->where('date', $date->format('Y-m-d'))
                ->where('status', '!=', 'Annulée')
                ->get();

            // Combiner planning et réservations
            if ($disponibilitePlanning === 'En repos') {
                $disponibilite['aujourdhui'] = 'En repos';
            } else {
                $disponibilite['aujourdhui'] = $reservationsAujourdhui->isEmpty() ? 'Disponible' : 'Occupé';
            }

            return $disponibilite;

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la vérification de disponibilité: ' . $e->getMessage());
            return ['aujourdhui' => 'Disponible']; // En cas d'erreur, considérer comme disponible
        }
    }

    /**
     * Obtenir la disponibilité d'un chauffeur selon le planning des groupes
     */
    private function getDisponibilitePlanning(User $chauffeur, Carbon $date)
    {
        try {
            // Trouver le groupe auquel appartient le chauffeur
            $driverGroup = DriverGroup::where(function($query) use ($chauffeur) {
                $query->where('driver_1_id', $chauffeur->id)
                      ->orWhere('driver_2_id', $chauffeur->id)
                      ->orWhere('driver_3_id', $chauffeur->id)
                      ->orWhere('driver_4_id', $chauffeur->id);
            })->where('is_active', true)->first();

            if (!$driverGroup) {
                return 'Disponible'; // Pas de groupe = disponible par défaut
            }

            // Vérifier si le chauffeur est en repos selon le planning
            $restDrivers = $driverGroup->getRestDaysForDate($date);

            if (in_array($chauffeur->id, $restDrivers)) {
                return 'En repos';
            }

            return 'Disponible';

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la vérification du planning: ' . $e->getMessage());
            return 'Disponible'; // En cas d'erreur, considérer comme disponible
        }
    }

    /**
     * Vérifier si un chauffeur est en repos
     */
    private function estChauffeurEnRepos(User $chauffeur)
    {
        try {
            // Vérifier d'abord le planning des groupes de chauffeurs
            $disponibilitePlanning = $this->getDisponibilitePlanning($chauffeur, Carbon::today());

            if ($disponibilitePlanning === 'En repos') {
                return true;
            }

            // Vérifier s'il a un jour de repos assigné manuellement
            if (isset($chauffeur->day_off) && $chauffeur->day_off) {
                $aujourdhui = Carbon::today()->format('l'); // Jour de la semaine en anglais
                $joursRepos = explode(',', $chauffeur->day_off);

                // Convertir les jours français en anglais si nécessaire
                $joursMapping = [
                    'Lundi' => 'Monday',
                    'Mardi' => 'Tuesday',
                    'Mercredi' => 'Wednesday',
                    'Jeudi' => 'Thursday',
                    'Vendredi' => 'Friday',
                    'Samedi' => 'Saturday',
                    'Dimanche' => 'Sunday'
                ];

                foreach ($joursRepos as $jour) {
                    $jour = trim($jour);
                    if (isset($joursMapping[$jour])) {
                        $jour = $joursMapping[$jour];
                    }

                    if ($aujourdhui === $jour) {
                        return true; // Chauffeur en repos aujourd'hui
                    }
                }
            }

            return false; // Chauffeur pas en repos

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la vérification du repos: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer les chauffeurs avec leur disponibilité selon une date spécifique
     */
    private function getChauffeursDisponiblesPourDate($date = null)
    {
        $chauffeurs = User::role('chauffeur')->get()->map(function ($chauffeur) use ($date) {
            $chauffeur->disponibilite = $this->getChauffeurDisponibilite($chauffeur, $date);
            $chauffeur->en_repos = $this->estChauffeurEnRepos($chauffeur);
            return $chauffeur;
        });

        return $chauffeurs;
    }



    /**
     * API pour récupérer les chauffeurs disponibles selon une date
     */
    public function getChauffeursDisponibles(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required|date',
                'heure_ramassage' => 'nullable|date_format:H:i'
            ]);

            $availableDrivers = $this->getAvailableDriversForDate($request->date, $request->heure_ramassage);

            return response()->json($availableDrivers);
        } catch (\Exception $e) {
            \Log::error('Erreur dans getChauffeursDisponibles: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la récupération des chauffeurs'], 500);
        }
    }

    /**
     * Route pour récupérer les chauffeurs disponibles pour une date spécifique
     */
    public function getAvailableDriversForReservation(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required|date',
                'heure_ramassage' => 'nullable|date_format:H:i'
            ]);

            $availableDrivers = $this->getAvailableDriversForDate($request->date, $request->heure_ramassage);

            return response()->json([
                'success' => true,
                'drivers' => $availableDrivers
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur dans getAvailableDriversForReservation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la récupération des chauffeurs'
            ], 500);
        }
    }

    /**
     * Récupère les chauffeurs disponibles pour une date spécifique
     */
    private function getAvailableDriversForDate($date, $heure_ramassage = null)
    {
        $dateCarbon = Carbon::parse($date);

        // Récupérer tous les chauffeurs
        $chauffeurs = User::role('chauffeur')->get();

        $availableDrivers = [];

        foreach ($chauffeurs as $chauffeur) {
            $isAvailable = true;
            $reason = null;

            // 1. Vérifier la disponibilité selon les groupes de chauffeurs
            $driverGroup = DriverGroup::where(function($query) use ($chauffeur) {
                $query->where('driver_1_id', $chauffeur->id)
                      ->orWhere('driver_2_id', $chauffeur->id)
                      ->orWhere('driver_3_id', $chauffeur->id)
                      ->orWhere('driver_4_id', $chauffeur->id);
            })->where('is_active', true)->first();

            if ($driverGroup) {
                // Vérifier si le chauffeur est en repos selon le planning du groupe
                $restDrivers = $driverGroup->getRestDaysForDate($date);
                if (in_array($chauffeur->id, $restDrivers)) {
                    $isAvailable = false;
                    $reason = 'En repos selon le planning du groupe';
                }
            } else {
                // Si le chauffeur n'est dans aucun groupe actif, vérifier son jour de repos personnel
                if ($chauffeur->day_off) {
                    $dayOfWeek = $dateCarbon->format('l'); // Jour de la semaine (Monday, Tuesday, etc.)

                    // Convertir les jours français en anglais si nécessaire
                    $joursMapping = [
                        'Lundi' => 'Monday',
                        'Mardi' => 'Tuesday',
                        'Mercredi' => 'Wednesday',
                        'Jeudi' => 'Thursday',
                        'Vendredi' => 'Friday',
                        'Samedi' => 'Saturday',
                        'Dimanche' => 'Sunday'
                    ];

                    $jourRepos = $chauffeur->day_off;
                    if (isset($joursMapping[$jourRepos])) {
                        $jourRepos = $joursMapping[$jourRepos];
                    }

                    if ($jourRepos === $dayOfWeek) {
                        $isAvailable = false;
                        $reason = 'En repos ce jour-là';
                    }
                }
            }

            // 2. Vérifier s'il a une voiture assignée
            $carDriver = CarDriver::where('chauffeur_id', $chauffeur->id)->first();
            if (!$carDriver) {
                $isAvailable = false;
                $reason = 'Aucune voiture assignée';
            } else {
                // 3. Vérifier si la voiture est en maintenance
                $maintenance = Maintenance::where('car_id', $carDriver->car_id)
                    ->where('jour', $date)
                    ->first();

                if ($maintenance) {
                    $isAvailable = false;
                    $reason = 'Voiture en maintenance';
                }
            }

            // 4. Vérifier les réservations existantes (si une heure est spécifiée)
            if ($heure_ramassage && $isAvailable && $carDriver) {
                $existingReservations = Reservation::where('cardriver_id', $carDriver->id)
                    ->where('status', 'Confirmée')
                    ->where('date', $date)
                    ->get();

                foreach ($existingReservations as $reservation) {
                    $reservationTime = Carbon::parse("{$reservation->date} {$reservation->heure_ramassage}");
                    $requestTime = Carbon::parse("{$date} {$heure_ramassage}");

                    // Vérifier si les réservations se chevauchent (moins de 3 heures d'écart)
                    if ($reservationTime->diffInHours($requestTime) < 3) {
                        $isAvailable = false;
                        $reason = 'Réservation existante';
                        break;
                    }
                }
            }

            $availableDrivers[] = [
                'id' => $chauffeur->id,
                'first_name' => $chauffeur->first_name,
                'last_name' => $chauffeur->last_name,
                'is_available' => $isAvailable,
                'reason' => $reason,
                'group_name' => $driverGroup ? $driverGroup->group_name : 'Aucun groupe'
            ];
        }

        return $availableDrivers;
    }

    /**
     * Générer automatiquement l'URL de checkout pour une facture
     */
    private function generateCheckoutUrlForInvoice(Invoice $invoice)
    {
        try {
            if (!$invoice->reservation) {
                \Log::warning('Impossible de générer l\'URL de checkout: réservation manquante', [
                    'invoice_id' => $invoice->id
                ]);
                return false;
            }

            $nabooPayService = app(\App\Services\NabooPayService::class);
            $result = $nabooPayService->createReservationTransaction($invoice->reservation);
            
            if (isset($result['checkout_url'])) {
                $invoice->update([
                    'payment_url' => $result['checkout_url'],
                    'transaction_id' => $result['transaction_id'] ?? null
                ]);
                
                \Log::info('URL de checkout générée automatiquement', [
                    'invoice_id' => $invoice->id,
                    'checkout_url' => $result['checkout_url']
                ]);
                
                return true;
            } else {
                \Log::error('Impossible de générer l\'URL de checkout', [
                    'invoice_id' => $invoice->id,
                    'result' => $result
                ]);
                return false;
            }
        } catch (\Exception $e) {
            \Log::error('Erreur génération URL de checkout automatique', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
