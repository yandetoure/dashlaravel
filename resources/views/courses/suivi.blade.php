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
<div class="container-fluid px-0">
    <!-- En-tête avec informations du chauffeur -->
    <div class="bg-white shadow-sm border-bottom sticky-top" style="z-index: 1000;">
        <div class="container py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle me-3">
                            <span class="material-icons text-white">person</span>
                        </div>
                        <div>
                            <h4 class="mb-0 text-primary">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h4>
                            <small class="text-muted">Chauffeur - Course en cours</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="d-flex justify-content-end align-items-center">
                        <span class="badge bg-success fs-6 me-3">
                            <i class="fas fa-clock me-1"></i>
                            <span id="course-timer">00:00:00</span>
                        </span>
                        <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-0 h-100">
        <!-- Carte Google Maps -->
        <div class="col-lg-8">
            <div id="map" style="height: calc(100vh - 120px);"></div>
        </div>

        <!-- Panel d'informations client -->
        <div class="col-lg-4">
            <div class="h-100 bg-light p-4">
                <!-- Informations client -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            Informations Client
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-4">
                                <strong>Nom:</strong>
                            </div>
                            <div class="col-8">
                                @if($course->reservation && $course->reservation->client)
                                    {{ $course->reservation->client->first_name }} {{ $course->reservation->client->last_name }}
                                @else
                                    {{ $course->reservation->first_name ?? 'N/A' }} {{ $course->reservation->last_name ?? 'N/A' }}
                                @endif
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-4">
                                <strong>Email:</strong>
                            </div>
                            <div class="col-8">
                                @if($course->reservation && $course->reservation->client)
                                    {{ $course->reservation->client->email }}
                                @else
                                    {{ $course->reservation->email ?? 'N/A' }}
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <strong>Téléphone:</strong>
                            </div>
                            <div class="col-8">
                                @if($course->reservation && $course->reservation->client)
                                    <a href="tel:{{ $course->reservation->client->phone_number }}" class="text-primary">
                                        {{ $course->reservation->client->phone_number ?? 'N/A' }}
                                    </a>
                                @else
                                    <a href="tel:{{ $course->reservation->phone_number }}" class="text-primary">
                                        {{ $course->reservation->phone_number ?? 'N/A' }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <strong>Adresse:</strong>
                            </div>
                            <div class="col-8">
                                {{ $course->reservation->adresse_rammassage }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <strong>Heure:</strong>
                            </div>
                            <div class="col-8">
                                {{ \Carbon\Carbon::parse($course->reservation->date)->format('d/m/Y') }} à {{ $course->reservation->heure_rammassage }}
                            </div>
                        </div>

                        @if($course->reservation->numero_vol)
                        <div class="row mb-3">
                            <div class="col-4">
                                <strong>Vol:</strong>
                            </div>
                            <div class="col-8">
                                {{ $course->reservation->numero_vol }}
                                @if($course->reservation->heure_vol)
                                    - {{ $course->reservation->heure_vol }}
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-4">
                                <strong>Personnes:</strong>
                            </div>
                            <div class="col-8">
                                {{ $course->reservation->nb_personnes }} personne(s), {{ $course->reservation->nb_valises }} valise(s)
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-cogs me-2"></i>
                            Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#terminerModal">
                                <i class="fas fa-check-circle me-2"></i>
                                Marquer comme Terminée
                            </button>
                            
                            <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#annulerModal">
                                <i class="fas fa-times-circle me-2"></i>
                                Annuler la Course
                            </button>

                            <a href="tel:@if($course->reservation && $course->reservation->client){{ $course->reservation->client->phone_number }}@else{{ $course->reservation->phone_number }}@endif" class="btn btn-primary btn-lg">
                                <i class="fas fa-phone me-2"></i>
                                Appeler le Client
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Informations de la course -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Détails de la Course
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-5"><strong>Début:</strong></div>
                            <div class="col-7">{{ $course->debut_course->format('H:i:s') }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5"><strong>Tarif:</strong></div>
                            <div class="col-7 text-success fw-bold">{{ number_format($course->reservation->tarif, 0, ',', ' ') }} FCFA</div>
                        </div>
                        <div class="row">
                            <div class="col-5"><strong>Distance:</strong></div>
                            <div class="col-7"><span id="distance">Calcul en cours...</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Terminer Course -->
<div class="modal fade" id="terminerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>
                    Terminer la Course
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir marquer cette course comme terminée ?</p>
                <p class="text-muted">Cette action ne peut pas être annulée.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('courses.terminer', $course->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>
                        Terminer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Annuler Course -->
<div class="modal fade" id="annulerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle me-2"></i>
                    Annuler la Course
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir annuler cette course ?</p>
                <p class="text-muted">Cette action ne peut pas être annulée.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('courses.annuler', $course->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i>
                        Annuler
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

#map {
    border-radius: 0;
}

.card {
    border-radius: 10px;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
}

.badge {
    border-radius: 20px;
}

.material-icons {
    font-size: 24px;
}
</style>

<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=geometry"></script>

<script>
let map;
let driverMarker;
let clientMarker;
let directionsService;
let directionsRenderer;
let courseStartTime;
let timerInterval;

// Initialisation de la carte
function initMap() {
    // Position par défaut (Dakar)
    const defaultPosition = { lat: 14.6928, lng: -17.4467 };
    
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 13,
        center: defaultPosition,
        mapTypeControl: false,
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

    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({
        suppressMarkers: true,
        polylineOptions: {
            strokeColor: '#4285F4',
            strokeWeight: 4,
            strokeOpacity: 0.8
        }
    });
    directionsRenderer.setMap(map);

    // Obtenir la position actuelle du chauffeur
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(
            function(position) {
                const driverPosition = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                if (driverMarker) {
                    driverMarker.setPosition(driverPosition);
                } else {
                    driverMarker = new google.maps.Marker({
                        position: driverPosition,
                        map: map,
                        title: 'Votre position',
                        icon: {
                            url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                            scaledSize: new google.maps.Size(40, 40)
                        },
                        animation: google.maps.Animation.DROP
                    });
                }

                // Centrer la carte sur le chauffeur
                map.setCenter(driverPosition);

                // Calculer la distance vers le client
                calculateDistanceToClient(driverPosition);
            },
            function(error) {
                console.error('Erreur de géolocalisation:', error);
                // Utiliser la position par défaut
                driverMarker = new google.maps.Marker({
                    position: defaultPosition,
                    map: map,
                    title: 'Position par défaut',
                    icon: {
                        url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                        scaledSize: new google.maps.Size(40, 40)
                    }
                });
            },
            {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 30000
            }
        );
    }

    // Marquer la position du client (adresse de ramassage)
    const clientAddress = '{{ $course->reservation->adresse_rammassage }}';
    geocodeAddress(clientAddress);
}

// Géocoder l'adresse du client
function geocodeAddress(address) {
    const geocoder = new google.maps.Geocoder();
    geocoder.geocode({ address: address }, function(results, status) {
        if (status === 'OK' && results[0]) {
            const clientPosition = results[0].geometry.location;
            
            clientMarker = new google.maps.Marker({
                position: clientPosition,
                map: map,
                title: 'Position du client',
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
                    scaledSize: new google.maps.Size(40, 40)
                },
                animation: google.maps.Animation.DROP
            });

            // Afficher les directions
            calculateRoute(clientPosition);
        } else {
            console.error('Erreur de géocodage:', status);
        }
    });
}

// Calculer l'itinéraire
function calculateRoute(clientPosition) {
    if (driverMarker) {
        const request = {
            origin: driverMarker.getPosition(),
            destination: clientPosition,
            travelMode: google.maps.TravelMode.DRIVING,
            unitSystem: google.maps.UnitSystem.METRIC,
            avoidHighways: false,
            avoidTolls: false
        };

        directionsService.route(request, function(result, status) {
            if (status === 'OK') {
                directionsRenderer.setDirections(result);
                
                // Afficher la distance
                const route = result.routes[0];
                const leg = route.legs[0];
                const distance = leg.distance.text;
                document.getElementById('distance').textContent = distance;
            }
        });
    }
}

// Calculer la distance vers le client
function calculateDistanceToClient(driverPosition) {
    if (clientMarker) {
        const clientPosition = clientMarker.getPosition();
        const distance = google.maps.geometry.spherical.computeDistanceBetween(
            new google.maps.LatLng(driverPosition.lat, driverPosition.lng),
            clientPosition
        );
        
        const distanceKm = (distance / 1000).toFixed(2);
        document.getElementById('distance').textContent = distanceKm + ' km';
    }
}

// Timer de la course
function startCourseTimer() {
    courseStartTime = new Date('{{ $course->debut_course }}');
    timerInterval = setInterval(updateTimer, 1000);
}

function updateTimer() {
    const now = new Date();
    const diff = now - courseStartTime;
    
    const hours = Math.floor(diff / 3600000);
    const minutes = Math.floor((diff % 3600000) / 60000);
    const seconds = Math.floor((diff % 60000) / 1000);
    
    const timerString = 
        hours.toString().padStart(2, '0') + ':' +
        minutes.toString().padStart(2, '0') + ':' +
        seconds.toString().padStart(2, '0');
    
    document.getElementById('course-timer').textContent = timerString;
}

// Fonction pour envoyer la position au serveur
function updateDriverLocationOnServer(position) {
    fetch('{{ route("driver.update-location") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            lat: position.lat,
            lng: position.lng
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Position mise à jour sur le serveur');
        }
    })
    .catch(error => {
        console.error('Erreur lors de la mise à jour de la position:', error);
    });
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    startCourseTimer();
    
    // Actualiser la position toutes les 5 secondes
    setInterval(function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const driverPosition = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                
                if (driverMarker) {
                    driverMarker.setPosition(driverPosition);
                    calculateDistanceToClient(driverPosition);
                }
                
                // Envoyer la position au serveur
                updateDriverLocationOnServer(driverPosition);
            });
        }
    }, 5000);
});
</script>
@endsection
