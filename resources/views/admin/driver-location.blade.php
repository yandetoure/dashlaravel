<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mes Réservations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                        dark: '#1F2937',
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Styles modernes pour la page */
        body {
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 100%);
            min-height: 100vh;
        }
        
        .main-container {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            margin: 20px;
            padding: 30px;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .status-badge {
            top: -10px;
            right: -10px;
        }
        
        /* Styles personnalisés pour la page de localisation */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 100%);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(30, 58, 138, 0.3);
        }
        
        .bg-gradient-secondary {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(5, 150, 105, 0.3);
        }
        
        .driver-item {
            transition: all 0.3s ease;
            border-radius: 8px !important;
            margin-bottom: 0.2rem;
            padding: 0.4rem !important;
        }
        
        .driver-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.15);
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important;
            border-left: 4px solid #667eea;
        }
        
        .badge-sm {
            font-size: 0.65rem;
            padding: 0.2rem 0.4rem;
        }
        
        /* Style pour la liste scrollable */
        #driversList::-webkit-scrollbar {
            width: 6px;
        }
        
        #driversList::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        #driversList::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        
        #driversList::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        
        .driver-status-indicator {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            display: inline-block;
            position: relative;
            animation: pulse-status 2s infinite;
        }
        
        .driver-status-indicator[data-status="disponible"] {
            background-color: #10B981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
        }
        
        .driver-status-indicator[data-status="en_course"] {
            background-color: #3B82F6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
        }
        
        .driver-status-indicator[data-status="en_attente"] {
            background-color: #F59E0B;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.2);
        }
        
        @keyframes pulse-status {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .card {
            border-radius: 20px;
            overflow: hidden;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
        }
        
        .card-header {
            border-radius: 20px 20px 0 0 !important;
            border: none;
        }
        
        .btn {
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .badge {
            border-radius: 12px;
            font-weight: 600;
        }
        
        /* Styles pour les cartes de statistiques */
        .stats-card {
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 100%);
            color: white;
            border-radius: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(30, 58, 138, 0.3);
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.4);
        }
        
        .stats-icon {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        span{
            color: black;
        }
    </style>
</head>
<body>
<div class="main-container">
    <!-- En-tête amélioré -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-primary text-white">
                <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="mb-3 mb-md-0">
                            <h1 class="h4 mb-2 fw-bold text-black">
                        <i class="fas fa-map-marked-alt me-2"></i>
                        Chauffeurs
                    </h1>
                            <p class="text-black-50 mb-0 fs-6">
                                <i class="fas fa-eye me-1"></i>
                                Suivi temps réel
                            </p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                            <button id="refreshBtn" class="btn btn-light btn-lg">
                                <i class="fas fa-sync-alt me-2"></i>
                        Actualiser
                    </button>
                            <div class="badge bg-white bg-opacity-20 fs-6 px-4 py-3 text-white">
                                <i class="fas fa-circle text-success me-2" id="statusIndicator"></i>
                        <span id="updateStatus">En temps réel</span>
                    </div>
                            <div class="badge bg-white bg-opacity-20 fs-6 px-4 py-3 text-white">
                                <i class="fas fa-clock me-2"></i>
                        <span id="lastUpdate">--:--</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Carte -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-map me-2"></i>
                        Carte des Chauffeurs
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div id="map" style="height: 600px; width: 100%; border-radius: 0 0 15px 15px;"></div>
                </div>
            </div>
        </div>

        <!-- Liste des chauffeurs -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient-secondary text-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1 fw-bold text-white">
                            <i class="fas fa-users me-2"></i>
                                Chauffeurs Actifs
                        </h5>
                            <small class="text-white-50">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $chauffeurs->count() }} chauffeurs sur la carte
                            </small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-white bg-opacity-20 fs-5 px-3 py-2" id="driverCount">{{ $chauffeurs->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" id="driversList" style="max-height: 500px; overflow-y: auto;">
                        @foreach($chauffeurs as $chauffeur)
                            <div class="list-group-item border-0 px-2 py-1 driver-item" data-driver-id="{{ $chauffeur['id'] }}">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="driver-status-indicator me-2" data-status="{{ $chauffeur['statut'] }}"></div>
                                        <div>
                                            <div class="d-flex align-items-center mb-1">
                                                <h6 class="mb-0 fw-bold text-dark me-2" style="font-size: 0.8rem;">{{ $chauffeur['nom'] }}</h6>
                                                <span class="badge bg-{{ $chauffeur['statut'] === 'disponible' ? 'success' : ($chauffeur['statut'] === 'en_course' ? 'primary' : 'warning') }}" style="font-size: 0.6rem; padding: 0.15rem 0.3rem;">
                                                    {{ ucfirst(str_replace('_', ' ', $chauffeur['statut'])) }}
                                                </span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <small class="text-muted me-2" style="font-size: 0.65rem;">
                                            <i class="fas fa-car me-1"></i>
                                                    {{ $chauffeur['immatriculation'] ?? 'N/A' }}
                                                </small>
                                                <small class="text-muted" style="font-size: 0.65rem;">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $chauffeur['localisation']['derniere_maj'] ? \Carbon\Carbon::parse($chauffeur['localisation']['derniere_maj'])->diffForHumans() : 'Jamais' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-outline-primary btn-sm" onclick="centerOnDriver({{ $chauffeur['id'] }})" title="Centrer sur ce chauffeur" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                        <i class="fas fa-map-pin" style="font-size: 0.7rem;"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques générales améliorées -->
    <div class="row g-4 mt-4">
        <div class="col-md-3">
            <div class="stats-card text-center h-100">
                <div class="card-body p-4">
                    <div class="stats-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="fas fa-user-check text-white fs-3"></i>
                    </div>
                    <h3 class="fw-bold text-white mb-1" id="availableDrivers">{{ $chauffeurs->where('statut', 'disponible')->count() }}</h3>
                    <p class="text-white-50 mb-2 fw-semibold">Disponibles</p>
                    <small class="text-white">
                        <i class="fas fa-circle me-1"></i>
                        Prêts à prendre des courses
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card text-center h-100">
                <div class="card-body p-4">
                    <div class="stats-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="fas fa-route text-white fs-3"></i>
                    </div>
                    <h3 class="fw-bold text-white mb-1" id="activeDrivers">{{ $chauffeurs->where('statut', 'en_course')->count() }}</h3>
                    <p class="text-white-50 mb-2 fw-semibold">En Course</p>
                    <small class="text-white">
                        <i class="fas fa-circle me-1"></i>
                        Actuellement en service
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card text-center h-100">
                <div class="card-body p-4">
                    <div class="stats-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="fas fa-clock text-white fs-3"></i>
                    </div>
                    <h3 class="fw-bold text-white mb-1" id="waitingDrivers">{{ $chauffeurs->where('statut', 'en_attente')->count() }}</h3>
                    <p class="text-white-50 mb-2 fw-semibold">En Attente</p>
                    <small class="text-white">
                        <i class="fas fa-circle me-1"></i>
                        En attente de client
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card text-center h-100">
                <div class="card-body p-4">
                    <div class="stats-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="fas fa-users text-white fs-3"></i>
                    </div>
                    <h3 class="fw-bold text-white mb-1">{{ $chauffeurs->count() }}</h3>
                    <p class="text-white-50 mb-2 fw-semibold">Total</p>
                    <small class="text-white">
                        <i class="fas fa-circle me-1"></i>
                        Chauffeurs enregistrés
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Google Maps API -->
<script async
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=geometry&callback=initMap">
</script>

