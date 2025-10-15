@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 py-4">
    <!-- En-tête du dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="mb-3 mb-md-0">
                    <h1 class="h3 mb-1 text-primary fw-bold">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard Chauffeur
                    </h1>
                    <p class="text-muted mb-0">Bienvenue, {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <div class="badge bg-success fs-6 px-3 py-2">
                        <i class="fas fa-clock me-1"></i>
                        {{ now()->format('d/m/Y H:i') }}
                    </div>
                    <div class="badge bg-info fs-6 px-3 py-2">
                        <i class="fas fa-star me-1"></i>
                        {{ $stats['points'] ?? 0 }} points
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-primary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-list-alt text-white fs-4"></i>
                    </div>
                    <h3 class="fw-bold text-primary mb-1">{{ $stats['total_reservations'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Total Réservations</p>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-success bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-check-circle text-white fs-4"></i>
                    </div>
                    <h3 class="fw-bold text-success mb-1">{{ $stats['completed_reservations'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Courses Terminées</p>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-warning bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-calendar-day text-white fs-4"></i>
                    </div>
                    <h3 class="fw-bold text-warning mb-1">{{ $stats['today_reservations'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Aujourd'hui</p>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-info bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-money-bill-wave text-white fs-4"></i>
                    </div>
                    <h3 class="fw-bold text-info mb-1">{{ number_format($stats['total_earnings'] ?? 0, 0, ',', ' ') }} FCFA</h3>
                    <p class="text-muted mb-0 small">Gains Totaux</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Réservations d'aujourd'hui -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-calendar-day text-primary me-2"></i>
                            Réservations d'Aujourd'hui
                        </h5>
                        <span class="badge bg-primary">{{ $today_reservations->count() }} courses</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($today_reservations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Client</th>
                                        <th class="border-0">Heure</th>
                                        <th class="border-0">Adresse</th>
                                        <th class="border-0">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($today_reservations as $reservation)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">
                                                            @if($reservation->client)
                                                                {{ $reservation->client->first_name }} {{ $reservation->client->last_name }}
                                                            @else
                                                                {{ $reservation->first_name }} {{ $reservation->last_name }}
                                                            @endif
                                                        </h6>
                                                        <small class="text-muted">
                                                            @if($reservation->client)
                                                                {{ $reservation->client->phone_number }}
                                                            @else
                                                                {{ $reservation->phone_number }}
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-bold">{{ $reservation->heure_rammassage }}</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ Str::limit($reservation->adresse_rammassage, 30) }}</small>
                                            </td>
                                            <td>
                                                @php
                                                    $course = $reservation->course;
                                                @endphp
                                                @if($course && $course->statut === 'en_cours')
                                                    <a href="{{ route('courses.suivi', $course->id) }}" class="btn btn-success btn-sm">
                                                        <i class="fas fa-map-marked-alt me-1"></i>
                                                        Suivre
                                                    </a>
                                                @elseif($course && $course->statut === 'en_attente')
                                                    <form action="{{ route('courses.demarrer', $course->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-play me-1"></i>
                                                            Démarrer
                                                        </button>
                                                    </form>
                                                @elseif($course && $course->statut === 'terminee')
                                                    <span class="badge bg-success">Terminée</span>
                                                @else
                                                    <span class="badge bg-warning">En attente</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-calendar-times text-muted fs-2"></i>
                            </div>
                            <h5 class="text-muted">Aucune réservation aujourd'hui</h5>
                            <p class="text-muted mb-0">Vous n'avez pas de courses programmées pour aujourd'hui.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Prochaines réservations -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-clock text-info me-2"></i>
                            Prochaines Courses
                        </h5>
                        <span class="badge bg-info">{{ $upcoming_reservations->count() }}</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($upcoming_reservations->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($upcoming_reservations->take(5) as $reservation)
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold">
                                                @if($reservation->client)
                                                    {{ $reservation->client->first_name }} {{ $reservation->client->last_name }}
                                                @else
                                                    {{ $reservation->first_name }} {{ $reservation->last_name }}
                                                @endif
                                            </h6>
                                            <p class="mb-1 text-muted small">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}
                                                <i class="fas fa-clock ms-2 me-1"></i>
                                                {{ $reservation->heure_rammassage }}
                                            </p>
                                        </div>
                                        <span class="badge bg-primary">{{ $reservation->nb_personnes }} pers.</span>
                                    </div>
                                    <p class="mb-0 text-muted small">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ Str::limit($reservation->adresse_rammassage, 35) }}
                                    </p>
                                    @if($reservation->numero_vol)
                                        <p class="mb-0 text-muted small mt-1">
                                            <i class="fas fa-plane me-1"></i>
                                            Vol: {{ $reservation->numero_vol }}
                                        </p>
                                    @endif
                                </div>
                                @if(!$loop->last)
                                    <hr class="my-2">
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-calendar-plus text-muted"></i>
                            </div>
                            <h6 class="text-muted">Aucune course à venir</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row g-3 mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-bolt text-warning me-2"></i>
                        Actions Rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <a href="{{ route('reservations.chauffeur.mes') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                                <i class="fas fa-list-alt fs-3 mb-2"></i>
                                <span class="fw-bold">Mes Réservations</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('courses.index') }}" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                                <i class="fas fa-tasks fs-3 mb-2"></i>
                                <span class="fw-bold">Mes Courses</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('reservations.showCalendar') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                                <i class="fas fa-calendar-alt fs-3 mb-2"></i>
                                <span class="fw-bold">Calendrier</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('traffic.index') }}" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                                <i class="fas fa-traffic-light fs-3 mb-2"></i>
                                <span class="fw-bold">Trafic</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations du profil -->
    <div class="row g-3 mt-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-user text-primary me-2"></i>
                        Profil Chauffeur
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-lg bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-user text-white fs-3"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h5>
                            <p class="text-muted mb-0">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h4 class="fw-bold text-primary mb-1">{{ $stats['points'] ?? 0 }}</h4>
                                <small class="text-muted">Points</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h4 class="fw-bold text-success mb-1">{{ $stats['completed_reservations'] ?? 0 }}</h4>
                                <small class="text-muted">Courses</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-chart-line text-success me-2"></i>
                        Performances
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small">Courses terminées</span>
                            <span class="small fw-bold">{{ $stats['completed_reservations'] ?? 0 }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ min(($stats['completed_reservations'] ?? 0) * 5, 100) }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small">Gains ce mois</span>
                            <span class="small fw-bold">{{ number_format($stats['total_earnings'] ?? 0, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: 75%"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-1"></i>
                            Modifier le profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
}

.avatar-lg {
    width: 60px;
    height: 60px;
}

.card {
    border-radius: 15px;
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.btn {
    border-radius: 10px;
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
}

.progress {
    border-radius: 10px;
}

.badge {
    border-radius: 20px;
}

.table th {
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.list-group-item {
    border-radius: 10px;
    margin-bottom: 0.5rem;
}

.list-group-item:last-child {
    margin-bottom: 0;
}

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .btn {
        font-size: 0.875rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}

@media (max-width: 576px) {
    .h3 {
        font-size: 1.5rem;
    }
    
    .fs-4 {
        font-size: 1.25rem !important;
    }
    
    .card-body {
        padding: 0.75rem;
    }
}
</style>

<!-- Script de géolocalisation automatique pour les chauffeurs -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚗 Dashboard chauffeur chargé - Initialisation de la géolocalisation');
    
    function updateDriverLocation(position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        
        console.log('📍 Position récupérée:', lat, lng);
        
        fetch('/driver/update-location', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                lat: lat,
                lng: lng
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('✅ Position mise à jour:', data.updated ? 'Oui' : 'Non');
                
                // Afficher un indicateur visuel si c'est la première mise à jour
                if (data.updated) {
                    showLocationUpdateIndicator();
                }
            } else {
                console.error('❌ Erreur mise à jour position:', data);
            }
        })
        .catch(error => {
            console.error('❌ Erreur réseau:', error);
        });
    }
    
    function showLocationUpdateIndicator() {
        // Créer un indicateur visuel temporaire
        const indicator = document.createElement('div');
        indicator.innerHTML = '📍 Position mise à jour';
        indicator.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            z-index: 9999;
            font-size: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        `;
        document.body.appendChild(indicator);
        
        // Supprimer après 3 secondes
        setTimeout(() => {
            indicator.remove();
        }, 3000);
    }
    
    function startLocationTracking() {
        if (navigator.geolocation) {
            console.log('✅ Géolocalisation supportée');
            
            // Récupérer la position immédiatement
            navigator.geolocation.getCurrentPosition(
                updateDriverLocation,
                function(error) {
                    console.error('❌ Erreur géolocalisation:', error.message);
                    showLocationError();
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
            
            // Surveiller les changements de position
            navigator.geolocation.watchPosition(
                updateDriverLocation,
                function(error) {
                    console.error('❌ Erreur surveillance position:', error.message);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 5000 // Mise à jour toutes les 5 secondes
                }
            );
            
            console.log('✅ Géolocalisation activée - mise à jour toutes les 5 secondes');
        } else {
            console.error('❌ Géolocalisation non supportée par ce navigateur');
            showLocationError();
        }
    }
    
    function showLocationError() {
        const errorDiv = document.createElement('div');
        errorDiv.innerHTML = '⚠️ Géolocalisation non disponible';
        errorDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #dc3545;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            z-index: 9999;
            font-size: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        `;
        document.body.appendChild(errorDiv);
        
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    }
    
    // Démarrer le suivi de position
    startLocationTracking();
    
    // Ajouter un bouton de test manuel
    const testButton = document.createElement('button');
    testButton.innerHTML = '🔄 Tester Géolocalisation';
    testButton.className = 'btn btn-outline-primary btn-sm';
    testButton.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
    `;
    testButton.onclick = function() {
        console.log('🔄 Test manuel de géolocalisation');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(updateDriverLocation, function(error) {
                console.error('❌ Erreur test manuel:', error.message);
            });
        }
    };
    document.body.appendChild(testButton);
});
</script>
@endsection
