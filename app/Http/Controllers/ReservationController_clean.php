<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Carbon\Carbon;
use Google_Client;
use App\Models\Car;
use App\Models\Actu;
use App\Models\Info;
use App\Models\Trip;
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
use App\Mail\ReservationCreated;
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
use App\Mail\ReservationCreatedDriver;
use App\Mail\ReservationCanceledclient;
use App\Mail\ReservationCanceledDriver;
use App\Mail\ReservationConfirmedclient;
use App\Mail\ReservationConfirmedDriver;
use App\Mail\ReservationCreatedProspect;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer tous les chauffeurs avec leur disponibilité et statut de repos
        $chauffeurs = User::role('chauffeur')->get()->map(function ($chauffeur) {
            $chauffeur->disponibilite = $this->getChauffeurDisponibilite($chauffeur);
            $chauffeur->en_repos = $this->estChauffeurEnRepos($chauffeur);
            return $chauffeur;
        });

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

    // ... autres méthodes existantes ...

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
            
            $demain = $date->copy()->addDay();
            $disponibilite = [];

            // Vérifier d'abord le planning des groupes de chauffeurs
            $disponibilitePlanning = $this->getDisponibilitePlanning($chauffeur, $date);
            $disponibilitePlanningDemain = $this->getDisponibilitePlanning($chauffeur, $demain);

            // Vérifier ensuite les réservations existantes
            $reservationsAujourdhui = Reservation::where('cardriver_id', $chauffeur->id)
                ->where('date', $date->format('Y-m-d'))
                ->where('status', '!=', 'Annulée')
                ->get();

            $reservationsDemain = Reservation::where('cardriver_id', $chauffeur->id)
                ->where('date', $demain->format('Y-m-d'))
                ->where('status', '!=', 'Annulée')
                ->get();

            // Combiner planning et réservations
            if ($disponibilitePlanning === 'En repos') {
                $disponibilite['aujourdhui'] = 'En repos';
            } else {
                $disponibilite['aujourdhui'] = $reservationsAujourdhui->isEmpty() ? 'Disponible' : 'Occupé';
            }

            if ($disponibilitePlanningDemain === 'En repos') {
                $disponibilite['demain'] = 'En repos';
            } else {
                $disponibilite['demain'] = $reservationsDemain->isEmpty() ? 'Disponible' : 'Occupé';
            }

            return $disponibilite;

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la vérification de disponibilité: ' . $e->getMessage());
            return ['aujourdhui' => 'Erreur', 'demain' => 'Erreur'];
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
            if ($chauffeur->day_off) {
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
            $chauffeur->en_repos = $this->estChauffeurEnReposPourDate($chauffeur, $date);
            return $chauffeur;
        });

        return $chauffeurs;
    }

    /**
     * Vérifier si un chauffeur est en repos pour une date spécifique
     */
    private function estChauffeurEnReposPourDate(User $chauffeur, $date = null)
    {
        try {
            // Si aucune date n'est fournie, utiliser la date actuelle
            if (!$date) {
                $date = Carbon::today();
            } else {
                $date = Carbon::parse($date);
            }

            // Vérifier d'abord le planning des groupes de chauffeurs
            $disponibilitePlanning = $this->getDisponibilitePlanning($chauffeur, $date);
            
            if ($disponibilitePlanning === 'En repos') {
                return true;
            }

            // Vérifier s'il a un jour de repos assigné manuellement
            if ($chauffeur->day_off) {
                $jourSemaine = $date->format('l'); // Jour de la semaine en anglais
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
                    
                    if ($jourSemaine === $jour) {
                        return true; // Chauffeur en repos ce jour-là
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
     * API pour récupérer les chauffeurs disponibles selon une date
     */
    public function getChauffeursDisponibles(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $chauffeurs = $this->getChauffeursDisponiblesPourDate($request->date);

        return response()->json($chauffeurs);
    }

    // ... autres méthodes existantes ...
}
