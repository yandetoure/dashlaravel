<?php declare(strict_types=1); 
/**
 * Script de test pour simuler des positions de chauffeurs
 * Usage: php test-driver-locations.php
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\User;

// Configuration de base de Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🚗 Test de simulation des positions des chauffeurs\n";
echo "================================================\n\n";

// Récupérer tous les chauffeurs
$chauffeurs = User::role('chauffeur')->get();

if ($chauffeurs->isEmpty()) {
    echo "❌ Aucun chauffeur trouvé dans la base de données.\n";
    echo "💡 Créez d'abord des utilisateurs avec le rôle 'chauffeur'.\n";
    exit(1);
}

echo "📋 Chauffeurs trouvés: " . $chauffeurs->count() . "\n\n";

// Positions de test autour de Dakar
$positions = [
    ['lat' => 14.6928, 'lng' => -17.4467], // Centre Dakar
    ['lat' => 14.7167, 'lng' => -17.4678], // Plateau
    ['lat' => 14.6833, 'lng' => -17.4333], // Médina
    ['lat' => 14.7500, 'lng' => -17.4500], // Almadies
    ['lat' => 14.6667, 'lng' => -17.4000], // Parcelles Assainies
    ['lat' => 14.7000, 'lng' => -17.5000], // Yoff
    ['lat' => 14.6500, 'lng' => -17.4500], // Guédiawaye
    ['lat' => 14.8000, 'lng' => -17.4000], // Rufisque
];

$statuts = ['disponible', 'en_course', 'en_attente'];

echo "🎯 Simulation des positions:\n";
echo "----------------------------\n";

foreach ($chauffeurs as $index => $chauffeur) {
    // Assigner une position aléatoire
    $position = $positions[$index % count($positions)];
    
    // Ajouter une petite variation aléatoire (±0.01 degrés)
    $lat = $position['lat'] + (rand(-100, 100) / 10000);
    $lng = $position['lng'] + (rand(-100, 100) / 10000);
    
    // Assigner un statut aléatoire
    $statut = $statuts[array_rand($statuts)];
    
    // Mettre à jour la position
    $chauffeur->update([
        'current_lat' => $lat,
        'current_lng' => $lng,
        'location_updated_at' => now(),
    ]);
    
    echo sprintf(
        "✅ %s: %.6f, %.6f (%s) - MAJ: %s\n",
        $chauffeur->first_name . ' ' . $chauffeur->last_name,
        $lat,
        $lng,
        $statut,
        now()->format('H:i:s')
    );
}

echo "\n🎉 Simulation terminée!\n";
echo "🌐 Testez maintenant sur: http://127.0.0.1:8000/admin/driver-location\n\n";

echo "📊 Statistiques:\n";
echo "----------------\n";
echo "• Total chauffeurs: " . User::role('chauffeur')->count() . "\n";
echo "• Positions mises à jour: " . User::role('chauffeur')->whereNotNull('current_lat')->count() . "\n";
echo "• Dernière MAJ récente (< 10 min): " . User::role('chauffeur')->where('location_updated_at', '>', now()->subMinutes(10))->count() . "\n\n";

echo "💡 Pour tester le système:\n";
echo "1. Ouvrez http://127.0.0.1:8000/admin/driver-location\n";
echo "2. Connectez-vous en tant qu'admin\n";
echo "3. Vous devriez voir tous les chauffeurs sur la carte\n";
echo "4. Les positions se mettront à jour automatiquement toutes les 30 secondes\n\n";

echo "🔧 Pour tester la géolocalisation côté chauffeur:\n";
echo "1. Connectez-vous en tant que chauffeur\n";
echo "2. Allez sur le dashboard chauffeur\n";
echo "3. Autorisez la géolocalisation dans votre navigateur\n";
echo "4. Votre position sera mise à jour automatiquement\n";
