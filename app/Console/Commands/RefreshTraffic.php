<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\TrafficIncident;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RefreshTraffic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'traffic:refresh {--demo : Créer des incidents de démonstration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rafraîchir les données de trafic depuis l\'API Google Maps';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Récupération des incidents de trafic depuis Google Maps...');

        // Si l'option demo est activée, créer des incidents de démonstration
        if ($this->option('demo')) {
            $this->createDemoIncidents();
            return;
        }

        $apiKey = env('GOOGLE_MAPS_API_KEY');

        if (!$apiKey) {
            $this->error('❌ Clé API Google Maps manquante dans le fichier .env');
            $this->error('Ajoutez GOOGLE_MAPS_API_KEY=votre_cle_api dans votre fichier .env');
            $this->error('Obtenez votre clé sur: https://console.cloud.google.com/');
            return;
        }

        $this->info('🔑 Clé API Google Maps configurée');

        // Zones de trafic importantes au Sénégal
        $senegalZones = [
            // Dakar Centre
            'Dakar Centre' => [
                'bounds' => '14.7000,-17.5000|14.7500,-17.4000',
                'center' => '14.7167,-17.4677'
            ],
            'Dakar Plateau' => [
                'bounds' => '14.7400,-17.4600|14.7600,-17.4400',
                'center' => '14.7500,-17.4500'
            ],
            'Dakar Almadies' => [
                'bounds' => '14.7100,-17.4700|14.7300,-17.4500',
                'center' => '14.7200,-17.4600'
            ],
            'Dakar Ouakam' => [
                'bounds' => '14.7300,-17.4800|14.7500,-17.4600',
                'center' => '14.7400,-17.4700'
            ],
            'Dakar Yoff' => [
                'bounds' => '14.7400,-17.4900|14.7600,-17.4700',
                'center' => '14.7500,-17.4800'
            ],
            'Dakar Mermoz' => [
                'bounds' => '14.7200,-17.4600|14.7400,-17.4400',
                'center' => '14.7300,-17.4500'
            ],
            'Dakar Fann' => [
                'bounds' => '14.7100,-17.4500|14.7300,-17.4300',
                'center' => '14.7200,-17.4400'
            ],
            'Dakar Gueule Tapée' => [
                'bounds' => '14.7000,-17.4700|14.7200,-17.4500',
                'center' => '14.7100,-17.4600'
            ],
            'Dakar Médina' => [
                'bounds' => '14.6900,-17.4600|14.7100,-17.4400',
                'center' => '14.7000,-17.4500'
            ],
            'Dakar Grand Dakar' => [
                'bounds' => '14.7200,-17.4800|14.7400,-17.4600',
                'center' => '14.7300,-17.4700'
            ],

            // Aéroport et zones périphériques
            'Aéroport AIBD' => [
                'bounds' => '14.7300,-17.5000|14.7500,-17.4800',
                'center' => '14.7400,-17.4900'
            ],
            'Diamniadio' => [
                'bounds' => '14.6800,-17.4200|14.7000,-17.4000',
                'center' => '14.6900,-17.4100'
            ],
            'Thiès' => [
                'bounds' => '14.7800,-17.0000|14.8000,-16.9000',
                'center' => '14.7833,-16.9333'
            ],
            'Rufisque' => [
                'bounds' => '14.7100,-17.2800|14.7300,-17.2600',
                'center' => '14.7167,-17.2667'
            ],
            'Bargny' => [
                'bounds' => '14.7000,-17.3200|14.7200,-17.3000',
                'center' => '14.7100,-17.3100'
            ],
            'Sangalkam' => [
                'bounds' => '14.7800,-17.2200|14.8000,-17.2000',
                'center' => '14.7900,-17.2100'
            ],
            'Pikine' => [
                'bounds' => '14.7500,-17.4000|14.7700,-17.3800',
                'center' => '14.7600,-17.3900'
            ],
            'Guédiawaye' => [
                'bounds' => '14.7800,-17.4200|14.8000,-17.4000',
                'center' => '14.7900,-17.4100'
            ],
            'Saint-Louis' => [
                'bounds' => '16.0300,-16.5100|16.0500,-16.4900',
                'center' => '16.0333,-16.5000'
            ],
            'Kaolack' => [
                'bounds' => '14.1400,-16.0900|14.1600,-16.0700',
                'center' => '14.1500,-16.0833'
            ],
            'Ziguinchor' => [
                'bounds' => '12.5800,-16.2900|12.6000,-16.2700',
                'center' => '12.5833,-16.2833'
            ],

            // Routes principales
            'Route AIBD-Dakar' => [
                'bounds' => '14.7200,-17.4900|14.7500,-17.4500',
                'center' => '14.7350,-17.4700'
            ],
            'Route Dakar-Thiès' => [
                'bounds' => '14.7500,-17.4500|14.8000,-16.9000',
                'center' => '14.7750,-17.1750'
            ],
            'Route Dakar-Rufisque' => [
                'bounds' => '14.7100,-17.4700|14.7200,-17.2700',
                'center' => '14.7150,-17.3700'
            ],
            'Route Dakar-Diamniadio' => [
                'bounds' => '14.6900,-17.4700|14.7000,-17.4100',
                'center' => '14.6950,-17.4400'
            ],
            'Autoroute Dakar-AIBD' => [
                'bounds' => '14.7200,-17.4900|14.7500,-17.4500',
                'center' => '14.7350,-17.4700'
            ],
            'Route de la Corniche' => [
                'bounds' => '14.7400,-17.4900|14.7600,-17.4500',
                'center' => '14.7500,-17.4700'
            ],
            'Route de l\'Aéroport' => [
                'bounds' => '14.7200,-17.4900|14.7500,-17.4700',
                'center' => '14.7350,-17.4800'
            ],
            'Route de Ouakam' => [
                'bounds' => '14.7300,-17.4800|14.7500,-17.4600',
                'center' => '14.7400,-17.4700'
            ],
            'Route de Yoff' => [
                'bounds' => '14.7400,-17.4900|14.7600,-17.4700',
                'center' => '14.7500,-17.4800'
            ],
            'Route de Mermoz' => [
                'bounds' => '14.7200,-17.4600|14.7400,-17.4400',
                'center' => '14.7300,-17.4500'
            ]
        ];

        $totalIncidents = 0;
        $successfulZones = 0;

        foreach ($senegalZones as $zoneName => $zoneData) {
            $this->line("📍 Analyse de {$zoneName}...");

            try {
                // Utiliser Google Maps Directions API pour obtenir des données de trafic
                $url = "https://maps.googleapis.com/maps/api/directions/json";

                $response = Http::timeout(30)->get($url, [
                    'origin' => $zoneData['center'],
                    'destination' => $this->getDestinationForZone($zoneName),
                    'key' => $apiKey,
                    'departure_time' => 'now',
                    'traffic_model' => 'best_guess',
                    'alternatives' => 'true'
                ]);

                $this->line("📡 Réponse pour {$zoneName}: " . $response->status());

                if ($response->successful()) {
                    $data = $response->json();

                    if ($data['status'] === 'OK' && !empty($data['routes'])) {
                        $incidents = $this->extractTrafficDataFromGoogleResponse($data, $zoneName);

                        if (!empty($incidents)) {
                            $this->processIncidents($incidents, $zoneName);
                            $totalIncidents += count($incidents);
                            $successfulZones++;
                            $this->line("✅ {$zoneName}: " . count($incidents) . " incidents de trafic détectés");
                        } else {
                            // Créer un incident de "trafic fluide" pour informer l'utilisateur
                            $this->createFluidTrafficIncident($data, $zoneName);
                            $this->line("✅ {$zoneName}: Circulation fluide - Aucun problème détecté");
                        }
                    } else {
                        $this->warn("⚠️ {$zoneName}: " . ($data['status'] ?? 'Erreur inconnue'));
                        Log::warning("Google Maps API Error for {$zoneName}", [
                            'status' => $data['status'] ?? 'unknown',
                            'error_message' => $data['error_message'] ?? 'none'
                        ]);
                    }
                } else {
                    $this->warn("⚠️ {$zoneName}: Erreur HTTP " . $response->status());
                    Log::warning("Google Maps HTTP Error for {$zoneName}", [
                        'status' => $response->status(),
                        'response' => $response->body()
                    ]);
                }

            } catch (\Exception $e) {
                $this->error("❌ {$zoneName}: " . $e->getMessage());
                Log::error("Google Maps API Exception for {$zoneName}", [
                    'error' => $e->getMessage()
                ]);
            }

            // Pause entre les requêtes pour éviter le rate limiting
            sleep(2);
        }

        if ($totalIncidents > 0) {
            $this->info("✅ Récupération terminée: {$totalIncidents} incidents depuis {$successfulZones} zones");
            $this->displaySummary();
        } else {
            $this->warn("⚠️ Aucun incident récupéré depuis Google Maps");
            $this->line("Causes possibles:");
            $this->line("  - Trafic fluide dans les zones analysées");
            $this->line("  - Problème de clé API Google Maps");
            $this->line("  - Problème de connectivité réseau");

            if ($this->confirm('Voulez-vous créer des incidents de démonstration en attendant ?')) {
                $this->createDemoIncidents();
            }
        }
    }

    /**
     * Obtenir une destination appropriée pour chaque zone
     */
    private function getDestinationForZone($zoneName)
    {
        $destinations = [
            // Dakar Centre et quartiers
            'Dakar Centre' => '14.7500,-17.4500', // Plateau
            'Dakar Plateau' => '14.7200,-17.4600', // Almadies
            'Dakar Almadies' => '14.7167,-17.4677', // Centre
            'Dakar Ouakam' => '14.7167,-17.4677', // Centre
            'Dakar Yoff' => '14.7167,-17.4677', // Centre
            'Dakar Mermoz' => '14.7167,-17.4677', // Centre
            'Dakar Fann' => '14.7167,-17.4677', // Centre
            'Dakar Gueule Tapée' => '14.7167,-17.4677', // Centre
            'Dakar Médina' => '14.7167,-17.4677', // Centre
            'Dakar Grand Dakar' => '14.7167,-17.4677', // Centre

            // Aéroport et zones périphériques
            'Aéroport AIBD' => '14.7167,-17.4677', // Dakar Centre
            'Diamniadio' => '14.7167,-17.4677', // Dakar Centre
            'Thiès' => '14.7167,-17.4677', // Dakar
            'Rufisque' => '14.7167,-17.4677', // Dakar
            'Bargny' => '14.7167,-17.4677', // Dakar
            'Sangalkam' => '14.7167,-17.4677', // Dakar
            'Pikine' => '14.7167,-17.4677', // Dakar
            'Guédiawaye' => '14.7167,-17.4677', // Dakar
            'Saint-Louis' => '14.7167,-17.4677', // Dakar
            'Kaolack' => '14.7167,-17.4677', // Dakar
            'Ziguinchor' => '14.7167,-17.4677', // Dakar

            // Routes principales
            'Route AIBD-Dakar' => '14.7167,-17.4677', // Dakar Centre
            'Route Dakar-Thiès' => '14.7167,-17.4677', // Dakar
            'Route Dakar-Rufisque' => '14.7167,-17.4677', // Dakar
            'Route Dakar-Diamniadio' => '14.7167,-17.4677', // Dakar
            'Autoroute Dakar-AIBD' => '14.7167,-17.4677', // Dakar Centre
            'Route de la Corniche' => '14.7167,-17.4677', // Dakar Centre
            'Route de l\'Aéroport' => '14.7167,-17.4677', // Dakar Centre
            'Route de Ouakam' => '14.7167,-17.4677', // Dakar Centre
            'Route de Yoff' => '14.7167,-17.4677', // Dakar Centre
            'Route de Mermoz' => '14.7167,-17.4677', // Dakar Centre
        ];

        return $destinations[$zoneName] ?? '14.7167,-17.4677'; // Dakar Centre par défaut
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
                                'road_name' => $step['html_instructions'] ?? 'Route principale',
                                'congestion_ratio' => $stepCongestionRatio,
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
    private function processIncidents($incidents, $location)
    {
        $newIncidents = 0;
        $updatedIncidents = 0;

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
                $updatedIncidents++;
            } else {
                TrafficIncident::create($incidentData);
                $newIncidents++;
            }
        }

        // Marquer les anciens incidents comme inactifs
        $deactivatedCount = TrafficIncident::where('updated_at', '<', now()->subHours(2))
            ->where('is_active', true)
            ->update(['is_active' => false]);

        // Vider le cache
        Cache::forget('traffic_incidents');

        $this->line("📊 Nouveaux incidents: {$newIncidents}");
        $this->line("🔄 Incidents mis à jour: {$updatedIncidents}");
        $this->line("❌ Incidents désactivés: {$deactivatedCount}");
    }

    /**
     * Créer des incidents de démonstration
     */
    private function createDemoIncidents()
    {
        $this->info('🎭 Création d\'incidents de démonstration...');

        // Supprimer les anciens incidents de démo
        TrafficIncident::where('incident_id', 'like', 'demo-%')->delete();

        // Créer des incidents de démonstration
        $demoIncidents = [
            [
                'incident_id' => 'demo-1',
                'type' => 'congestion',
                'severity' => 'critical',
                'description' => '🚗 Embouteillage majeur - Délai estimé: +75% - Évitez cette zone',
                'latitude' => 14.7167,
                'longitude' => -17.4677,
                'road_name' => 'Autoroute Dakar-Thiès',
                'is_active' => true
            ],
            [
                'incident_id' => 'demo-2',
                'type' => 'construction',
                'severity' => 'major',
                'description' => '🚧 Travaux en cours - Voie réduite - Privilégiez l\'itinéraire alternatif',
                'latitude' => 14.7500,
                'longitude' => -17.4500,
                'road_name' => 'Route de la Corniche',
                'is_active' => true
            ],
            [
                'incident_id' => 'demo-3',
                'type' => 'slow_traffic',
                'severity' => 'minor',
                'description' => '🐌 Ralentissement - Délai: +25% - Privilégiez les voies de gauche',
                'latitude' => 14.6900,
                'longitude' => -17.4440,
                'road_name' => 'Centre-ville Dakar',
                'is_active' => true
            ],
            [
                'incident_id' => 'demo-4',
                'type' => 'accident',
                'severity' => 'critical',
                'description' => '🚨 Accident signalé - Route bloquée - Délai: +90% - Itinéraire alternatif conseillé',
                'latitude' => 14.7200,
                'longitude' => -17.4600,
                'road_name' => 'Route de l\'Aéroport',
                'is_active' => true
            ],
            [
                'incident_id' => 'demo-5',
                'type' => 'weather',
                'severity' => 'major',
                'description' => '🌧️ Pluie intense - Visibilité réduite - Ralentissez et allumez vos phares',
                'latitude' => 14.6800,
                'longitude' => -17.4300,
                'road_name' => 'Route de Rufisque',
                'is_active' => true
            ]
        ];

        foreach ($demoIncidents as $incident) {
            TrafficIncident::create($incident);
        }

        // Vider le cache
        Cache::forget('traffic_incidents');

        $this->info('✅ ' . count($demoIncidents) . ' incidents de démonstration créés');
        $this->displaySummary();
    }

    /**
     * Créer un incident de trafic fluide pour informer l'utilisateur
     */
    private function createFluidTrafficIncident($data, $zoneName)
    {
        // Extraire les informations de trafic de la réponse Google Maps
        $route = $data['routes'][0] ?? null;
        if (!$route) return;

        $leg = $route['legs'][0] ?? null;
        if (!$leg) return;

        $duration = $leg['duration_in_traffic']['value'] ?? $leg['duration']['value'] ?? 0;
        $durationNormal = $leg['duration']['value'] ?? $duration;
        $congestionRatio = $durationNormal > 0 ? $duration / $durationNormal : 1;

        // Calculer le pourcentage de retard
        $delayPercent = round(($congestionRatio - 1) * 100);

        // Déterminer l'état du trafic
        if ($congestionRatio <= 1.05) {
            $status = "fluide";
            $emoji = "🟢";
            $description = "Circulation excellente - Trafic fluide";
        } elseif ($congestionRatio <= 1.1) {
            $status = "normal";
            $emoji = "🟡";
            $description = "Circulation normale - Légers ralentissements";
        } else {
            $status = "lent";
            $emoji = "🟠";
            $description = "Circulation lente - Ralentissements modérés";
        }

        // Créer l'incident de trafic fluide
        $incidentData = [
            'incident_id' => "fluid_{$zoneName}_" . time(),
            'type' => 'normal',
            'severity' => 'info',
            'description' => "{$emoji} {$description} - {$zoneName} - Délai: +{$delayPercent}%",
            'latitude' => $leg['start_location']['lat'] ?? 0,
            'longitude' => $leg['start_location']['lng'] ?? 0,
            'road_name' => $zoneName,
            'direction' => null,
            'start_time' => null,
            'end_time' => null,
            'is_active' => true
        ];

        // Vérifier si un incident fluide existe déjà pour cette zone
        $existingIncident = TrafficIncident::where('incident_id', 'like', "fluid_{$zoneName}_%")->first();

        if ($existingIncident) {
            $existingIncident->update($incidentData);
        } else {
            TrafficIncident::create($incidentData);
        }

        // Vider le cache
        Cache::forget('traffic_incidents');
    }

    /**
     * Afficher un résumé des incidents
     */
    private function displaySummary()
    {
        $activeIncidents = TrafficIncident::active()->count();
        $criticalIncidents = TrafficIncident::active()->bySeverity('critical')->count();
        $majorIncidents = TrafficIncident::active()->bySeverity('major')->count();
        $minorIncidents = TrafficIncident::active()->bySeverity('minor')->count();

        $this->newLine();
        $this->info('📋 Résumé des incidents actifs:');
        $this->line("🔴 Critiques: {$criticalIncidents}");
        $this->line("🟠 Majeurs: {$majorIncidents}");
        $this->line("🟡 Mineurs: {$minorIncidents}");
        $this->line("📊 Total: {$activeIncidents}");
    }
}
