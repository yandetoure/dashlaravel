@extends('layouts.traffic')

@section('title', 'Alertes Trafic - Sénégal')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50">
    <!-- Header avec animation -->
    <div class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <div class="flex items-center justify-center space-x-3 mb-4">
                    <div class="text-4xl animate-pulse">🚦</div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
                        Alertes Trafic - Sénégal
                    </h1>
                    <div class="text-4xl animate-pulse">🚦</div>
                </div>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Surveillance en temps réel des incidents de circulation pour optimiser vos trajets
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistiques avec animations -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Incidents Critiques</p>
                        <p class="text-2xl font-bold text-gray-900" id="critical-count">{{ $incidents->where('severity', 'critical')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-full">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Incidents Majeurs</p>
                        <p class="text-2xl font-bold text-gray-900" id="major-count">{{ $incidents->where('severity', 'major')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Incidents Mineurs</p>
                        <p class="text-2xl font-bold text-gray-900" id="minor-count">{{ $incidents->where('severity', 'minor')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Incidents</p>
                        <p class="text-2xl font-bold text-gray-900" id="total-count">{{ $incidents->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contrôles avec style amélioré -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8 border border-gray-100">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <button id="refresh-btn" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg flex items-center space-x-2 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span class="font-medium">Actualiser</span>
                    </button>

                    <div class="flex items-center space-x-3">
                        <label class="text-sm font-medium text-gray-700">Filtrer par gravité:</label>
                        <select id="severity-filter" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <option value="">Tous les incidents</option>
                            <option value="critical">Critiques</option>
                            <option value="major">Majeurs</option>
                            <option value="minor">Mineurs</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="text-sm text-gray-500">
                        Dernière mise à jour: <span id="last-update" class="font-medium text-gray-700">{{ now()->format('H:i') }}</span>
                    </div>
                    <div id="update-indicator" class="hidden items-center space-x-2 text-blue-600">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-xs font-medium">Mise à jour...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte avec style amélioré -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8 border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                    </svg>
                    Carte Interactive des Incidents
                </h3>
            </div>
            <div id="map" class="w-full h-96"></div>
        </div>

        <!-- Liste des incidents avec style amélioré -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Liste des Incidents Actifs
                </h3>
            </div>
            <div class="divide-y divide-gray-100" id="incidents-list">
                @forelse($incidents as $incident)
                <div class="px-6 py-4 hover:bg-gray-50 transition-all duration-200 transform hover:scale-[1.02]">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4">
                            <div class="text-3xl transform hover:scale-110 transition-transform duration-200">{{ $incident->type_icon }}</div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <span class="text-sm font-semibold text-gray-900">{{ $incident->description }}</span>
                                    <span class="px-3 py-1 text-xs font-bold rounded-full
                                        @if($incident->severity === 'critical') bg-red-100 text-red-800 border border-red-200
                                        @elseif($incident->severity === 'major') bg-orange-100 text-orange-800 border border-orange-200
                                        @else bg-yellow-100 text-yellow-800 border border-yellow-200
                                        @endif">
                                        {{ ucfirst($incident->severity) }}
                                    </span>
                                </div>
                                @if($incident->road_name)
                                <p class="text-sm text-gray-600 mb-1">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $incident->road_name }}
                                </p>
                                @endif
                                <p class="text-xs text-gray-500 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $incident->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <button onclick="centerOnIncident({{ $incident->latitude }}, {{ $incident->longitude }})"
                                class="bg-blue-50 hover:bg-blue-100 text-blue-700 hover:text-blue-800 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105">
                            Voir sur la carte
                        </button>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-gray-500">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-lg font-medium text-gray-900 mb-2">Aucun incident actuellement</p>
                    <p class="text-sm">La circulation est fluide dans votre zone</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- TomTom Maps CSS et JS -->
<link rel="stylesheet" href="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.17.0/maps/maps.css"/>
<script src="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.17.0/maps/maps-web.min.js"></script>

<script>
let map;
let markers = [];
let allIncidents = @json($incidents);

// Initialiser la carte
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si la clé API est disponible
    const apiKey = '{{ env("TOMTOM_API_KEY") }}';
    if (!apiKey) {
        alert('Clé API TomTom manquante. Veuillez configurer TOMTOM_API_KEY dans votre fichier .env');
        return;
    }

    // Initialiser la carte TomTom
    map = tt.map({
        key: apiKey,
        container: 'map',
        center: [-17.44, 14.69], // Dakar
        zoom: 12,
        style: 'tomtom://vector/1/basic-main'
    });

    // Ajouter les contrôles
    map.addControl(new tt.NavigationControl());
    map.addControl(new tt.FullscreenControl());

    // Ajouter les marqueurs des incidents
    addIncidentMarkers(allIncidents);

    // Gestionnaire pour le bouton de rafraîchissement
    document.getElementById('refresh-btn').addEventListener('click', manualRefreshIncidents);

    // Gestionnaire pour le filtre de gravité
    document.getElementById('severity-filter').addEventListener('change', filterIncidents);

    // Rafraîchissement automatique toutes les 30 secondes
    setInterval(fetchIncidents, 30000);

    // Premier rafraîchissement après 5 secondes
    setTimeout(() => fetchIncidents(), 5000);
});

// Rafraîchissement manuel
function manualRefreshIncidents() {
    fetchIncidents(true);
}

// Récupérer les incidents dynamiquement
function fetchIncidents(showLoader = false) {
    const btn = document.getElementById('refresh-btn');
    if (showLoader) {
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Actualisation...</span>';
    }

    // Afficher un indicateur de mise à jour
    showUpdateIndicator();

    // D'abord récupérer les nouvelles données TomTom
    fetch('/traffic/fetch')
        .then(response => response.json())
        .then(data => {
            console.log('Nouvelles données récupérées:', data);
            // Puis récupérer les incidents mis à jour
            return fetch('/traffic/api');
        })
        .then(response => response.json())
        .then(data => {
            const previousCount = allIncidents.length;
            allIncidents = data;

            // Vérifier s'il y a de nouveaux incidents
            if (data.length > previousCount) {
                showNewIncidentNotification(data.length - previousCount);
            }

            updateUI();
            console.log('UI mise à jour avec', data.length, 'incidents');

            // Mettre à jour l'heure de dernière mise à jour
            document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
        })
        .catch(error => {
            console.error('Erreur:', error);
            showErrorNotification();
        })
        .finally(() => {
            if (showLoader) {
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg><span class="font-medium">Actualiser</span>';
            }
            hideUpdateIndicator();
        });
}

// Afficher un indicateur de mise à jour
function showUpdateIndicator() {
    const indicator = document.getElementById('update-indicator');
    if (indicator) {
        indicator.style.display = 'flex';
    }
}

// Masquer l'indicateur de mise à jour
function hideUpdateIndicator() {
    const indicator = document.getElementById('update-indicator');
    if (indicator) {
        indicator.style.display = 'none';
    }
}

// Notification pour nouveaux incidents
function showNewIncidentNotification(count) {
    // Créer une notification toast
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full';
    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <span class="text-xl">🚦</span>
            <span>${count} nouveau(x) incident(s) détecté(s)</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Animation d'entrée
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Supprimer après 3 secondes
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);

    // Son de notification (optionnel)
    if (typeof Audio !== 'undefined') {
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWT');
        audio.play().catch(() => {}); // Ignorer les erreurs de lecture audio
    }
}

// Notification d'erreur
function showErrorNotification() {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full';
    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <span class="text-xl">⚠️</span>
            <span>Erreur lors de la mise à jour</span>
        </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Mettre à jour la carte et la liste
function updateUI() {
    const severity = document.getElementById('severity-filter').value;
    const filteredIncidents = severity ? allIncidents.filter(i => i.severity === severity) : allIncidents;
    addIncidentMarkers(filteredIncidents);
    updateCounts(filteredIncidents);
    updateIncidentList(filteredIncidents);
}

// Ajouter les marqueurs des incidents
function addIncidentMarkers(incidents) {
    // Supprimer les marqueurs existants
    markers.forEach(marker => marker.remove());
    markers = [];

    incidents.forEach(incident => {
        const marker = new tt.Marker({
            color: getSeverityColor(incident.severity)
        })
        .setLngLat([incident.longitude, incident.latitude])
        .setPopup(new tt.Popup({
            offset: 30,
            closeButton: false
        }).setHTML(`
            <div class="p-2">
                <div class="flex items-center space-x-2 mb-2">
                    <span class="text-xl">${getTypeIcon(incident.type)}</span>
                    <span class="font-medium">${incident.description}</span>
                </div>
                <div class="text-sm text-gray-600">
                    <p><strong>Gravité:</strong> ${incident.severity}</p>
                    ${incident.road_name ? `<p><strong>Route:</strong> ${incident.road_name}</p>` : ''}
                    <p><strong>Heure:</strong> ${new Date(incident.created_at).toLocaleTimeString()}</p>
                </div>
            </div>
        `))
        .addTo(map);

        markers.push(marker);
    });
}

// Obtenir la couleur selon la gravité
function getSeverityColor(severity) {
    switch(severity) {
        case 'critical': return '#dc2626';
        case 'major': return '#ea580c';
        case 'minor': return '#ca8a04';
        default: return '#6b7280';
    }
}

// Obtenir l'icône selon le type
function getTypeIcon(type) {
    const icons = {
        'accident': '🚗',
        'construction': '🚧',
        'congestion': '🚦',
        'weather': '🌧️',
        'road_closed': '🚫',
        'other': '⚠️'
    };
    return icons[type] || '⚠️';
}

// Filtrer les incidents
function filterIncidents() {
    updateUI();
}

// Mettre à jour les compteurs
function updateCounts(incidents) {
    document.getElementById('critical-count').textContent = incidents.filter(i => i.severity === 'critical').length;
    document.getElementById('major-count').textContent = incidents.filter(i => i.severity === 'major').length;
    document.getElementById('minor-count').textContent = incidents.filter(i => i.severity === 'minor').length;
    document.getElementById('total-count').textContent = incidents.length;
}

// Mettre à jour la liste des incidents
function updateIncidentList(incidents) {
    const list = document.getElementById('incidents-list');
    if (!list) return;
    let html = '';
    if (incidents.length === 0) {
        html = `<div class="px-6 py-8 text-center text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="mt-2">Aucun incident de trafic actuellement</p>
        </div>`;
    } else {
        html = incidents.map(incident => `
            <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-3">
                        <div class="text-2xl">${getTypeIcon(incident.type)}</div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <span class="text-sm font-medium text-gray-900">${incident.description}</span>
                                <span class="px-2 py-1 text-xs font-medium rounded-full ${getSeverityClass(incident.severity)}">
                                    ${capitalize(incident.severity)}
                                </span>
                            </div>
                            ${incident.road_name ? `<p class="text-sm text-gray-600">${incident.road_name}</p>` : ''}
                            <p class="text-xs text-gray-500 mt-1">
                                ${timeAgo(incident.created_at)}
                            </p>
                        </div>
                    </div>
                    <button onclick="centerOnIncident(${incident.latitude}, ${incident.longitude})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Voir sur la carte</button>
                </div>
            </div>
        `).join('');
    }
    list.innerHTML = html;
}

// Classe CSS selon la gravité
function getSeverityClass(severity) {
    if (severity === 'critical') return 'bg-red-100 text-red-800';
    if (severity === 'major') return 'bg-orange-100 text-orange-800';
    return 'bg-yellow-100 text-yellow-800';
}

// Capitaliser
function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// Affichage "il y a ..."
function timeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = Math.floor((now - date) / 1000);
    if (diff < 60) return 'il y a quelques secondes';
    if (diff < 3600) return `il y a ${Math.floor(diff/60)} min`;
    if (diff < 86400) return `il y a ${Math.floor(diff/3600)} h`;
    return date.toLocaleDateString();
}

// Centrer la carte sur un incident
function centerOnIncident(lat, lng) {
    map.flyTo({
        center: [lng, lat],
        zoom: 15,
        duration: 1000
    });
}

// Actualisation automatique de l'heure
setInterval(() => {
    document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
}, 60000);
</script>
@endsection