<script>
let map;
let drivers = @json($chauffeurs);
let driverMarkers = {};

// Vérifier que initMap est bien définie globalement
console.log("🔍 Vérification de la fonction initMap:", typeof window.initMap);

// Initialisation de la carte - déclarée globalement pour Google Maps
window.initMap = function() {
    try {
    // Position par défaut (Dakar)
        const defaultPosition = { lat: 14.71542, lng: -17.46055 };
    
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 12,
        center: defaultPosition,
        mapTypeControl: true,
        streetViewControl: false,
        fullscreenControl: true,
        zoomControl: true,
        styles: [
            {
                featureType: "poi",
                elementType: "labels",
                stylers: [{ visibility: "off" }]
            }
        ]
    });

    // Ajouter les marqueurs des chauffeurs
    addDriverMarkers();
    
        // Actualiser les positions toutes les 30 secondes
        setInterval(updateDriverLocations, 30000);
    
    // Mettre à jour l'heure de dernière actualisation
    updateLastUpdateTime();
        
        // Démarrer la mise à jour automatique
        updateDriverLocations();
        
        console.log("✅ Carte Google Maps initialisée avec succès");
    } catch (error) {
        console.error("❌ Erreur lors de l'initialisation de la carte:", error);
        document.getElementById("map").innerHTML = `
            <div style="display: flex; align-items: center; justify-content: center; height: 100%; background: #f8f9fa; color: #6c757d;">
                <div style="text-align: center;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                    <h4>Erreur de chargement de la carte</h4>
                    <p>Impossible de charger Google Maps. Vérifiez votre connexion internet.</p>
                </div>
            </div>
        `;
    }
}

// Confirmer que la fonction est bien définie globalement
console.log("✅ Fonction initMap définie globalement:", typeof window.initMap);

