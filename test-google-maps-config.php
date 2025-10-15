<?php declare(strict_types=1); 
// Script de test pour vérifier la configuration Google Maps

echo "=== Configuration Google Maps ===\n";

// Vérifier si le fichier .env existe
if (!file_exists('.env')) {
    echo "❌ Fichier .env non trouvé\n";
    exit(1);
}

// Charger les variables d'environnement
$envContent = file_get_contents('.env');
$lines = explode("\n", $envContent);

$googleMapsKey = null;
foreach ($lines as $line) {
    if (strpos($line, 'GOOGLE_MAPS_API_KEY') === 0) {
        $googleMapsKey = trim(substr($line, strpos($line, '=') + 1));
        break;
    }
}

if (!$googleMapsKey || $googleMapsKey === 'votre_cle_api_google_maps_ici') {
    echo "❌ Clé API Google Maps non configurée ou non valide\n";
    echo "📝 Pour configurer :\n";
    echo "   1. Obtenez une clé API sur https://console.cloud.google.com/\n";
    echo "   2. Remplacez 'votre_cle_api_google_maps_ici' dans le fichier .env\n";
    echo "   3. Redémarrez votre serveur Laravel\n";
} else {
    echo "✅ Clé API Google Maps configurée : " . substr($googleMapsKey, 0, 10) . "...\n";
}

echo "\n=== Endpoints qui utilisent Google Maps ===\n";
echo "• Page de localisation des chauffeurs : /admin/driver-location\n";
echo "• Page de suivi des courses : /courses/{id}/suivi\n";
echo "• Système de trafic : /traffic\n";

echo "\n=== Instructions ===\n";
echo "1. Remplacez 'votre_cle_api_google_maps_ici' par votre vraie clé API\n";
echo "2. Redémarrez le serveur : php artisan serve\n";
echo "3. Accédez à : http://127.0.0.1:8001/admin/driver-location\n";

echo "\n=== Sécurité ===\n";
echo "• Restreignez votre clé API aux domaines autorisés\n";
echo "• Limitez les APIs activées (Maps JavaScript API uniquement)\n";
echo "• Surveillez l'utilisation dans Google Cloud Console\n";


