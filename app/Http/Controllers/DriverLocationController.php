<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverLocationController extends Controller
{
    /**
     * Vérifier les autorisations
     */
    private function checkPermissions()
    {
        if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'super-admin'])) {
            abort(403, 'Accès non autorisé.');
        }
    }

    /**
     * Afficher la page de localisation des chauffeurs
     */
    public function index()
    {
        $this->checkPermissions();
        // Récupérer tous les chauffeurs avec leurs informations
        $chauffeurs = User::role('chauffeur')
            ->with(['car_drivers.car'])
            ->get()
            ->map(function ($chauffeur) {
                // Récupérer la dernière course active
                $lastCourse = Course::whereHas('reservation.carDriver', function ($query) use ($chauffeur) {
                    $query->where('chauffeur_id', $chauffeur->id);
                })
                ->whereIn('statut', ['en_attente', 'en_cours'])
                ->with(['reservation.client'])
                ->latest()
                ->first();

                // Récupérer les statistiques du chauffeur
                $stats = [
                    'courses_aujourd_hui' => Course::whereHas('reservation.carDriver', function ($query) use ($chauffeur) {
                        $query->where('chauffeur_id', $chauffeur->id);
                    })
                    ->whereDate('created_at', today())
                    ->count(),
                    
                    'courses_total' => Course::whereHas('reservation.carDriver', function ($query) use ($chauffeur) {
                        $query->where('chauffeur_id', $chauffeur->id);
                    })->count(),
                    
                    'courses_en_cours' => Course::whereHas('reservation.carDriver', function ($query) use ($chauffeur) {
                        $query->where('chauffeur_id', $chauffeur->id);
                    })
                    ->where('statut', 'en_cours')
                    ->count(),
                ];

                return [
                    'id' => $chauffeur->id,
                    'nom' => $chauffeur->first_name . ' ' . $chauffeur->last_name,
                    'email' => $chauffeur->email,
                    'telephone' => $chauffeur->phone_number,
                    'voiture' => $chauffeur->car_drivers->first()?->car?->marque . ' ' . $chauffeur->car_drivers->first()?->car?->modele,
                    'immatriculation' => $chauffeur->car_drivers->first()?->car?->immatriculation,
                    'statut' => $this->getDriverStatus($chauffeur, $lastCourse),
                    'derniere_course' => $lastCourse,
                    'stats' => $stats,
                    'localisation' => [
                        'lat' => $chauffeur->current_lat ?? 14.6928, // Position par défaut à Dakar
                        'lng' => $chauffeur->current_lng ?? -17.4467,
                        'derniere_maj' => $chauffeur->location_updated_at ?? null,
                    ]
                ];
            });

        return view('admin.driver-location', compact('chauffeurs'));
    }

    /**
     * API pour récupérer la position actuelle d'un chauffeur
     */
    public function getDriverLocation($driverId)
    {
        $this->checkPermissions();
        $chauffeur = User::role('chauffeur')->find($driverId);
        
        if (!$chauffeur) {
            return response()->json(['error' => 'Chauffeur non trouvé'], 404);
        }

        return response()->json([
            'id' => $chauffeur->id,
            'nom' => $chauffeur->first_name . ' ' . $chauffeur->last_name,
            'position' => [
                'lat' => $chauffeur->current_lat ?? 14.6928,
                'lng' => $chauffeur->current_lng ?? -17.4467,
            ],
            'derniere_maj' => $chauffeur->location_updated_at,
            'statut' => $this->getDriverStatus($chauffeur)
        ]);
    }

    /**
     * API pour récupérer toutes les positions des chauffeurs
     */
    public function getAllDriversLocations()
    {
        $this->checkPermissions();
        $chauffeurs = User::role('chauffeur')
            ->select(['id', 'first_name', 'last_name', 'current_lat', 'current_lng', 'location_updated_at'])
            ->get()
            ->map(function ($chauffeur) {
                return [
                    'id' => $chauffeur->id,
                    'nom' => $chauffeur->first_name . ' ' . $chauffeur->last_name,
                    'position' => [
                        'lat' => $chauffeur->current_lat ?? 14.6928,
                        'lng' => $chauffeur->current_lng ?? -17.4467,
                    ],
                    'derniere_maj' => $chauffeur->location_updated_at,
                ];
            });

        return response()->json($chauffeurs);
    }

    /**
     * Mettre à jour la position d'un chauffeur (appelé par le chauffeur)
     */
    public function updateDriverLocation(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $chauffeur = Auth::user();
        
        if (!$chauffeur->hasRole('chauffeur')) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        // Vérifier si la position a vraiment changé
        $hasChanged = false;
        if (!$chauffeur->current_lat || !$chauffeur->current_lng) {
            $hasChanged = true;
        } else {
            $distance = $this->calculateDistance(
                $chauffeur->current_lat, 
                $chauffeur->current_lng, 
                $request->lat, 
                $request->lng
            );
            
            // Mettre à jour seulement si le chauffeur a bougé de plus de 10 mètres
            if ($distance > 10) {
                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            $chauffeur->update([
                'current_lat' => $request->lat,
                'current_lng' => $request->lng,
                'location_updated_at' => now(),
            ]);

            \Log::info("Position chauffeur {$chauffeur->id} mise à jour", [
                'lat' => $request->lat,
                'lng' => $request->lng,
                'timestamp' => now()
            ]);
        }

        return response()->json([
            'success' => true, 
            'message' => 'Position mise à jour',
            'updated' => $hasChanged,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Calculer la distance entre deux points GPS en mètres
     */
    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371000; // Rayon de la Terre en mètres
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng/2) * sin($dLng/2);
             
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }

    /**
     * Déterminer le statut d'un chauffeur
     */
    private function getDriverStatus($chauffeur, $lastCourse = null)
    {
        if (!$lastCourse) {
            $lastCourse = Course::whereHas('reservation.carDriver', function ($query) use ($chauffeur) {
                $query->where('chauffeur_id', $chauffeur->id);
            })
            ->latest()
            ->first();
        }

        if ($lastCourse) {
            switch ($lastCourse->statut) {
                case 'en_cours':
                    return 'en_course';
                case 'en_attente':
                    return 'en_attente';
                default:
                    return 'disponible';
            }
        }

        return 'disponible';
    }
}