// Ajouter les marqueurs des chauffeurs
function addDriverMarkers() {
    drivers.forEach(driver => {
        // Vérifier que les coordonnées sont valides
        if (driver.localisation && driver.localisation.lat && driver.localisation.lng) {
            const lat = parseFloat(driver.localisation.lat);
            const lng = parseFloat(driver.localisation.lng);
            
            if (!isNaN(lat) && !isNaN(lng)) {
        const marker = new google.maps.Marker({
                    position: { lat: lat, lng: lng },
            map: map,
            title: driver.nom,
                    icon: getDriverIcon(driver.statut, true), // Par défaut en ligne au chargement
            animation: google.maps.Animation.DROP
        });

        // InfoWindow pour chaque chauffeur
        const infoWindow = new google.maps.InfoWindow({
            content: createInfoWindowContent(driver)
        });

        marker.addListener('click', function() {
            infoWindow.open(map, marker);
        });

        driverMarkers[driver.id] = marker;
            } else {
                console.warn("Coordonnées invalides pour le chauffeur:", driver.nom, "lat:", lat, "lng:", lng);
            }
        } else {
            console.warn("Données de localisation manquantes pour le chauffeur:", driver.nom);
        }
    });
}

// Obtenir l'icône selon le statut et l'état en ligne
function getDriverIcon(status, isOnline = true) {
    const baseIcons = {
        'disponible': {
            url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
            scaledSize: new google.maps.Size(40, 40)
        },
        'en_course': {
            url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
            scaledSize: new google.maps.Size(40, 40)
        },
        'en_attente': {
            url: 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png',
            scaledSize: new google.maps.Size(40, 40)
        }
    };
    
    // Si le chauffeur est hors ligne, utiliser des icônes grises
    if (!isOnline) {
        return {
            url: 'https://maps.google.com/mapfiles/ms/icons/gray-dot.png',
            scaledSize: new google.maps.Size(35, 35)
        };
    }
    
    return baseIcons[status] || baseIcons['disponible'];
}

// Fonction pour obtenir la couleur du statut
function getStatusColor(status) {
    const colors = {
        'disponible': '#10B981',
        'en_course': '#3B82F6',
        'en_attente': '#F59E0B'
    };
    return colors[status] || '#6B7280';
}

// Créer le contenu de l'InfoWindow
function createInfoWindowContent(driver) {
    // Obtenir l'adresse à partir des coordonnées (si disponible)
    let address = 'Adresse non disponible';
    if (driver.localisation && driver.localisation.lat && driver.localisation.lng) {
        // Pour l'instant, on affiche les coordonnées simplifiées
        // Dans une vraie application, vous pourriez utiliser l'API Geocoding de Google Maps
        address = `${parseFloat(driver.localisation.lat).toFixed(4)}, ${parseFloat(driver.localisation.lng).toFixed(4)}`;
    }
    
    return `
        <div style="padding: 8px; min-width: 150px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
            <div style="text-align: center;">
                <h4 style="margin: 0 0 5px 0; color: #1F2937; font-weight: 600; font-size: 14px;">${driver.nom}</h4>
                <div style="color: #6B7280; font-size: 11px;">
                    <i class="fas fa-map-marker-alt" style="margin-right: 3px;"></i>
                    ${address}
                </div>
            </div>
        </div>
    `;
}

// Obtenir le texte du statut
function getStatusText(status) {
    const statusTexts = {
        'disponible': 'Disponible',
        'en_course': 'En Course',
        'en_attente': 'En Attente'
    };
    return statusTexts[status] || 'Inconnu';
}

// Centrer la carte sur un chauffeur
function centerOnDriver(driverId) {
    const marker = driverMarkers[driverId];
    if (marker) {
        map.setCenter(marker.getPosition());
        map.setZoom(15);
        
        // Ouvrir l'InfoWindow
        const infoWindow = new google.maps.InfoWindow({
            content: createInfoWindowContent(drivers.find(d => d.id === driverId))
        });
        infoWindow.open(map, marker);
    }
}

