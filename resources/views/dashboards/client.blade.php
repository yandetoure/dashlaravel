<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Espace Client - CPRO Transport</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#DC2626',
                        secondary: '#10B981',
                        dark: '#1F2937',
                    }
                }
            }
        }
    </script>
    <style>
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
        }
        .loyalty-badge {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

<!-- Header avec informations client -->
<div class="gradient-bg text-white p-6 mb-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-2xl font-bold mr-4">
                    {{ substr(Auth::user()->first_name ?? Auth::user()->name, 0, 1) }}{{ substr(Auth::user()->last_name ?? '', 0, 1) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-1">Bonjour {{ Auth::user()->first_name ?? Auth::user()->name }} !</h1>
                    <p class="text-red-100">Bienvenue dans votre espace client personnel</p>
                    <div class="flex items-center mt-2">
                        <div class="loyalty-badge px-3 py-1 rounded-full text-white text-sm font-semibold mr-3">
                            <i class="fas fa-star mr-1"></i>
                            Client {{ $loyalty_status }}
                        </div>
                        <div class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">
                            {{ $stats['points'] }} points fidélité
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <div class="text-red-100 text-sm">{{ Carbon\Carbon::now()->format('d/m/Y') }}</div>
                <div class="text-white font-semibold">{{ Carbon\Carbon::now()->format('H:i') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Réservations -->
        <div class="bg-white rounded-lg shadow-md p-6 card-hover transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Mes Réservations</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_reservations'] }}</h3>
                    <p class="text-xs text-green-600 mt-1">
                        <i class="fas fa-check-circle mr-1"></i>
                        {{ $stats['confirmed_reservations'] }} confirmées
                    </p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-calendar-check text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Dépensé -->
        <div class="bg-white rounded-lg shadow-md p-6 card-hover transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Dépensé</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_spent']) }}</h3>
                    <p class="text-xs text-gray-500 mt-1">FCFA</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-money-bill-wave text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Points Fidélité -->
        <div class="bg-white rounded-lg shadow-md p-6 card-hover transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Points Fidélité</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['points'] }}</h3>
                    <p class="text-xs text-orange-600 mt-1">
                        @if($stats['points'] < 100)
                            {{ 100 - $stats['points'] }} pts pour être Fidèle
                        @elseif($stats['points'] < 300)
                            {{ 300 - $stats['points'] }} pts pour être VIP
                        @else
                            Statut VIP atteint !
                        @endif
                    </p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-star text-2xl text-yellow-600"></i>
                </div>
            </div>
        </div>

        <!-- Factures Impayées -->
        <div class="bg-white rounded-lg shadow-md p-6 card-hover transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">À Payer</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['unpaid_amount']) }}</h3>
                    <p class="text-xs text-red-600 mt-1">
                        {{ count($unpaid_invoices) }} facture(s) en attente
                    </p>
                </div>
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-circle text-2xl text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Prochaine réservation et actions rapides -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Prochaine réservation -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
            @if($next_reservation)
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-clock text-primary mr-2"></i>
                        Prochaine Réservation
                    </h3>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Confirmée</span>
                </div>
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Détails du trajet</h4>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-route text-blue-500 mr-2 w-4"></i>
                                    @if($next_reservation->trip)
                                        {{ $next_reservation->trip->departure }} → {{ $next_reservation->trip->destination }}
                                    @else
                                        Trajet personnalisé
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar text-blue-500 mr-2 w-4"></i>
                                    {{ Carbon\Carbon::parse($next_reservation->date)->format('d/m/Y') }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-clock text-blue-500 mr-2 w-4"></i>
                                    {{ $next_reservation->heure_ramassage }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt text-blue-500 mr-2 w-4"></i>
                                    {{ $next_reservation->adresse_rammassage }}
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Informations</h4>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-users text-green-500 mr-2 w-4"></i>
                                    {{ $next_reservation->nb_personnes }} personne(s)
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-suitcase text-green-500 mr-2 w-4"></i>
                                    {{ $next_reservation->nb_valises }} valise(s)
                                </div>
                                @if($next_reservation->carDriver && $next_reservation->carDriver->chauffeur)
                                    <div class="flex items-center">
                                        <i class="fas fa-user-tie text-green-500 mr-2 w-4"></i>
                                        {{ $next_reservation->carDriver->chauffeur->first_name }} {{ $next_reservation->carDriver->chauffeur->last_name }}
                                    </div>
                                @endif
                                <div class="flex items-center">
                                    <i class="fas fa-money-bill text-green-500 mr-2 w-4"></i>
                                    {{ number_format($next_reservation->tarif) }} FCFA
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-blue-200">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-info-circle mr-1"></i>
                                Temps restant : {{ Carbon\Carbon::parse($next_reservation->date . ' ' . $next_reservation->heure_ramassage)->diffForHumans() }}
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('reservations.show', $next_reservation->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                    <i class="fas fa-eye mr-1"></i>Voir détails
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-calendar-plus text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune réservation prochaine</h3>
                    <p class="text-gray-600 mb-4">Planifiez votre prochain voyage dès maintenant</p>
                    <a href="{{ route('reservations.clientcreate') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Nouvelle réservation
                    </a>
                </div>
            @endif
        </div>

        <!-- Actions rapides -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h3>
            <div class="space-y-3">
                <a href="{{ route('reservations.clientcreate') }}" class="block w-full bg-primary hover:bg-red-700 text-white text-center py-3 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Nouvelle Réservation
                </a>
                <a href="{{ route('reservations.client.mes') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-3 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-list mr-2"></i>Mes Réservations
                </a>
                <a href="{{ route('invoices.index') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-3 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-file-invoice mr-2"></i>Mes Factures
                </a>
                <a href="{{ route('profile.edit') }}" class="block w-full bg-gray-600 hover:bg-gray-700 text-white text-center py-3 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-user-edit mr-2"></i>Mon Profil
                </a>
            </div>

            <!-- Programme de fidélité -->
            <div class="mt-6 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border border-yellow-200">
                <h4 class="font-semibold text-gray-900 mb-2">
                    <i class="fas fa-gift text-yellow-600 mr-2"></i>
                    Programme Fidélité
                </h4>
                <div class="text-sm text-gray-600 mb-3">
                    <div class="flex justify-between items-center mb-1">
                        <span>Progression vers {{ $loyalty_status === 'VIP' ? 'Maintien VIP' : ($loyalty_status === 'Fidèle' ? 'VIP' : 'Fidèle') }}</span>
                        <span class="font-semibold">{{ $stats['points'] }}/{{ $loyalty_status === 'VIP' ? '300+' : ($loyalty_status === 'Fidèle' ? '300' : '100') }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        @php
                            $target = $loyalty_status === 'VIP' ? 300 : ($loyalty_status === 'Fidèle' ? 300 : 100);
                            $progress = min(($stats['points'] / $target) * 100, 100);
                        @endphp
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                    </div>
                </div>
                <p class="text-xs text-gray-500">
                    Gagnez des points à chaque réservation et débloquez des avantages exclusifs !
                </p>
            </div>
        </div>
    </div>

    <!-- Graphique et réservations récentes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Graphique des réservations -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Mes Réservations ({{ Carbon\Carbon::now()->year }})</h3>
                <div class="text-sm text-gray-500">{{ $stats['total_reservations'] }} au total</div>
            </div>
            <div class="h-64">
                <canvas id="reservationsChart"></canvas>
            </div>
        </div>

        <!-- Réservations récentes -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Réservations Récentes</h3>
                <a href="{{ route('reservations.client.mes') }}" class="text-primary hover:text-red-700 text-sm font-medium">Voir tout</a>
            </div>
            <div class="space-y-4">
                @forelse($recent_reservations as $reservation)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                @if($reservation->trip)
                                    <span class="text-sm font-medium text-gray-900">{{ $reservation->trip->departure }} → {{ $reservation->trip->destination }}</span>
                                @else
                                    <span class="text-sm font-medium text-gray-900">Trajet personnalisé</span>
                                @endif
                            </div>
                            @if($reservation->status === 'Confirmée')
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Confirmée</span>
                            @elseif($reservation->status === 'En_attente')
                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">En attente</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Annulée</span>
                            @endif
                        </div>
                        <div class="text-sm text-gray-600">
                            <div class="flex items-center justify-between">
                                <span><i class="fas fa-calendar mr-1"></i>{{ Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</span>
                                <span><i class="fas fa-clock mr-1"></i>{{ $reservation->heure_ramassage }}</span>
                                <span><i class="fas fa-money-bill mr-1"></i>{{ number_format($reservation->tarif) }} FCFA</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-times text-4xl text-gray-300 mb-2"></i>
                        <p class="text-gray-500">Aucune réservation récente</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Factures impayées -->
    @if(count($unpaid_invoices) > 0)
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                Factures en Attente de Paiement
            </h3>
            <a href="{{ route('invoices.index') }}" class="text-primary hover:text-red-700 text-sm font-medium">Voir toutes les factures</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($unpaid_invoices as $invoice)
            <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900">{{ $invoice->invoice_number }}</h4>
                    <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">En attente</span>
                </div>
                <div class="text-sm text-gray-600 mb-3">
                    <div class="flex items-center justify-between">
                        <span>Montant :</span>
                        <span class="font-semibold">{{ number_format($invoice->amount) }} FCFA</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Date :</span>
                        <span>{{ Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</span>
                    </div>
                </div>
                <a href="{{ route('invoices.show', $invoice->id) }}" class="block w-full text-center bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition-colors text-sm">
                    Voir facture
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
// Données pour le graphique
const monthlyReservations = @json($monthly_reservations);

// Préparer les données pour Chart.js
const chartData = Array(12).fill(0);
monthlyReservations.forEach(item => {
    chartData[item.month - 1] = item.total;
});

// Configuration du graphique
const ctx = document.getElementById('reservationsChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
        datasets: [{
            label: 'Réservations',
            data: chartData,
            borderColor: '#DC2626',
            backgroundColor: 'rgba(220, 38, 38, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#DC2626',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                borderColor: '#DC2626',
                borderWidth: 1,
                callbacks: {
                    label: function(context) {
                        return 'Réservations: ' + context.parsed.y;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#f3f4f6'
                },
                ticks: {
                    stepSize: 1
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Animation des cartes au chargement
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card-hover');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.5s ease';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        }, index * 100);
    });
});
</script>

@endsection
