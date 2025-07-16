# 🚀 Configuration Rapide Google Maps API

## Étape 1: Obtenir une Clé API (5 minutes)

1. **Allez sur** [Google Cloud Console](https://console.cloud.google.com/)
2. **Créez un projet** ou sélectionnez un existant
3. **Activez la facturation** (requis pour l'API)
4. **Activez l'API Directions** :
   - Menu → "APIs & Services" → "Library"
   - Recherchez "Directions API"
   - Cliquez "Enable"
5. **Créez une clé API** :
   - Menu → "APIs & Services" → "Credentials"
   - "Create Credentials" → "API Key"
   - Copiez la clé

## Étape 2: Configurer Laravel (2 minutes)

1. **Ajoutez la clé dans votre fichier `.env`** :
```bash
GOOGLE_MAPS_API_KEY=votre_cle_api_ici
```

2. **Testez la configuration** :
```bash
php test-google-maps.php
```

## Étape 3: Tester les Données de Trafic (1 minute)

```bash
php artisan traffic:refresh
```

## Étape 4: Voir les Résultats

Allez sur votre page de trafic : `/traffic`

## ✅ Avantages Google Maps vs TomTom

| Fonctionnalité | Google Maps | TomTom |
|----------------|-------------|--------|
| Couverture Sénégal | ✅ Excellente | ❌ Limitée |
| Données temps réel | ✅ Oui | ✅ Oui |
| Gratuit | ✅ 1000 req/jour | ✅ 2500 req/jour |
| Précision | ✅ Très haute | ⚠️ Moyenne |
| Documentation | ✅ Complète | ✅ Bonne |

## 🔧 Dépannage Rapide

### Erreur "API key not valid"
- Vérifiez que l'API Directions est activée
- Vérifiez que la facturation est activée

### Erreur "Quota exceeded"
- Vérifiez votre quota dans Google Cloud Console
- Réduisez la fréquence de rafraîchissement

### Aucune donnée
- Testez avec `php test-google-maps.php`
- Vérifiez les logs : `tail -f storage/logs/laravel.log`

## 💰 Coûts Estimés

- **Gratuit** : 1000 requêtes/jour
- **Payant** : $5 USD par 1000 requêtes supplémentaires
- **Estimation CPRO** : ~$2.20 USD/mois

## 🚀 Production

1. **Restreignez votre clé API** :
   - HTTP referrers : votre domaine
   - API restrictions : Directions API uniquement

2. **Configurez le cron** :
```bash
*/5 * * * * cd /path/to/project && php artisan traffic:refresh
```

3. **Surveillez les logs** :
```bash
tail -f storage/logs/laravel.log
```

## 📞 Support

Si vous avez des problèmes :
1. Vérifiez les logs Laravel
2. Testez avec `php test-google-maps.php`
3. Vérifiez la console Google Cloud
4. Consultez `GOOGLE_MAPS_SETUP.md` pour plus de détails 
