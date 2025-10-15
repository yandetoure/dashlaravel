<?php declare(strict_types=1);

require_once 'vendor/autoload.php';

use App\Models\User;
use Illuminate\Support\Facades\DB;

// Charger Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test du Système de Localisation des Chauffeurs ===\n\n";

// 1. Vérifier les chauffeurs existants
echo "1. Chauffeurs existants :\n";
$chauffeurs = User::role('chauffeur')->get();
if ($chauffeurs->isEmpty()) {
    echo "❌ Aucun chauffeur trouvé\n";
    echo "📝 Créez d'abord des utilisateurs avec le rôle 'chauffeur'\n\n";
} else {
    foreach ($chauffeurs as $chauffeur) {
        echo "✅ {$chauffeur->first_name} {$chauffeur->last_name} (ID: {$chauffeur->id})\n";
        echo "   - Email: {$chauffeur->email}\n";
        echo "   - Téléphone: " . ($chauffeur->phone_number ?: 'Non renseigné') . "\n";
        echo "   - Position actuelle: ";
        if ($chauffeur->current_lat && $chauffeur->current_lng) {
            echo "Lat: {$chauffeur->current_lat}, Lng: {$chauffeur->current_lng}\n";
        } else {
            echo "Non définie\n";
        }
        echo "   - Dernière MAJ: " . ($chauffeur->location_updated_at ?? 'Jamais') . "\n\n";
    }
}

// 2. Simuler la mise à jour de position d'un chauffeur
echo "2. Simulation de mise à jour de position :\n";
if ($chauffeurs->isNotEmpty()) {
    $chauffeur = $chauffeurs->first();
    
    // Coordonnées aléatoires autour de Dakar
    $lat = 14.6928 + (rand(-100, 100) / 10000); // ±0.01 degrés
    $lng = -17.4467 + (rand(-100, 100) / 10000);
    
    echo "✅ Mise à jour de la position de {$chauffeur->first_name} {$chauffeur->last_name}\n";
    echo "   - Nouvelle position: Lat: {$lat}, Lng: {$lng}\n";
    
    $chauffeur->update([
        'current_lat' => $lat,
        'current_lng' => $lng,
        'location_updated_at' => now(),
    ]);
    
    echo "   - Position mise à jour avec succès !\n\n";
} else {
    echo "❌ Impossible de tester - aucun chauffeur disponible\n\n";
}

// 3. Tester l'API de récupération des positions
echo "3. Test de l'API de récupération :\n";
try {
    $controller = new \App\Http\Controllers\DriverLocationController();
    
    // Simuler une requête authentifiée
    $user = User::role('admin')->first() ?? User::first();
    if ($user) {
        auth()->login($user);
        
        $response = $controller->getAllDriversLocations();
        $data = json_decode($response->getContent(), true);
        
        echo "✅ API getAllDriversLocations fonctionne\n";
        echo "   - Nombre de chauffeurs retournés: " . count($data) . "\n";
        
        foreach ($data as $driver) {
            echo "   - {$driver['nom']}: Lat: {$driver['position']['lat']}, Lng: {$driver['position']['lng']}\n";
        }
        echo "\n";
    } else {
        echo "❌ Aucun utilisateur admin trouvé pour tester l'API\n\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur lors du test de l'API: " . $e->getMessage() . "\n\n";
}

// 4. Instructions pour tester manuellement
echo "4. Instructions pour tester manuellement :\n";
echo "✅ Accédez à la page de test Google Maps :\n";
echo "   http://127.0.0.1:8001/test-google-maps\n\n";
echo "✅ Accédez à la page de localisation des chauffeurs :\n";
echo "   http://127.0.0.1:8001/admin/driver-location\n\n";
echo "✅ Testez l'API directement :\n";
echo "   http://127.0.0.1:8001/admin/driver-locations\n\n";

// 5. Vérifier la configuration Google Maps
echo "5. Configuration Google Maps :\n";
$apiKey = env('GOOGLE_MAPS_API_KEY');
if ($apiKey && $apiKey !== 'votre_cle_api_google_maps_ici') {
    echo "✅ Clé API configurée : " . substr($apiKey, 0, 10) . "...\n";
} else {
    echo "❌ Clé API non configurée ou invalide\n";
}

echo "\n=== Test terminé ===\n";
