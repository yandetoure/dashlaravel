# Configuration Google Maps pour le Suivi des Courses

## Prérequis

Pour utiliser la fonctionnalité de suivi en temps réel des courses, vous devez configurer l'API Google Maps.

## Étapes de configuration

### 1. Obtenir une clé API Google Maps

1. Allez sur [Google Cloud Console](https://console.cloud.google.com/)
2. Créez un nouveau projet ou sélectionnez un projet existant
3. Activez l'API Google Maps JavaScript
4. Créez une clé API :
   - Allez dans "APIs & Services" > "Credentials"
   - Cliquez sur "Create Credentials" > "API Key"
   - Copiez votre clé API

### 2. Configurer la clé dans l'application

Ajoutez la clé API dans votre fichier `.env` :

```env
GOOGLE_MAPS_API_KEY=votre_cle_api_google_maps_ici
```

### 3. Restreindre la clé API (Recommandé)

Pour des raisons de sécurité, restreignez votre clé API :

1. Dans Google Cloud Console, allez dans "APIs & Services" > "Credentials"
2. Cliquez sur votre clé API
3. Dans "Application restrictions", sélectionnez "HTTP referrers"
4. Ajoutez votre domaine : `https://votre-domaine.com/*`
5. Dans "API restrictions", sélectionnez "Restrict key"
6. Sélectionnez "Maps JavaScript API"

## Fonctionnalités disponibles

- **Géolocalisation en temps réel** : Suivi de la position du chauffeur
- **Navigation vers le client** : Itinéraire optimisé vers l'adresse de ramassage
- **Calcul de distance** : Distance en temps réel entre le chauffeur et le client
- **Timer de course** : Chronomètre depuis le début de la course
- **Interface responsive** : Compatible mobile et desktop

## Utilisation

1. Le chauffeur démarre une course depuis la liste des courses
2. Il est automatiquement redirigé vers la page de suivi
3. Sa position est affichée en temps réel sur la carte
4. L'adresse du client est marquée et l'itinéraire affiché
5. Il peut terminer ou annuler la course directement depuis cette page

## Notes importantes

- La géolocalisation nécessite l'autorisation du navigateur
- L'API Google Maps a des limites de requêtes (voir la documentation Google)
- Pour la production, configurez les restrictions de clé API appropriées