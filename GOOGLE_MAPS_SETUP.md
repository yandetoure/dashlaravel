# Configuration Google Maps API pour les Alertes de Trafic

## 1. Obtenir une Clé API Google Maps

### Étape 1: Créer un projet Google Cloud
1. Allez sur [Google Cloud Console](https://console.cloud.google.com/)
2. Créez un nouveau projet ou sélectionnez un projet existant
3. Activez la facturation pour votre projet

### Étape 2: Activer les APIs nécessaires
Activez les APIs suivantes dans votre projet :
- **Directions API** - Pour obtenir les données de trafic en temps réel
- **Maps JavaScript API** - Pour l'affichage des cartes (optionnel)
- **Geocoding API** - Pour la conversion d'adresses (optionnel)

### Étape 3: Créer une clé API
1. Dans la console Google Cloud, allez dans "APIs & Services" > "Credentials"
2. Cliquez sur "Create Credentials" > "API Key"
3. Copiez votre clé API

## 2. Configurer la Clé API dans Laravel

### Ajouter la clé dans le fichier .env
```bash
# Ajoutez cette ligne dans votre fichier .env
GOOGLE_MAPS_API_KEY=votre_cle_api_google_maps_ici
```

### Restreindre la clé API (Recommandé)
Pour la sécurité, restreignez votre clé API :
1. Dans Google Cloud Console, cliquez sur votre clé API
2. Dans "Application restrictions", sélectionnez "HTTP referrers"
3. Ajoutez votre domaine de production
4. Dans "API restrictions", sélectionnez "Restrict key"
5. Sélectionnez uniquement les APIs nécessaires

## 3. Tester la Configuration

### Tester la commande de rafraîchissement
```bash
php artisan traffic:refresh
```

### Vérifier les logs
Si des erreurs surviennent, vérifiez les logs Laravel :
```bash
tail -f storage/logs/laravel.log
```

## 4. Configuration du Cron Job (Production)

### Ajouter au crontab
```bash
# Éditer le crontab
crontab -e

# Ajouter cette ligne pour rafraîchir toutes les 5 minutes
*/5 * * * * cd /path/to/your/project && php artisan traffic:refresh >> /dev/null 2>&1
```

### Ou utiliser Laravel Scheduler
Dans `app/Console/Kernel.php`, ajoutez :
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('traffic:refresh')->everyFiveMinutes();
}
```

## 5. Avantages de Google Maps vs TomTom

### Google Maps
✅ **Meilleure couverture au Sénégal**
✅ **Données de trafic en temps réel**
✅ **API Directions avec données de congestion**
✅ **Gratuit jusqu'à 1000 requêtes/jour**
✅ **Documentation complète**

### TomTom
❌ **Couverture limitée au Sénégal**
❌ **Points trop éloignés des segments existants**
❌ **API Traffic Flow moins précise**

## 6. Structure des Données Récupérées

### Types d'incidents détectés
- **Congestion** : Embouteillages majeurs (>50% de retard)
- **Slow Traffic** : Ralentissements (20-50% de retard)
- **Normal** : Trafic fluide (<20% de retard)

### Zones surveillées au Sénégal
- Dakar Centre
- Dakar Plateau
- Dakar Almadies
- Route de Thiès
- Route de Rufisque

## 7. Dépannage

### Erreur "API key not valid"
- Vérifiez que la clé API est correcte
- Assurez-vous que les APIs sont activées
- Vérifiez les restrictions de la clé

### Erreur "Quota exceeded"
- Vérifiez votre quota dans Google Cloud Console
- Réduisez la fréquence de rafraîchissement
- Optimisez les requêtes

### Aucune donnée récupérée
- Vérifiez la connectivité réseau
- Testez avec des coordonnées connues
- Vérifiez les logs d'erreur

## 8. Coûts

### Google Maps Directions API
- **Gratuit** : 1000 requêtes/jour
- **Payant** : $5 USD par 1000 requêtes supplémentaires

### Estimation pour CPRO
- 5 zones × 12 requêtes/heure × 24h = 1440 requêtes/jour
- Coût estimé : ~$2.20 USD/mois

## 9. Sécurité

### Bonnes pratiques
- Ne jamais exposer la clé API côté client
- Utiliser des restrictions de domaine
- Surveiller l'utilisation de l'API
- Roter les clés API si nécessaire

### Variables d'environnement
```bash
# Production
GOOGLE_MAPS_API_KEY=production_key_here

# Développement
GOOGLE_MAPS_API_KEY=development_key_here
``` 
