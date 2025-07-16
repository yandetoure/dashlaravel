# 🚦 Système d'Alertes Trafic - Configuration

Ce guide vous explique comment configurer le système d'alertes trafic pour le Sénégal utilisant l'API TomTom.

## 📋 Prérequis

1. **Compte TomTom Developer** : Inscrivez-vous sur [TomTom Developer Portal](https://developer.tomtom.com/)
2. **Clé API TomTom** : Créez un projet et récupérez votre clé API (gratuit jusqu'à 2 500 requêtes/jour)

## ⚙️ Configuration

### 1. Ajouter la clé API dans le fichier .env

Ajoutez cette ligne dans votre fichier `.env` :

```env
TOMTOM_API_KEY=VOTRE_CLE_API_TOMTOM
```

### 2. Exécuter les migrations

```bash
php artisan migrate
```

### 3. Tester la commande de rafraîchissement

```bash
php artisan traffic:refresh
```

### 4. Configurer le planificateur de tâches (optionnel)

Pour rafraîchir automatiquement les données toutes les 5 minutes, ajoutez cette ligne à votre crontab :

```bash
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

## 🗺️ Utilisation

### Accéder à la carte de trafic

Visitez : `http://votre-domaine.com/traffic`

### Fonctionnalités disponibles

- **Carte interactive** : Affichage des incidents sur une carte TomTom
- **Filtrage par gravité** : Critiques, majeurs, mineurs
- **Actualisation manuelle** : Bouton pour rafraîchir les données
- **Liste détaillée** : Vue liste avec détails de chaque incident
- **API JSON** : Endpoint `/traffic/api` pour récupérer les données

### Types d'incidents supportés

- 🚗 **Accidents** : Collisions et incidents routiers
- 🚧 **Travaux** : Construction et maintenance
- 🚦 **Congestion** : Embouteillages et ralentissements
- 🌧️ **Météo** : Conditions météorologiques
- 🚫 **Fermetures** : Routes fermées
- ⚠️ **Autres** : Autres types d'incidents

## 🔧 Personnalisation

### Modifier la zone de surveillance

Dans `app/Http/Controllers/TrafficController.php`, modifiez la variable `$bbox` :

```php
// Zone autour de Dakar (actuel)
$bbox = '14.55,-17.5,14.7,-17.35';

// Pour une autre zone, utilisez le format : 'lat_min,lng_min,lat_max,lng_max'
```

### Ajouter de nouveaux types d'incidents

Dans `app/Models/TrafficIncident.php`, modifiez la méthode `getTypeIconAttribute()` :

```php
public function getTypeIconAttribute()
{
    return match($this->type) {
        'accident' => '🚗',
        'construction' => '🚧',
        'congestion' => '🚦',
        'weather' => '🌧️',
        'votre_nouveau_type' => '🆕', // Ajoutez ici
        default => '⚠️'
    };
}
```

## 📊 Monitoring

### Vérifier les incidents actifs

```bash
php artisan tinker
>>> App\Models\TrafficIncident::active()->count()
```

### Statistiques par gravité

```bash
php artisan tinker
>>> App\Models\TrafficIncident::active()->bySeverity('critical')->count()
>>> App\Models\TrafficIncident::active()->bySeverity('major')->count()
>>> App\Models\TrafficIncident::active()->bySeverity('minor')->count()
```

## 🚨 Dépannage

### Erreur "Clé API manquante"

1. Vérifiez que `TOMTOM_API_KEY` est défini dans votre fichier `.env`
2. Redémarrez votre serveur web après modification du `.env`

### Aucun incident affiché

1. Vérifiez votre quota TomTom (2 500 requêtes/jour gratuites)
2. Testez la commande : `php artisan traffic:refresh`
3. Vérifiez les logs Laravel : `storage/logs/laravel.log`

### Carte ne se charge pas

1. Vérifiez que la clé API TomTom est valide
2. Vérifiez votre connexion internet
3. Consultez la console du navigateur pour les erreurs JavaScript

## 📈 Améliorations possibles

- **Notifications push** : Alertes en temps réel
- **Historique** : Conservation des anciens incidents
- **Zones personnalisées** : Permettre aux utilisateurs de définir leurs zones
- **Intégration SMS** : Envoi d'alertes par SMS
- **Prédiction** : Analyse prédictive des embouteillages

## 🔗 Liens utiles

- [Documentation TomTom Traffic API](https://developer.tomtom.com/traffic-api)
- [TomTom Maps SDK](https://developer.tomtom.com/maps-sdk)
- [Laravel Scheduling](https://laravel.com/docs/scheduling) 
