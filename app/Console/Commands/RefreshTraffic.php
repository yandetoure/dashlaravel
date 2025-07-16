<?php declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\TrafficIncident;

class RefreshTraffic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'traffic:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rafraîchir les données de trafic depuis l\'API TomTom';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Récupération des incidents de trafic...');

        try {
            // Tester avec un point à Paris (fonctionne avec TomTom)
            $point = '48.8566,2.3522'; // Paris
            $url = "https://api.tomtom.com/traffic/services/4/flowSegmentData/absolute/10/json";
            $this->info('URL utilisée : ' . $url);

            $response = \Illuminate\Support\Facades\Http::get($url, [
                'key' => env('TOMTOM_API_KEY'),
                'point' => $point,
                'unit' => 'KMPH'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $incidents = $data['flowSegmentData'] ?? [];

                $this->processIncidents($incidents);

                $this->info('✅ ' . count($incidents) . ' incidents récupérés avec succès');

                // Afficher un résumé
                $this->displaySummary();

            } else {
                $this->error('❌ Erreur lors de la récupération des données');
                $this->error('Code: ' . $response->status());
                $this->error('Réponse TomTom: ' . $response->body());
            }

        } catch (\Exception $e) {
            $this->error('❌ Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Traiter et sauvegarder les incidents
     */
    private function processIncidents($incidents)
    {
        $newIncidents = 0;
        $updatedIncidents = 0;

        foreach ($incidents as $incident) {
            // Pour Traffic Flow, on crée des incidents basés sur le niveau de congestion
            $congestionLevel = $incident['currentFlow'] ?? 0;
            $freeFlow = $incident['freeFlow'] ?? 1;

            // Calculer la gravité basée sur le ratio de congestion
            $ratio = $congestionLevel > 0 ? $freeFlow / $congestionLevel : 1;
            $severity = $ratio < 0.5 ? 'critical' : ($ratio < 0.8 ? 'major' : 'minor');

            // Générer des descriptions plus pertinentes pour les chauffeurs
            $description = $this->generateDriverDescription($ratio, $incident);

            $incidentData = [
                'incident_id' => 'flow_' . ($incident['coordinates']['coordinate'][0] ?? uniqid()),
                'type' => $this->determineIncidentType($ratio),
                'severity' => $severity,
                'description' => $description,
                'latitude' => $incident['coordinates']['coordinate'][1] ?? 0,
                'longitude' => $incident['coordinates']['coordinate'][0] ?? 0,
                'road_name' => $this->getRoadName($incident),
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
     * Générer une description pertinente pour les chauffeurs
     */
    private function generateDriverDescription($ratio, $incident)
    {
        $congestionLevel = $incident['currentFlow'] ?? 0;
        $freeFlow = $incident['freeFlow'] ?? 1;

        if ($ratio < 0.5) {
            // Trafic très dense
            $delay = round(($freeFlow - $congestionLevel) / $freeFlow * 100);
            return "🚗 Embouteillage majeur - Délai estimé: +{$delay}% - Évitez cette zone";
        } elseif ($ratio < 0.8) {
            // Trafic dense
            $delay = round(($freeFlow - $congestionLevel) / $freeFlow * 100);
            return "🚦 Ralentissement - Délai: +{$delay}% - Privilégiez les voies de gauche";
        } else {
            // Trafic fluide avec légers ralentissements
            return "⚡ Trafic fluide - Légers ralentissements - Circulation normale";
        }
    }

    /**
     * Déterminer le type d'incident basé sur le niveau de congestion
     */
    private function determineIncidentType($ratio)
    {
        if ($ratio < 0.5) {
            return 'congestion';
        } elseif ($ratio < 0.8) {
            return 'slow_traffic';
        } else {
            return 'normal';
        }
    }

    /**
     * Obtenir un nom de route plus descriptif
     */
    private function getRoadName($incident)
    {
        $frc = $incident['frc'] ?? '';

        // Mapper les codes FRC vers des noms de routes
        $roadNames = [
            'FRC0' => 'Autoroute principale',
            'FRC1' => 'Route nationale',
            'FRC2' => 'Route départementale',
            'FRC3' => 'Route locale',
            'FRC4' => 'Rue urbaine',
            'FRC5' => 'Chemin local'
        ];

        return $roadNames[$frc] ?? 'Route principale';
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
