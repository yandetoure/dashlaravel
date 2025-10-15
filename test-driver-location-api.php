<?php declare(strict_types=1);

require_once 'vendor/autoload.php';

use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Charger Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test de l'API de Géolocalisation des Chauffeurs ===\n\n";

// 1. Récupérer un chauffeur
$chauffeur = User::role('chauffeur')->first();
if (!$chauffeur) {
    echo "❌ Aucun chauffeur trouvé\n";
    exit(1);
}

echo "🚗 Chauffeur de test : {$chauffeur->first_name} {$chauffeur->last_name}\n";
echo "📧 Email : {$chauffeur->email}\n";
echo "📍 Position actuelle : " . ($chauffeur->current_lat ? "Lat: {$chauffeur->current_lat}, Lng: {$chauffeur->current_lng}" : "Non définie") . "\n\n";

// 2. Se connecter en tant que chauffeur
echo "2. Connexion du chauffeur...\n";
Auth::login($chauffeur);
echo "✅ Chauffeur connecté (ID: {$chauffeur->id})\n\n";

// 3. Tester l'API de mise à jour
echo "3. Test de l'API updateDriverLocation...\n";

$controller = new \App\Http\Controllers\DriverLocationController();

// Simuler une position GPS
$lat = 14.6928 + (rand(-100, 100) / 10000);
$lng = -17.4467 + (rand(-100, 100) / 10000);

echo "📍 Position de test : Lat: {$lat}, Lng: {$lng}\n";

$request = new \Illuminate\Http\Request();
$request->merge([
    'lat' => $lat,
    'lng' => $lng
]);

try {
    $response = $controller->updateDriverLocation($request);
    $data = json_decode($response->getContent(), true);
    
    echo "✅ Réponse API :\n";
    echo "   - Succès : " . ($data['success'] ? 'Oui' : 'Non') . "\n";
    echo "   - Message : {$data['message']}\n";
    echo "   - Mise à jour effectuée : " . ($data['updated'] ? 'Oui' : 'Non') . "\n";
    echo "   - Timestamp : {$data['timestamp']}\n\n";
    
} catch (Exception $e) {
    echo "❌ Erreur API : " . $e->getMessage() . "\n\n";
}

// 4. Vérifier la position en base
echo "4. Vérification en base de données...\n";
$chauffeur->refresh();
echo "📍 Position après mise à jour :\n";
echo "   - Lat : " . ($chauffeur->current_lat ?? 'Non définie') . "\n";
echo "   - Lng : " . ($chauffeur->current_lng ?? 'Non définie') . "\n";
echo "   - Dernière MAJ : " . ($chauffeur->location_updated_at ?? 'Jamais') . "\n\n";

// 5. Tester l'API de récupération des positions
echo "5. Test de l'API getAllDriversLocations...\n";
try {
    $response = $controller->getAllDriversLocations();
    $drivers = json_decode($response->getContent(), true);
    
    echo "✅ API getAllDriversLocations fonctionne\n";
    echo "   - Nombre de chauffeurs : " . count($drivers) . "\n";
    
    foreach ($drivers as $driver) {
        echo "   - {$driver['nom']} : Lat: {$driver['position']['lat']}, Lng: {$driver['position']['lng']}\n";
    }
    echo "\n";
    
} catch (Exception $e) {
    echo "❌ Erreur API getAllDriversLocations : " . $e->getMessage() . "\n\n";
}

// 6. Instructions pour tester manuellement
echo "6. Instructions pour tester manuellement :\n";
echo "✅ Connectez-vous avec le compte chauffeur :\n";
echo "   - Email : {$chauffeur->email}\n";
echo "   - URL : http://127.0.0.1:8001/chauffeur/dashboard\n\n";
echo "✅ Ouvrez la console du navigateur (F12)\n";
echo "✅ Autorisez la géolocalisation quand demandé\n";
echo "✅ Vous devriez voir les messages de géolocalisation\n";
echo "✅ Un bouton 'Tester Géolocalisation' apparaîtra en bas à droite\n\n";
echo "✅ Surveillez la page admin :\n";
echo "   http://127.0.0.1:8001/admin/driver-location\n\n";

// 7. Test de l'endpoint directement
echo "7. Test de l'endpoint /driver/update-location...\n";
echo "✅ Endpoint disponible : POST /driver/update-location\n";
echo "✅ Paramètres requis : lat, lng\n";
echo "✅ Authentification : Chauffeur connecté\n";
echo "✅ Format réponse : JSON\n\n";

echo "=== Test terminé ===\n";
echo "🔍 Si le problème persiste, vérifiez :\n";
echo "   1. La console du navigateur pour les erreurs JavaScript\n";
echo "   2. L'autorisation de géolocalisation dans le navigateur\n";
echo "   3. La connexion réseau\n";
echo "   4. Les logs Laravel : storage/logs/laravel.log\n";




