<?php declare(strict_types=1);

require_once 'vendor/autoload.php';

use App\Models\User;
use Illuminate\Support\Facades\DB;

// Charger Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Simulateur de Mouvement des Chauffeurs ===\n\n";

// Récupérer tous les chauffeurs
$chauffeurs = User::role('chauffeur')->get();

if ($chauffeurs->isEmpty()) {
    echo "❌ Aucun chauffeur trouvé. Créez d'abord des chauffeurs.\n";
    exit(1);
}

echo "🚗 Simulation du mouvement de " . $chauffeurs->count() . " chauffeurs\n";
echo "📍 Zone de simulation : Dakar et environs\n";
echo "⏱️  Fréquence : Toutes les 2 secondes\n";
echo "🔄 Appuyez sur Ctrl+C pour arrêter\n\n";

// Coordonnées de base à Dakar
$baseLat = 14.6928;
$baseLng = -17.4467;
$radius = 0.02; // Rayon de 2km environ

$iteration = 0;

while (true) {
    $iteration++;
    echo "=== Itération $iteration - " . date('H:i:s') . " ===\n";
    
    foreach ($chauffeurs as $index => $chauffeur) {
        // Générer une position aléatoire dans un cercle autour de Dakar
        $angle = (2 * M_PI * $index / $chauffeurs->count()) + ($iteration * 0.1);
        $distance = $radius * (0.5 + (rand(0, 100) / 200)); // Distance variable
        
        $lat = $baseLat + ($distance * cos($angle));
        $lng = $baseLng + ($distance * sin($angle));
        
        // Mettre à jour la position
        $chauffeur->update([
            'current_lat' => $lat,
            'current_lng' => $lng,
            'location_updated_at' => now(),
        ]);
        
        echo "🚗 {$chauffeur->first_name}: Lat: " . number_format($lat, 6) . ", Lng: " . number_format($lng, 6) . "\n";
    }
    
    echo "✅ Positions mises à jour\n";
    echo "🌐 Testez sur : http://127.0.0.1:8001/admin/driver-location\n\n";
    
    // Attendre 2 secondes
    sleep(2);
}

echo "\n=== Simulation arrêtée ===\n";


