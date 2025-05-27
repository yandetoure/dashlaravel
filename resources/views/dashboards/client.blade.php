<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
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
                                Client Standard
                            </div>
                            <div class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">
                                0 points fidélité
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
                        <h3 class="text-3xl font-bold text-gray-900 mt-1">0</h3>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-check-circle mr-1"></i>
                            0 confirmées
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
                        <h3 class="text-3xl font-bold text-gray-900 mt-1">0</h3>
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
                        <h3 class="text-3xl font-bold text-gray-900 mt-1">0</h3>
                        <p class="text-xs text-orange-600 mt-1">
                            100 pts pour être Fidèle
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
                        <h3 class="text-3xl font-bold text-gray-900 mt-1">0</h3>
                        <p class="text-xs text-green-600 mt-1">
                            Aucune facture en attente
                        </p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full">
                        <i class="fas fa-receipt text-2xl text-red-600"></i>
                    </div>
                </div>
                            </div>
                        </div>

        <!-- Actions rapides et informations -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Actions rapides -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                    Actions Rapides
                </h3>
                <div class="space-y-3">
                    <a href="/reservations/create" class="w-full bg-red-600 hover:bg-red-700 text-white py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-plus mr-2"></i>
                        Nouvelle Réservation
                    </a>
                    <a href="/reservations" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-list mr-2"></i>
                        Mes Réservations
                    </a>
                    <a href="/invoices" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-file-invoice mr-2"></i>
                        Mes Factures
                    </a>
                    <button onclick="contactWhatsApp()" class="w-full bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                        <i class="fab fa-whatsapp mr-2"></i>
                        Contacter un Agent
                    </button>
                        </div>
                    </div>

                    <!-- Prochaine réservation -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-clock text-red-600 mr-2"></i>
                        Prochaine Réservation
                    </h3>
                </div>
                <div class="text-center py-8">
                    <i class="fas fa-calendar-plus text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-500 mb-4">Aucune réservation prévue</p>
                    <a href="/reservations/create" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Créer une réservation
                    </a>
                </div>
            </div>
                            </div>

        <!-- Mes réservations récentes -->
        <div class="bg-white rounded-lg shadow-md mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-history text-blue-500 mr-2"></i>
                        Mes Réservations Récentes
                    </h3>
                    <a href="/reservations" class="text-red-600 hover:text-red-800 text-sm font-medium">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                            </div>
                            </div>
            <div class="p-6">
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-500 mb-4">Aucune réservation pour le moment</p>
                    <p class="text-sm text-gray-400">Vos réservations apparaîtront ici une fois créées</p>
                                </div>
                            </div>
                        </div>
                        
        <!-- Mes factures -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Factures récentes -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-file-invoice text-green-500 mr-2"></i>
                            Mes Factures
                        </h3>
                        <a href="/invoices" class="text-red-600 hover:text-red-800 text-sm font-medium">
                            Voir tout <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="text-center py-8">
                        <i class="fas fa-receipt text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500 mb-4">Aucune facture disponible</p>
                        <p class="text-sm text-gray-400">Vos factures apparaîtront ici après vos réservations</p>
                    </div>
                </div>
            </div>

            <!-- Programme de fidélité -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-gift text-yellow-500 mr-2"></i>
                        Programme de Fidélité
                    </h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Progression vers Fidèle</span>
                            <span>0/100 points</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-star text-yellow-500 mr-2"></i>
                                <span class="text-sm font-medium">Standard</span>
                            </div>
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Actuel</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg opacity-50">
                            <div class="flex items-center">
                                <i class="fas fa-star text-yellow-500 mr-2"></i>
                                <span class="text-sm font-medium">Fidèle</span>
                            </div>
                            <span class="text-xs text-gray-500">100 pts</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg opacity-50">
                            <div class="flex items-center">
                                <i class="fas fa-crown text-yellow-500 mr-2"></i>
                                <span class="text-sm font-medium">VIP</span>
                            </div>
                            <span class="text-xs text-gray-500">300 pts</span>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                        <p class="text-xs text-blue-700">
                            <i class="fas fa-info-circle mr-1"></i>
                            Gagnez des points à chaque réservation et débloquez des avantages exclusifs !
                        </p>
                    </div>
                        </div>
                    </div>
                </div>

        <!-- Support et aide -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-headset text-purple-500 mr-2"></i>
                    Support et Aide
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer" onclick="contactWhatsApp()">
                        <i class="fab fa-whatsapp text-green-500 text-2xl mb-2"></i>
                        <h4 class="font-medium text-gray-900">WhatsApp</h4>
                        <p class="text-sm text-gray-600">+221 77 705 69 69</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer" onclick="contactPhone()">
                        <i class="fas fa-phone text-blue-500 text-2xl mb-2"></i>
                        <h4 class="font-medium text-gray-900">Téléphone</h4>
                        <p class="text-sm text-gray-600">+221 77 705 67 67</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer" onclick="contactEmail()">
                        <i class="fas fa-envelope text-red-500 text-2xl mb-2"></i>
                        <h4 class="font-medium text-gray-900">Email</h4>
                        <p class="text-sm text-gray-600">221cproservices@gmail.com</p>
                    </div>
                </div>
                
                <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-lightbulb text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-yellow-800">Conseil du jour</h4>
                            <p class="text-sm text-yellow-700 mt-1">
                                Réservez à l'avance pour garantir votre transport et bénéficier des meilleurs tarifs !
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

<!-- Scripts pour les fonctionnalités dynamiques -->
    <script>
// Fonctions de contact
function contactWhatsApp() {
    const message = "Bonjour, je suis un client CPRO et j'aimerais obtenir de l'aide.";
    window.open(`https://wa.me/221777056969?text=${encodeURIComponent(message)}`, '_blank');
}

function contactPhone() {
    window.open('tel:+221777056767', '_self');
}

function contactEmail() {
    window.open('mailto:221cproservices@gmail.com?subject=Demande d\'assistance client', '_self');
}

// Animation des cartes au survol
document.querySelectorAll('.card-hover').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});

// Actualisation automatique des données toutes les 5 minutes
setInterval(function() {
    // Ici vous pouvez ajouter une requête AJAX pour actualiser les données
    console.log('Actualisation des données...');
}, 300000);

// Notification de bienvenue
document.addEventListener('DOMContentLoaded', function() {
    // Afficher une notification de bienvenue pour les nouveaux utilisateurs
    const isNewUser = localStorage.getItem('isNewUser');
    if (!isNewUser) {
        showWelcomeNotification();
        localStorage.setItem('isNewUser', 'false');
    }
});

function showWelcomeNotification() {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 z-50 p-4 bg-green-500 text-white rounded-lg shadow-lg max-w-sm';
    notification.innerHTML = `
        <div class="flex items-start">
            <i class="fas fa-check-circle text-xl mr-3 mt-1"></i>
            <div>
                <h4 class="font-bold">Bienvenue chez CPRO !</h4>
                <p class="text-sm mt-1">Votre compte a été créé avec succès. Commencez par créer votre première réservation.</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Supprimer automatiquement après 10 secondes
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 10000);
}

// Fonction pour afficher les notifications
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        type === 'warning' ? 'bg-yellow-500' :
        'bg-blue-500'
    } text-white`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
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

/* Animation pour les boutons */
button, a {
    transition: all 0.2s ease-in-out;
}

button:hover, a:hover {
    transform: translateY(-1px);
}

/* Styles pour les notifications */
.notification {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Responsive design */
@media (max-width: 768px) {
    .grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
