<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\TrafficIncident;

class TrafficController extends Controller
{
    /**
     * Afficher la carte des incidents de trafic
     */
    public function index()
    {
        // Log pour déboguer
        Log::info('TrafficController::index - Début de la méthode');

        // Récupérer les incidents depuis le cache ou la base de données
        $incidents = Cache::remember('traffic_incidents', 300, function () {
            Log::info('TrafficController::index - Récupération depuis la base de données');
            $incidents = TrafficIncident::active()->latest()->get();
            Log::info('TrafficController::index - Nombre d\'incidents trouvés: ' . $incidents->count());
            return $incidents;
        });

        Log::info('TrafficController::index - Incidents à afficher: ' . $incidents->count());

        return view('traffic.index', compact('incidents'));
    }

    /**
     * Récupérer les incidents depuis l'API Google Maps
     */
    public function fetchIncidents()
    {
        try {
            $apiKey = env('GOOGLE_MAPS_API_KEY');

            if (!$apiKey) {
                Log::error('Clé API Google Maps manquante');
                return response()->json([
                    'success' => false,
                    'message' => 'Configuration API manquante'
                ], 500);
            }

            // Zones importantes au Sénégal
            $zones = [
                // Dakar Centre et quartiers
                'Dakar Centre' => ['origin' => '14.7167,-17.4677', 'destination' => '14.7500,-17.4500'],
                'Dakar Plateau' => ['origin' => '14.7500,-17.4500', 'destination' => '14.7200,-17.4600'],
                'Dakar Almadies' => ['origin' => '14.7200,-17.4600', 'destination' => '14.7167,-17.4677'],
                'Dakar Ouakam' => ['origin' => '14.7400,-17.4700', 'destination' => '14.7167,-17.4677'],
                'Dakar Yoff' => ['origin' => '14.7500,-17.4800', 'destination' => '14.7167,-17.4677'],
                'Dakar Mermoz' => ['origin' => '14.7300,-17.4500', 'destination' => '14.7167,-17.4677'],
                'Dakar Fann' => ['origin' => '14.7200,-17.4400', 'destination' => '14.7167,-17.4677'],
                'Dakar Gueule Tapée' => ['origin' => '14.7100,-17.4600', 'destination' => '14.7167,-17.4677'],
                'Dakar Médina' => ['origin' => '14.7000,-17.4500', 'destination' => '14.7167,-17.4677'],
                'Dakar Grand Dakar' => ['origin' => '14.7300,-17.4700', 'destination' => '14.7167,-17.4677'],

                // Aéroport et zones périphériques
                'Aéroport AIBD' => ['origin' => '14.7400,-17.4900', 'destination' => '14.7167,-17.4677'],
                'Diamniadio' => ['origin' => '14.6900,-17.4100', 'destination' => '14.7167,-17.4677'],
                'Thiès' => ['origin' => '14.7833,-16.9333', 'destination' => '14.7167,-17.4677'],
                'Rufisque' => ['origin' => '14.7167,-17.2667', 'destination' => '14.7167,-17.4677'],
                'Bargny' => ['origin' => '14.7100,-17.3100', 'destination' => '14.7167,-17.4677'],
                'Sangalkam' => ['origin' => '14.7900,-17.2100', 'destination' => '14.7167,-17.4677'],
                'Pikine' => ['origin' => '14.7600,-17.3900', 'destination' => '14.7167,-17.4677'],
                'Guédiawaye' => ['origin' => '14.7900,-17.4100', 'destination' => '14.7167,-17.4677'],

                // Routes principales
                'Route AIBD-Dakar' => ['origin' => '14.7350,-17.4700', 'destination' => '14.7167,-17.4677'],
                'Route Dakar-Thiès' => ['origin' => '14.7750,-17.1750', 'destination' => '14.7167,-17.4677'],
                'Route Dakar-Rufisque' => ['origin' => '14.7150,-17.3700', 'destination' => '14.7167,-17.4677'],
                'Route Dakar-Diamniadio' => ['origin' => '14.6950,-17.4400', 'destination' => '14.7167,-17.4677'],
                'Autoroute Dakar-AIBD' => ['origin' => '14.7350,-17.4700', 'destination' => '14.7167,-17.4677'],
                'Route de la Corniche' => ['origin' => '14.7500,-17.4700', 'destination' => '14.7167,-17.4677'],
                'Route de l\'Aéroport' => ['origin' => '14.7350,-17.4800', 'destination' => '14.7167,-17.4677'],
                'Route de Ouakam' => ['origin' => '14.7400,-17.4700', 'destination' => '14.7167,-17.4677'],
                'Route de Yoff' => ['origin' => '14.7500,-17.4800', 'destination' => '14.7167,-17.4677'],
                'Route de Mermoz' => ['origin' => '14.7300,-17.4500', 'destination' => '14.7167,-17.4677']
            ];

            $totalIncidents = 0;

            foreach ($zones as $zoneName => $coordinates) {
                $response = Http::timeout(30)->get('https://maps.googleapis.com/maps/api/directions/json', [
                    'origin' => $coordinates['origin'],
                    'destination' => $coordinates['destination'],
                    'key' => $apiKey,
                    'departure_time' => 'now',
                    'traffic_model' => 'best_guess'
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if ($data['status'] === 'OK' && !empty($data['routes'])) {
                        $incidents = $this->extractTrafficDataFromGoogleResponse($data, $zoneName);
                        $this->processIncidents($incidents);
                        $totalIncidents += count($incidents);

                        Log::info("Zone {$zoneName}: " . count($incidents) . " incidents détectés");
                    }
                }

                // Pause entre les requêtes
                sleep(1);
            }

            return response()->json([
                'success' => true,
                'message' => $totalIncidents . ' incidents récupérés depuis Google Maps',
                'incidents_count' => $totalIncidents
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des incidents: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Extraire les données de trafic de la réponse Google Maps
     */
    private function extractTrafficDataFromGoogleResponse($data, $zoneName)
    {
        $incidents = [];

        foreach ($data['routes'] as $routeIndex => $route) {
            $legs = $route['legs'] ?? [];

            foreach ($legs as $leg) {
                $duration = $leg['duration_in_traffic']['value'] ?? $leg['duration']['value'] ?? 0;
                $durationWithoutTraffic = $leg['duration']['value'] ?? $duration;

                // Calculer le niveau de congestion
                $congestionRatio = $durationWithoutTraffic > 0 ? $duration / $durationWithoutTraffic : 1;

                if ($congestionRatio > 1.1) { // Seuil de 10% de retard
                    $steps = $leg['steps'] ?? [];

                    foreach ($steps as $stepIndex => $step) {
                        $stepDuration = $step['duration_in_traffic']['value'] ?? $step['duration']['value'] ?? 0;
                        $stepDurationNormal = $step['duration']['value'] ?? $stepDuration;
                        $stepCongestionRatio = $stepDurationNormal > 0 ? $stepDuration / $stepDurationNormal : 1;

                        if ($stepCongestionRatio > 1.05) { // Seuil de 5% de retard par segment
                            $incidents[] = [
                                'incident_id' => "google_{$zoneName}_{$routeIndex}_{$stepIndex}",
                                'type' => $this->determineIncidentType($stepCongestionRatio),
                                'severity' => $this->determineSeverity($stepCongestionRatio),
                                'description' => $this->generateDescription($stepCongestionRatio, $step),
                                'latitude' => $step['start_location']['lat'] ?? 0,
                                'longitude' => $step['start_location']['lng'] ?? 0,
                                'road_name' => strip_tags($step['html_instructions'] ?? 'Route principale'),
                                'zone' => $zoneName
                            ];
                        }
                    }
                }
            }
        }

        return $incidents;
    }

    /**
     * Déterminer le type d'incident basé sur le ratio de congestion
     */
    private function determineIncidentType($ratio)
    {
        if ($ratio > 1.5) {
            return 'congestion';
        } elseif ($ratio > 1.2) {
            return 'slow_traffic';
        } else {
            return 'normal';
        }
    }

    /**
     * Déterminer la gravité basée sur le ratio de congestion
     */
    private function determineSeverity($ratio)
    {
        if ($ratio > 1.5) {
            return 'critical';
        } elseif ($ratio > 1.2) {
            return 'major';
        } else {
            return 'minor';
        }
    }

    /**
     * Générer une description pertinente
     */
    private function generateDescription($ratio, $step)
    {
        $delay = round(($ratio - 1) * 100);
        $instruction = strip_tags($step['html_instructions'] ?? '');

        if ($ratio > 1.5) {
            return "🚗 Embouteillage majeur - Délai: +{$delay}% - {$instruction}";
        } elseif ($ratio > 1.2) {
            return "🚦 Ralentissement - Délai: +{$delay}% - {$instruction}";
        } else {
            return "⚡ Trafic fluide - Légers ralentissements - {$instruction}";
        }
    }

    /**
     * Traiter et sauvegarder les incidents
     */
    private function processIncidents($incidents)
    {
        foreach ($incidents as $incident) {
            $incidentData = [
                'incident_id' => $incident['incident_id'],
                'type' => $incident['type'],
                'severity' => $incident['severity'],
                'description' => $incident['description'],
                'latitude' => $incident['latitude'],
                'longitude' => $incident['longitude'],
                'road_name' => $incident['road_name'],
                'direction' => null,
                'start_time' => null,
                'end_time' => null,
                'is_active' => true
            ];

            // Vérifier si l'incident existe déjà
            $existingIncident = TrafficIncident::where('incident_id', $incidentData['incident_id'])->first();

            if ($existingIncident) {
                $existingIncident->update($incidentData);
            } else {
                TrafficIncident::create($incidentData);
            }
        }

        // Marquer les anciens incidents comme inactifs
        TrafficIncident::where('updated_at', '<', now()->subHours(2))->update(['is_active' => false]);

        // Vider le cache
        Cache::forget('traffic_incidents');
    }

    /**
     * API endpoint pour récupérer les incidents en JSON
     */
    public function api()
    {
        $incidents = TrafficIncident::active()
            ->select(['id', 'type', 'severity', 'description', 'latitude', 'longitude', 'road_name', 'created_at'])
            ->latest()
            ->get();

        return response()->json($incidents);
    }
}