// Actualiser les positions des chauffeurs
async function updateDriverLocations() {
    try {
        // Indicateur de mise à jour
        updateStatusIndicator('updating');
        
        const response = await fetch('/admin/driver-locations');
        const updatedDrivers = await response.json();
        
        // Mettre à jour les marqueurs
        updatedDrivers.forEach(driver => {
            if (driverMarkers[driver.id] && driver.position && driver.position.lat && driver.position.lng) {
                const marker = driverMarkers[driver.id];
                const lat = parseFloat(driver.position.lat);
                const lng = parseFloat(driver.position.lng);
                
                if (!isNaN(lat) && !isNaN(lng)) {
                    const newPosition = { lat: lat, lng: lng };
                
                // Vérifier si la position a changé
                const currentPosition = marker.getPosition();
                if (!currentPosition || 
                    Math.abs(currentPosition.lat() - newPosition.lat) > 0.00001 || 
                    Math.abs(currentPosition.lng() - newPosition.lng) > 0.00001) {
                    
                    marker.setPosition(newPosition);
                        marker.setIcon(getDriverIcon(driver.statut, driver.is_online));
                        
                        // Animation pour les mouvements seulement si le chauffeur est en ligne
                        if (driver.is_online) {
                            marker.setAnimation(google.maps.Animation.BOUNCE);
                            setTimeout(() => marker.setAnimation(null), 1000);
                        }
                    }
                    
                    // Mettre à jour l'icône selon le statut en ligne/hors ligne
                    marker.setIcon(getDriverIcon(driver.statut, driver.is_online));
                } else {
                    console.warn("Coordonnées invalides pour la mise à jour du chauffeur:", driver.nom);
                }
            }
        });
        
        // Mettre à jour l'heure de dernière actualisation
        updateLastUpdateTime();
        
        // Indicateur de succès
        updateStatusIndicator('success');
        
    } catch (error) {
        console.error('Erreur lors de la mise à jour des positions:', error);
        updateStatusIndicator('error');
    }
}

// Mettre à jour l'heure de dernière actualisation
function updateLastUpdateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('fr-FR', { 
        hour: '2-digit', 
        minute: '2-digit',
        second: '2-digit'
    });
    document.getElementById('lastUpdate').textContent = timeString;
}

// Mettre à jour l'indicateur de statut
function updateStatusIndicator(status) {
    const indicator = document.getElementById('statusIndicator');
    const statusText = document.getElementById('updateStatus');
    
    switch(status) {
        case 'updating':
            indicator.className = 'fas fa-circle text-warning me-1';
            indicator.style.animation = 'pulse 1s infinite';
            statusText.textContent = 'Mise à jour...';
            break;
        case 'success':
            indicator.className = 'fas fa-circle text-success me-1';
            indicator.style.animation = 'none';
            statusText.textContent = 'En temps réel';
            break;
        case 'error':
            indicator.className = 'fas fa-circle text-danger me-1';
            indicator.style.animation = 'pulse 1s infinite';
            statusText.textContent = 'Erreur';
            break;
        default:
            indicator.className = 'fas fa-circle text-success me-1';
            indicator.style.animation = 'none';
            statusText.textContent = 'En temps réel';
    }
}

// Bouton d'actualisation manuelle
document.getElementById('refreshBtn').addEventListener('click', function() {
    updateDriverLocations();
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Actualisation...';
    setTimeout(() => {
        this.innerHTML = '<i class="fas fa-sync-alt me-1"></i>Actualiser';
    }, 1000);
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // La fonction initMap sera appelée automatiquement par Google Maps
    // grâce au paramètre callback=initMap dans l'URL
    
    // Initialiser l'indicateur de statut
    updateStatusIndicator('success');
    
    // Fallback : si Google Maps ne se charge pas dans les 10 secondes
    setTimeout(function() {
        if (!map) {
            console.warn("⚠️ Google Maps ne s'est pas chargé, tentative de fallback...");
            // Essayer d'appeler initMap manuellement
            if (typeof google !== 'undefined' && google.maps) {
                window.initMap();
            } else {
                console.error("❌ Google Maps n'est pas disponible");
                document.getElementById("map").innerHTML = `
                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; background: #f8f9fa; color: #6c757d;">
                        <div style="text-align: center;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                            <h4>Erreur de chargement de Google Maps</h4>
                            <p>Vérifiez votre connexion internet et votre clé API Google Maps.</p>
                            <button onclick="location.reload()" class="btn btn-primary mt-3">Réessayer</button>
                        </div>
                    </div>
                `;
            }
        }
    }, 10000);
    
    console.log("✅ Page de localisation des chauffeurs chargée");
});

// Styles CSS pour les indicateurs de statut
const style = document.createElement('style');
style.textContent = `
    .driver-status-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
    }
    .driver-status-indicator[data-status="disponible"] {
        background-color: #28a745;
    }
    .driver-status-indicator[data-status="en_course"] {
        background-color: #007bff;
    }
    .driver-status-indicator[data-status="en_attente"] {
        background-color: #ffc107;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    .real-time-indicator {
        transition: all 0.3s ease;
    }
    
    .driver-marker-animation {
        animation: bounce 1s ease-in-out;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
        60% {
            transform: translateY(-5px);
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection
