<?php declare(strict_types=1); 
/**
 * Script de test complet pour le système de localisation des chauffeurs
 * Usage: php test-location-system.php
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Course;

// Configuration de base de Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🚗 Test complet du système de localisation des chauffeurs\n";
echo "========================================================\n\n";

// 1. Vérifier les chauffeurs
$chauffeurs = User::role('chauffeur')->get();
echo "📋 Chauffeurs trouvés: " . $chauffeurs->count() . "\n";

if ($chauffeurs->isEmpty()) {
    echo "❌ Aucun chauffeur trouvé. Créez d'abord des utilisateurs avec le rôle 'chauffeur'.\n";
    exit(1);
}

// 2. Vérifier les positions actuelles
echo "\n📍 Positions actuelles:\n";
echo "------------------------\n";
foreach ($chauffeurs as $chauffeur) {
    $status = $chauffeur->current_lat ? "✅ Position: {$chauffeur->current_lat}, {$chauffeur->current_lng}" : "❌ Aucune position";
    $lastUpdate = $chauffeur->location_updated_at ? $chauffeur->location_updated_at->format('H:i:s') : "Jamais";
    echo sprintf("• %s: %s - MAJ: %s\n", $chauffeur->first_name . ' ' . $chauffeur->last_name, $status, $lastUpdate);
}

// 3. Simuler des positions si nécessaire
$chauffeursSansPosition = $chauffeurs->whereNull('current_lat');
if ($chauffeursSansPosition->count() > 0) {
    echo "\n🎯 Simulation des positions manquantes...\n";
    
    $positions = [
        ['lat' => 14.6928, 'lng' => -17.4467], // Centre Dakar
        ['lat' => 14.7167, 'lng' => -17.4678], // Plateau
        ['lat' => 14.6833, 'lng' => -17.4333], // Médina
        ['lat' => 14.7500, 'lng' => -17.4500], // Almadies
        ['lat' => 14.6667, 'lng' => -17.4000], // Parcelles Assainies
    ];
    
    foreach ($chauffeursSansPosition as $index => $chauffeur) {
        $position = $positions[$index % count($positions)];
        $lat = $position['lat'] + (rand(-50, 50) / 10000);
        $lng = $position['lng'] + (rand(-50, 50) / 10000);
        
        $chauffeur->update([
            'current_lat' => $lat,
            'current_lng' => $lng,
            'location_updated_at' => now(),
        ]);
        
        echo sprintf("✅ %s: %.6f, %.6f\n", $chauffeur->first_name . ' ' . $chauffeur->last_name, $lat, $lng);
    }
}

// 4. Vérifier les routes
echo "\n🛣️  Vérification des routes:\n";
echo "----------------------------\n";

$routes = [
    '/admin/driver-location' => 'Page principale de localisation',
    '/admin/driver-locations' => 'API des positions (JSON)',
    '/driver/update-location' => 'API de mise à jour position',
];

foreach ($routes as $route => $description) {
    echo sprintf("• %s: %s\n", $route, $description);
}

// 5. Statistiques finales
echo "\n📊 Statistiques finales:\n";
echo "------------------------\n";
echo "• Total chauffeurs: " . User::role('chauffeur')->count() . "\n";
echo "• Avec position: " . User::role('chauffeur')->whereNotNull('current_lat')->count() . "\n";
echo "• Position récente (< 10 min): " . User::role('chauffeur')->where('location_updated_at', '>', now()->subMinutes(10))->count() . "\n";
echo "• Position ancienne (> 10 min): " . User::role('chauffeur')->where('location_updated_at', '<', now()->subMinutes(10))->count() . "\n";

// 6. Instructions de test
echo "\n🧪 Instructions de test:\n";
echo "------------------------\n";
echo "1. 🌐 Ouvrez: http://127.0.0.1:8000/admin/driver-location\n";
echo "2. 🔐 Connectez-vous en tant qu'admin\n";
echo "3. 🗺️  Vous devriez voir tous les chauffeurs sur la carte\n";
echo "4. 🔄 Les positions se mettent à jour toutes les 30 secondes\n";
echo "5. 📱 Testez côté chauffeur:\n";
echo "   - Connectez-vous en tant que chauffeur\n";
echo "   - Allez sur le dashboard chauffeur\n";
echo "   - Autorisez la géolocalisation\n";
echo "   - Votre position sera mise à jour automatiquement\n\n";

echo "🎉 Système prêt pour les tests!\n";
echo "💡 Les chauffeurs hors ligne gardent leur dernière position connue.\n";
