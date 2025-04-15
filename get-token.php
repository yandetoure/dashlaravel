<?php declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

$credentialsPath = __DIR__ . '/storage/app/google-calendar/credentials.json';
$tokenPath = __DIR__ . '/storage/app/google-calendar/token.json';

$client = new Google_Client();
$client->setAuthConfig($credentialsPath);
$client->addScope(Google_Service_Calendar::CALENDAR);
$client->setAccessType('offline');
$client->setPrompt('consent');
$client->setRedirectUri('http://localhost:8000/oauth2callback');

// Vérifie si un token existe déjà
if (file_exists($tokenPath)) {
    $accessToken = json_decode(file_get_contents($tokenPath), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new InvalidArgumentException("Le contenu du fichier token.json est invalide : " . json_last_error_msg());
    }
    $client->setAccessToken($accessToken);
}

// Si le token est expiré ou inexistant, demande une autorisation
if ($client->isAccessTokenExpired()) {
    if ($client->getRefreshToken()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    } else {
        // Ouvre une URL pour obtenir le code d'authentification
        $authUrl = $client->createAuthUrl();
        echo "1️⃣ Ouvre ce lien dans ton navigateur :\n$authUrl\n";
        echo "2️⃣ Connecte-toi et autorise l'application.\n";
        echo "3️⃣ Copie-colle le code ici : ";
        $authCode = trim(fgets(STDIN));

        // Échange le code contre un token d'accès
        try {
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            if (array_key_exists('error', $accessToken)) {
                throw new Exception($accessToken['error']);
            }
            $client->setAccessToken($accessToken);

            // Vérifie si un refresh token est présent
            if (!empty($accessToken['refresh_token'])) {
                file_put_contents($tokenPath, json_encode($accessToken));
                echo "✅ Token enregistré avec succès ! 🎉\n";
            } else {
                echo "❌ Erreur : Pas de refresh token. Vérifie tes paramètres OAuth.\n";
            }
        } catch (Exception $e) {
            echo 'Erreur lors de l\'échange du code : ' . $e->getMessage();
            exit;
        }
    }
}

// Vérification finale
if ($client->isAccessTokenExpired()) {
    die("❌ Erreur : Impossible d'obtenir un token valide.\n");
} else {
    echo "✅ Authentification réussie ! Tu peux maintenant utiliser Google Calendar.\n";
}
