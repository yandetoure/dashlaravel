<?php declare(strict_types=1);

/**
 * Script de test pour l'API Google Maps
 * Usage: php test-google-maps.php
 */

// Charger les variables d'environnement
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

$apiKey = $_ENV['GOOGLE_MAPS_API_KEY'] ?? 'your_api_key_here';

echo "🔍 Test de l'API Google Maps\n";
echo "============================\n\n";

if ($apiKey === 'your_api_key_here') {
    echo "❌ Clé API non configurée\n";
    echo "Ajoutez GOOGLE_MAPS_API_KEY=votre_cle dans le fichier .env\n";
    exit(1);
}

echo "✅ Clé API configurée\n\n";

// Test avec un point au Sénégal
$testPoints = [
    'Dakar Centre' => ['origin' => '14.7167,-17.4677', 'destination' => '14.7500,-17.4500'],
    'Dakar Plateau' => ['origin' => '14.7500,-17.4500', 'destination' => '14.7200,-17.4600'],
    'Route de Thiès' => ['origin' => '14.7833,-16.9333', 'destination' => '14.7167,-17.4677']
];

foreach ($testPoints as $name => $coords) {
    echo "📍 Test de {$name}...\n";

    $url = 'https://maps.googleapis.com/maps/api/directions/json';
    $params = [
        'origin' => $coords['origin'],
        'destination' => $coords['destination'],
        'key' => $apiKey,
        'departure_time' => 'now',
        'traffic_model' => 'best_guess'
    ];

    $fullUrl = $url . '?' . http_build_query($params);

    echo "   URL: " . str_replace($apiKey, '***', $fullUrl) . "\n";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fullUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "   Code HTTP: {$httpCode}\n";

    if ($response) {
        $data = json_decode($response, true);

        if ($data['status'] === 'OK') {
            $routes = $data['routes'] ?? [];
            echo "   ✅ Succès: " . count($routes) . " route(s) trouvée(s)\n";

            foreach ($routes as $i => $route) {
                $legs = $route['legs'] ?? [];
                foreach ($legs as $leg) {
                    $duration = $leg['duration_in_traffic']['value'] ?? $leg['duration']['value'] ?? 0;
                    $durationNormal = $leg['duration']['value'] ?? $duration;
                    $congestionRatio = $durationNormal > 0 ? $duration / $durationNormal : 1;
                    $delay = round(($congestionRatio - 1) * 100);

                    echo "   🚗 Route " . ($i + 1) . ": {$duration}s (normal: {$durationNormal}s, retard: +{$delay}%)\n";
                }
            }
        } else {
            echo "   ❌ Erreur: " . ($data['status'] ?? 'Inconnue') . "\n";
            if (isset($data['error_message'])) {
                echo "   Message: " . $data['error_message'] . "\n";
            }
        }
    } else {
        echo "   ❌ Erreur de connexion\n";
    }

    echo "\n";

    // Pause entre les requêtes
    sleep(1);
}

echo "🎯 Test terminé\n";
echo "Si vous voyez des données de trafic, l'API fonctionne correctement !\n";
