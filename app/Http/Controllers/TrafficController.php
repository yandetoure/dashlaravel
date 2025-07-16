<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\TrafficIncident;

class TrafficController extends Controller
{
    /**
     * Afficher la carte des incidents de trafic
     */
    public function index()
    {
        // Récupérer les incidents depuis le cache ou la base de données
        $incidents = Cache::remember('traffic_incidents', 300, function () {
            return TrafficIncident::active()->latest()->get();
        });

        return view('traffic.index', compact('incidents'));
    }

    /**
     * Récupérer les incidents depuis l'API TomTom
     */
        public function fetchIncidents()
    {
        try {
            // Bbox large autour du Sénégal
            $bbox = '12.0,-18.0,16.0,-16.0'; // Presque tout le Sénégal

            $response = \Illuminate\Support\Facades\Http::get('https://api.tomtom.com/traffic/services/4/incidentDetails/s3/' . $bbox . '/10/json', [
                'key' => env('TOMTOM_API_KEY')
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $incidents = $data['incidents'] ?? [];

                // Traiter et sauvegarder les incidents
                $this->processIncidents($incidents);

                return response()->json([
                    'success' => true,
                    'message' => count($incidents) . ' incidents récupérés',
                    'incidents' => $incidents
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des données'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Traiter et sauvegarder les incidents
     */
    private function processIncidents($incidents)
    {
        foreach ($incidents as $incident) {
            $incidentData = [
                'incident_id' => $incident['id'] ?? uniqid(),
                'type' => $this->mapIncidentType($incident['type'] ?? 'unknown'),
                'severity' => $incident['properties']['magnitudeOfDelay'] ?? 'minor',
                'description' => $incident['properties']['description'] ?? 'Incident de trafic',
                'latitude' => $incident['geometry']['coordinates'][1] ?? 0,
                'longitude' => $incident['geometry']['coordinates'][0] ?? 0,
                'road_name' => $incident['properties']['roadName'] ?? null,
                'direction' => $incident['properties']['direction'] ?? null,
                'start_time' => isset($incident['properties']['startTime'])
                    ? \Carbon\Carbon::parse($incident['properties']['startTime'])
                    : null,
                'end_time' => isset($incident['properties']['endTime'])
                    ? \Carbon\Carbon::parse($incident['properties']['endTime'])
                    : null,
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
     * Mapper les types d'incidents TomTom vers nos types
     */
    private function mapIncidentType($tomtomType)
    {
        return match($tomtomType) {
            'ACCIDENT' => 'accident',
            'CONSTRUCTION' => 'construction',
            'CONGESTION' => 'congestion',
            'WEATHER' => 'weather',
            'ROAD_CLOSED' => 'road_closed',
            default => 'other'
        };
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
