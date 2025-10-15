@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Courses</title>
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
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .status-badge {
            top: -10px;
            right: -10px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="container-fluid px-3 py-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="mb-3 mb-md-0">
                    <h1 class="h3 mb-1 text-primary fw-bold">
                        <i class="fas fa-map-marked-alt me-2"></i>
                        Localisation des Chauffeurs
                    </h1>
                    <p class="text-muted mb-0">Suivi en temps réel de la position de tous les chauffeurs</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button id="refreshBtn" class="btn btn-outline-primary">
                        <i class="fas fa-sync-alt me-1"></i>
                        Actualiser
                    </button>
                    <div class="badge bg-info fs-6 px-3 py-2">
                        <i class="fas fa-circle text-success me-1" id="statusIndicator"></i>
                        <span id="updateStatus">En temps réel</span>
                    </div>
                    <div class="badge bg-success fs-6 px-3 py-2">
                        <i class="fas fa-clock me-1"></i>
                        <span id="lastUpdate">--:--</span>
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
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-users me-2"></i>
                            Chauffeurs
                        </h5>
                        <span class="badge bg-primary" id="driverCount">{{ $chauffeurs->count() }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush" id="driversList">
                        @foreach($chauffeurs as $chauffeur)
                            <div class="list-group-item border-0 px-0 driver-item" data-driver-id="{{ $chauffeur['id'] }}">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="driver-status-indicator me-3" data-status="{{ $chauffeur['statut'] }}"></div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">{{ $chauffeur['nom'] }}</h6>
                                        <p class="mb-1 text-muted small">
                                            <i class="fas fa-car me-1"></i>
                                            {{ $chauffeur['voiture'] ?? 'Aucune voiture' }}
                                        </p>
                                    </div>
                                    <button class="btn btn-outline-primary btn-sm" onclick="centerOnDriver({{ $chauffeur['id'] }})">
                                        <i class="fas fa-map-pin"></i>
                                    </button>
                                </div>
                                
                                <div class="row g-2 mb-2">
                                    <div class="col-4">
                                        <div class="text-center p-2 bg-light rounded">
                                            <div class="fw-bold text-primary">{{ $chauffeur['stats']['courses_aujourd_hui'] }}</div>
                                            <small class="text-muted">Aujourd'hui</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center p-2 bg-light rounded">
                                            <div class="fw-bold text-success">{{ $chauffeur['stats']['courses_total'] }}</div>
                                            <small class="text-muted">Total</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center p-2 bg-light rounded">
                                            <div class="fw-bold text-warning">{{ $chauffeur['stats']['courses_en_cours'] }}</div>
                                            <small class="text-muted">En cours</small>
                                        </div>
                                    </div>
                                </div>

                                @if($chauffeur['derniere_course'])
                                    <div class="mt-2 p-2 bg-light rounded">
                                        <small class="text-muted">
                                            <i class="fas fa-route me-1"></i>
                                            Course {{ $chauffeur['derniere_course']->statut_francais }}
                                            @if($chauffeur['derniere_course']->reservation->client)
                                                avec {{ $chauffeur['derniere_course']->reservation->client->first_name }}
                                            @endif
                                        </small>
                                    </div>
                                @endif

                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        Dernière MAJ: {{ $chauffeur['localisation']['derniere_maj'] ? \Carbon\Carbon::parse($chauffeur['localisation']['derniere_maj'])->diffForHumans() : 'Jamais' }}
                                    </small>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr class="my-2">
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques générales -->
    <div class="row g-3 mt-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="bg-success bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-user-check text-white fs-4"></i>
                    </div>
                    <h4 class="fw-bold text-success" id="availableDrivers">{{ $chauffeurs->where('statut', 'disponible')->count() }}</h4>
                    <p class="text-muted mb-0">Disponibles</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="bg-primary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-route text-white fs-4"></i>
                    </div>
                    <h4 class="fw-bold text-primary" id="activeDrivers">{{ $chauffeurs->where('statut', 'en_course')->count() }}</h4>
                    <p class="text-muted mb-0">En Course</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="bg-warning bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-clock text-white fs-4"></i>
                    </div>
                    <h4 class="fw-bold text-warning" id="waitingDrivers">{{ $chauffeurs->where('statut', 'en_attente')->count() }}</h4>
                    <p class="text-muted mb-0">En Attente</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="bg-info bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-users text-white fs-4"></i>
                    </div>
                    <h4 class="fw-bold text-info">{{ $chauffeurs->count() }}</h4>
                    <p class="text-muted mb-0">Total</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=geometry"></script>

<script>
let map;
let drivers = @json($chauffeurs);
let driverMarkers = {};

// Initialisation de la carte
function initMap() {
    // Position par défaut (Dakar)
    const defaultPosition = { lat: 14.6928, lng: -17.4467 };
    
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
    
    // Actualiser les positions chaque seconde
    setInterval(updateDriverLocations, 1000);
    
    // Mettre à jour l'heure de dernière actualisation
    updateLastUpdateTime();
}

// Ajouter les marqueurs des chauffeurs
function addDriverMarkers() {
    drivers.forEach(driver => {
        const marker = new google.maps.Marker({
            position: { lat: driver.localisation.lat, lng: driver.localisation.lng },
            map: map,
            title: driver.nom,
            icon: getDriverIcon(driver.statut),
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
    });
}

// Obtenir l'icône selon le statut
function getDriverIcon(status) {
    const icons = {
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
    
    return icons[status] || icons['disponible'];
}

// Créer le contenu de l'InfoWindow
function createInfoWindowContent(driver) {
    return `
        <div style="padding: 10px; min-width: 200px;">
            <h6 class="fw-bold mb-2">${driver.nom}</h6>
            <p class="mb-1"><strong>Statut:</strong> ${getStatusText(driver.statut)}</p>
            <p class="mb-1"><strong>Voiture:</strong> ${driver.voiture || 'N/A'}</p>
            <p class="mb-1"><strong>Téléphone:</strong> ${driver.telephone || 'N/A'}</p>
            <p class="mb-0"><strong>Dernière MAJ:</strong> ${driver.localisation.derniere_maj ? new Date(driver.localisation.derniere_maj).toLocaleString() : 'Jamais'}</p>
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
            if (driverMarkers[driver.id]) {
                const marker = driverMarkers[driver.id];
                const newPosition = { lat: driver.position.lat, lng: driver.position.lng };
                
                // Vérifier si la position a changé
                const currentPosition = marker.getPosition();
                if (!currentPosition || 
                    Math.abs(currentPosition.lat() - newPosition.lat) > 0.00001 || 
                    Math.abs(currentPosition.lng() - newPosition.lng) > 0.00001) {
                    
                    marker.setPosition(newPosition);
                    marker.setIcon(getDriverIcon(driver.statut));
                    
                    // Animation pour les mouvements
                    marker.setAnimation(google.maps.Animation.BOUNCE);
                    setTimeout(() => marker.setAnimation(null), 1000);
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
    initMap();
    
    // Initialiser l'indicateur de statut
    updateStatusIndicator('success');
    
    // Démarrer la mise à jour automatique
    updateDriverLocations();
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
