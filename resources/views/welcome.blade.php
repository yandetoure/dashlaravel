<?php declare(strict_types=1); ?>
<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dakar Transport - Services vers AIBD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1556388158-158ea5ccacbd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-position: center;
        }
        
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .active {
            color: #ef4444;
            font-weight: 600;
        }
        
        .booking-form {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .cta-banner {
            background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
        }
        
        .rating-stars {
            color: #f59e0b;
        }
        
        .entreprise-banner {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="font-sans">
    <!-- Navigation -->
    <nav class="fixed w-full bg-white shadow-md z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 100px; width: auto;" class="me-2">
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#accueil" class="text-gray-700 hover:text-red-600 transition nav-link active">Accueil</a>
                    <a href="#services" class="text-gray-700 hover:text-red-600 transition nav-link">Services</a>
                    <a href="#reservation" class="text-gray-700 hover:text-red-600 transition nav-link">Réservation</a>
                    <a href="#tarifs" class="text-gray-700 hover:text-red-600 transition nav-link">Tarifs</a>
                    <a href="#contact" class="text-gray-700 hover:text-red-600 transition nav-link">Contact</a>
                    @auth
                        <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-red-600 transition nav-link">Mon compte</a>
                    @else
                        <a href="#compte" class="text-gray-700 hover:text-red-600 transition nav-link">Mon compte</a>
                    @endauth
                </div>
                <div class="md:hidden flex items-center">
                    <button id="menu-btn" class="text-gray-700">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white py-2 px-4 shadow-lg">
            <a href="#accueil" class="block py-2 text-gray-700 hover:text-red-600 nav-link active">Accueil</a>
            <a href="#services" class="block py-2 text-gray-700 hover:text-red-600 nav-link">Services</a>
            <a href="#reservation" class="block py-2 text-gray-700 hover:text-red-600 nav-link">Réservation</a>
            <a href="#tarifs" class="block py-2 text-gray-700 hover:text-red-600 nav-link">Tarifs</a>
            <a href="#contact" class="block py-2 text-gray-700 hover:text-red-600 nav-link">Contact</a>
            <a href="#compte" class="block py-2 text-gray-700 hover:text-red-600 nav-link">Mon compte</a>
        </div>
    </nav>
<!-- Hero Section -->
<section id="accueil" class="hero pt-24 pb-32 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex items-center justify-between">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">Nous sommes les Leaders des Transferts/Shuttle Aéroportuaires</h1>
                <p class="text-xl mb-8">Service de navette, location de voiture avec chauffeur et Transferts privés vers l'Aéroport International Blaise Diagne(AIBD).</p>
                <a href="#reservation" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 inline-block">Réserver maintenant</a>
            </div>

            <div class="md:w-1/2">
                <div class="booking-form p-8 max-w-md mx-auto bg-white rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Réserver votre trajet</h3>

                    <form id="availability-form" class="text-gray-800">
                        <div class="mb-4">
                            <label class="block mb-2">Sens du trajet</label>
                            <select id="trip_id" name="trip_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                <option value="1">Dakar - AIBD</option>
                                <option value="1">AIBD - Dakar</option>
                                <option value="2">AIBD - Saly</option>
                                <option value="3">Saly - AIBD</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block mb-2">Date</label>
                            <input type="date" id="date" name="date" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>

                        <div class="mb-4">
                            <label class="block mb-2">Heure</label>
                            <input type="time" id="heure_ramassage" name="heure_ramassage" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>

                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                            Vérifier disponibilité
                        </button>
                    </form>

                    <div id="availability-result" class="mt-4 text-center font-semibold"></div>
                </div>
            </div>
        </div>
    </div>
</section>



    <!-- Publicité Réservation -->
    <section class="cta-banner py-12 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="md:flex items-center justify-between">
                <div class="md:w-2/3 mb-6 md:mb-0">
                    <h2 class="text-2xl md:text-3xl font-bold mb-4">Vous voyagez bientôt ? Réservez dès maintenant !</h2>
                    <p class="text-lg opacity-90">Évitez les mauvaises surprises et garantissez votre transport vers l'aéroport AIBD en réservant à l'avance. Nos chauffeurs professionnels vous attendront à l'heure convenue, peu importe votre lieu de départ ou destination.</p>
                </div>
                <div class="md:w-1/3 text-center md:text-right">
                    <a href="#reservation" class="inline-block bg-white text-red-600 hover:bg-gray-100 font-bold py-3 px-8 rounded-lg transition duration-300">Faire une réservation</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Nos Services</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Nous offrons des solutions de transport adaptées à tous vos besoins vers l'aéroport AIBD et au-delà</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="service-card bg-white p-8 rounded-lg shadow-md transition duration-300">
                    <div class="text-red-600 mb-4">
                        <i class="fas fa-shuttle-van text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Location mini-bus</h3>
                    <p class="text-gray-600 mb-4">Service économique de navette partagée avec des départs réguliers depuis différents points de Dakar.</p>
                    <ul class="text-gray-600 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Départs toutes les heures</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Prix abordable</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Confort assuré</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Service 2 -->
                <div class="service-card bg-white p-8 rounded-lg shadow-md transition duration-300">
                    <div class="text-red-600 mb-4">
                        <i class="fas fa-car text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Voiture avec chauffeur</h3>
                    <p class="text-gray-600 mb-4">Service privé avec chauffeur professionnel pour un trajet personnalisé selon vos horaires.</p>
                    <ul class="text-gray-600 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Disponible 24h/24</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Chauffeur expérimenté</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Flexibilité totale</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Service 3 -->
                <div class="service-card bg-white p-8 rounded-lg shadow-md transition duration-300">
                    <div class="text-red-600 mb-4">
                        <i class="fas fa-bus text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Transferts privés</h3>
                    <p class="text-gray-600 mb-4">Service haut de gamme avec véhicule premium pour un transfert en toute élégance.</p>
                    <ul class="text-gray-600 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Véhicules de luxe</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Service personnalisé</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Accueil avec panneau</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Service 4 -->
                <div class="service-card bg-white p-8 rounded-lg shadow-md transition duration-300">
                    <div class="text-red-600 mb-4">
                        <i class="fas fa-road text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Location hors Dakar</h3>
                    <p class="text-gray-600 mb-4">Service de location avec chauffeur pour des trajets longue distance en dehors de Dakar.</p>
                    <ul class="text-gray-600 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Voyages inter-régions</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Véhicules adaptés</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Trajets sur mesure</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Service 5 -->
                <div class="service-card bg-white p-8 rounded-lg shadow-md transition duration-300">
                    <div class="text-red-600 mb-4">
                        <i class="fas fa-building text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Services entreprises</h3>
                    <p class="text-gray-600 mb-4">Solutions de transport professionnelles pour les besoins de mobilité de votre entreprise.</p>
                    <ul class="text-gray-600 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Transferts d'employés</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Accompagnement clients</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Facturation centralisée</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Service 6 -->
                <div class="service-card bg-white p-8 rounded-lg shadow-md transition duration-300">
                    <div class="text-red-600 mb-4">
                        <i class="fas fa-plane text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Transferts aéroport</h3>
                    <p class="text-gray-600 mb-4">Service spécialisé pour les arrivées et départs à l'aéroport AIBD.</p>
                    <ul class="text-gray-600 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Surveillance des vols</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Assistance bagages</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Ponctualité garantie</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Entreprises -->
    <section class="entreprise-banner py-20 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="md:flex items-center">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <h2 class="text-3xl md:text-4xl font-bold mb-6">Solutions de transport pour entreprises</h2>
                    <p class="text-xl mb-8">Confiez-nous les déplacements professionnels de vos collaborateurs et bénéficiez d'un service sur mesure, fiable et sécurisé.</p>
                    
                    <div class="space-y-6 mb-8">
                        <div class="flex items-start">
                            <div class="bg-red-600 text-white rounded-full w-10 h-10 flex items-center justify-center mr-4 mt-1">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-xl">Gestion simplifiée</h4>
                                <p class="opacity-90">Une seule interface pour gérer tous les trajets de vos employés avec facturation centralisée.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="bg-red-600 text-white rounded-full w-10 h-10 flex items-center justify-center mr-4 mt-1">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-xl">Chauffeurs sélectionnés</h4>
                                <p class="opacity-90">Nos chauffeurs professionnels sont formés aux standards les plus exigeants.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="bg-red-600 text-white rounded-full w-10 h-10 flex items-center justify-center mr-4 mt-1">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-xl">Parc véhicules diversifié</h4>
                                <p class="opacity-90">Des berlines aux minibus, nous avons le véhicule adapté à chaque besoin.</p>
                            </div>
                        </div>
                    </div>
                    
                    <a href="#contact" class="inline-block bg-white text-red-600 hover:bg-gray-100 font-bold py-3 px-8 rounded-lg transition duration-300">Demander un devis</a>
                </div>
                
                <div class="md:w-1/2">
                    <div class="bg-white bg-opacity-90 p-8 rounded-lg text-gray-800 max-w-md ml-auto">
                        <h3 class="text-2xl font-bold mb-6">Avantages pour les entreprises</h3>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <div class="text-red-600 mr-3 mt-1">
                                    <i class="fas fa-percentage"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold">Tarifs préférentiels</h4>
                                    <p class="text-gray-600">Des réductions volume pour les clients professionnels.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="text-red-600 mr-3 mt-1">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold">Facturation mensuelle</h4>
                                    <p class="text-gray-600">Simplifiez votre gestion administrative.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="text-red-600 mr-3 mt-1">
                                    <i class="fas fa-headset"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold">Service dédié</h4>
                                    <p class="text-gray-600">Un interlocuteur unique pour vos réservations.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="text-red-600 mr-3 mt-1">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold">Reporting complet</h4>
                                    <p class="text-gray-600">Analysez et optimisez vos dépenses de transport.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Publicité Réservation -->
    <section class="cta-banner py-12 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="md:flex items-center justify-between">
                <div class="md:w-2/3 mb-6 md:mb-0">
                    <h2 class="text-2xl md:text-3xl font-bold mb-4">Vous voyagez bientôt ? Réservez dès maintenant !</h2>
                    <p class="text-lg opacity-90">Évitez les mauvaises surprises et garantissez votre transport vers l'aéroport AIBD en réservant à l'avance. Nos chauffeurs professionnels vous attendront à l'heure convenue, peu importe votre destination de départ.</p>
                </div>
                <div class="md:w-1/3 text-center md:text-right">
                    <a href="#reservation" class="inline-block bg-white text-red-600 hover:bg-gray-100 font-bold py-3 px-8 rounded-lg transition duration-300">Faire une réservation</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Reservation Section -->
    <section id="reservation" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="md:flex items-center">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Réservez votre transport en quelques clics</h2>
                    <p class="text-xl text-gray-600 mb-8">Notre plateforme simple et intuitive vous permet de réserver votre transport vers l'aéroport AIBD en moins de 2 minutes.</p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="bg-red-100 text-red-600 rounded-full w-10 h-10 flex items-center justify-center mr-4 mt-1">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Réservation instantanée</h4>
                                <p class="text-gray-600">Confirmation immédiate de votre réservation par email et SMS.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="bg-red-100 text-red-600 rounded-full w-10 h-10 flex items-center justify-center mr-4 mt-1">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Paiement sécurisé</h4>
                                <p class="text-gray-600">Payez en ligne de manière sécurisée ou en espèces au chauffeur.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="bg-red-100 text-red-600 rounded-full w-10 h-10 flex items-center justify-center mr-4 mt-1">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Suivi en temps réel</h4>
                                <p class="text-gray-600">Suivez votre chauffeur en temps réel grâce à notre application mobile.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="md:w-1/2">
                    <div class="bg-white p-8 rounded-lg shadow-xl max-w-md mx-auto">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Complétez votre réservation</h3>
                        <!-- Messages d'erreur -->
                    @if($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                            <strong>Erreur(s) :</strong>
                            <ul class="list-disc ml-4">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        <form action="{{ route('reservations.storeByProspect') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @csrf
                            <!-- 1. Nom complet -->
                            <div>
                                <label for="first_name" class="block text-gray-700 mb-2">Prénom</label>
                                <input type="text" name="first_name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                            </div>

                            <div>
                                <label for="last_name"class="block text-gray-700 mb-2">Nom</label>
                                <input type="text" name="last_name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                            </div>

                            <!-- 2. Email -->
                            <div>
                                <label for="email" class="block text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                            </div>
                            <!-- 3. Téléphone -->
                            <div>
                                <label for="phone_number" class="block text-gray-700 mb-2">Téléphone</label>
                                <input type="tel" name="phone" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                            </div>
                            <!-- 4. Point de départ -->
                            <div>
                                <label for="adresse_rammassage" class="block text-gray-700 mb-2">Point de départ</label>
                                <input type="text" name="adresse_rammassage" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                            </div>
                            <!-- 5. Point d'arrivée -->
                            <div>
                                <label for="nb_personnes" class="block mb-2">Nombre de passagers</label>
                                <input type="number" name="nb_personnes" id="nb_personnes" min="1" max="20" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                            </div>
                            <div>
                                <label for="date" class="block mb-2">Date</label>
                                <input type="date" name="date" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                            </div>
                            <!-- 7. Heure de ramassage -->
                            <div>
                                <label for="heure_ramassage" class="block mb-2">Heure de ramassage</label>
                                <input type="time" name="heure_ramassage" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                            </div>
                            <!-- 8. Nombre de passagers -->
                            <div>
                                <label  for="nb_valises" class="block mb-2">Nombre de valises</label>
                                <input type="number" name="nb_valises" id="nb_valises" min="1" max="20" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                            </div>

                            <!-- 9. Type de service -->
                            <div>
                                <label for="trip_id" class="block text-sm font-medium text-gray-700 mb-1">Sens du trajet <span class="text-red-500">*</span></label>
                                <select id="trip_id" name="trip_id" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                    <option value="">-- Sélectionner un trajet --</option>
                                    @foreach($trips as $trip)
                                        <option value="{{ $trip->id }}">{{ $trip->departure }} - {{ $trip->destination }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="tarif" class="block text-sm font-medium text-gray-700 mb-1">Tarif estimé</label>
                                <input type="text" id="tarif" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-gray-100" readonly>
                            </div>

                            <!-- 10. Commentaires / Instructions -->
                            {{-- <div class="md:col-span-2">
                                <label class="block mb-2">Commentaires / Instructions</label>
                                <textarea name="comments" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Détails ou instructions supplémentaires..."></textarea>
                            </div> --}}

                            <!-- Bouton -->
                            <div class="md:col-span-2">
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">Finaliser la réservation</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Compte Client -->
    <section id="compte" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="md:flex items-center">
                <div class="md:w-1/2 mb-10 md:mb-0 md:pr-10">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Application mobile" class="rounded-lg shadow-xl">
                </div>
                <div class="md:w-1/2">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Créez votre compte client</h2>
                    <p class="text-xl text-gray-600 mb-8">Accédez à toutes vos réservations passées et futures, consultez votre historique de trajets et notez vos chauffeurs.</p>
                    
                    <div class="space-y-6 mb-8">
                        <div class="flex items-start">
                            <div class="bg-red-100 text-red-600 rounded-full w-10 h-10 flex items-center justify-center mr-4 mt-1">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Gestion des réservations</h4>
                                <p class="text-gray-600">Consultez, modifiez ou annulez facilement vos réservations.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="bg-red-100 text-red-600 rounded-full w-10 h-10 flex items-center justify-center mr-4 mt-1">
                                <i class="fas fa-history"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Historique complet</h4>
                                <p class="text-gray-600">Retrouvez tous vos trajets passés avec les détails et factures.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="bg-red-100 text-red-600 rounded-full w-10 h-10 flex items-center justify-center mr-4 mt-1">
                                <i class="fas fa-star"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Notation des chauffeurs</h4>
                                <p class="text-gray-600">Évaluez votre expérience pour nous aider à améliorer nos services.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="/register" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 text-center">
                            Créer un compte
                        </a>
                        <a href="/login" class="bg-white border border-red-600 text-red-600 hover:bg-red-50 font-bold py-3 px-6 rounded-lg transition duration-300 text-center">
                            Se connecter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Notation Chauffeur -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-red-50 rounded-xl p-8 md:p-12">
                <div class="md:flex items-center">
                    <div class="md:w-1/2 mb-8 md:mb-0">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">Comment s'est passé votre trajet ?</h2>
                        <p class="text-gray-600 mb-6">Votre avis compte ! Notez votre chauffeur et partagez votre expérience pour nous aider à améliorer continuellement nos services.</p>
                        <div class="flex items-center">
                            <div class="rating-stars mr-4">
                                <i class="fas fa-star text-2xl"></i>
                                <i class="fas fa-star text-2xl"></i>
                                <i class="fas fa-star text-2xl"></i>
                                <i class="fas fa-star text-2xl"></i>
                                <i class="fas fa-star text-2xl"></i>
                            </div>
                            <span class="text-gray-700 font-semibold">4.8/5 moyenne</span>
                        </div>
                    </div>
                    <div class="md:w-1/2">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">Noter un chauffeur</h3>
                            <form>
                                <div class="mb-4">
                                    <label class="block text-gray-700 mb-2">Numéro de réservation</label>
                                    <input type="text" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Ex: DKR123456">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 mb-2">Note (1 à 5 étoiles)</label>
                                    <div class="flex space-x-2">
                                        <button type="button" class="text-gray-300 hover:text-yellow-400 focus:outline-none">
                                            <i class="fas fa-star text-2xl"></i>
                                        </button>
                                        <button type="button" class="text-gray-300 hover:text-yellow-400 focus:outline-none">
                                            <i class="fas fa-star text-2xl"></i>
                                        </button>
                                        <button type="button" class="text-gray-300 hover:text-yellow-400 focus:outline-none">
                                            <i class="fas fa-star text-2xl"></i>
                                        </button>
                                        <button type="button" class="text-gray-300 hover:text-yellow-400 focus:outline-none">
                                            <i class="fas fa-star text-2xl"></i>
                                        </button>
                                        <button type="button" class="text-gray-300 hover:text-yellow-400 focus:outline-none">
                                            <i class="fas fa-star text-2xl"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 mb-2">Commentaire (optionnel)</label>
                                    <textarea rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Décrivez votre expérience..."></textarea>
                                </div>
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">Envoyer l'évaluation</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tarifs Section -->
    <section id="tarifs" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Nos Tarifs</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Des prix transparents et compétitifs pour tous nos services</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Tarif 1 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-red-600 text-white py-6 px-8">
                        <h3 class="text-xl font-bold">Location voiture</h3>
                        <div class="mt-4">
                            <span class="text-3xl font-bold">60 000 FCFA</span>
                            <span class="text-red-200">/Jour</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <ul class="space-y-3 text-gray-600 mb-8">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Chauffeur professionnel</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Plein carburant</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Climatisation</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Circulez partout à Dakar</span>
                            </li>
                        </ul>
                        <a href="#reservation" class="block text-center bg-gray-100 hover:bg-red-600 hover:text-white text-red-600 font-bold py-3 px-4 rounded-lg transition duration-300">Réserver</a>
                    </div>
                </div>
                
                <!-- Tarif 2 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden transform scale-105">
                    <div class="bg-red-800 text-white py-6 px-8">
                        <h3 class="text-xl font-bold">Transfert AIBD</h3>
                        <div class="mt-4">
                            <span class="text-3xl font-bold">32 500 FCFA</span>
                            <span class="text-red-200">/trajet</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <ul class="space-y-3 text-gray-600 mb-8">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Navette privé</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Disponible 24h/24</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Jusqu'à 3 passagers</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Bagages inclus (2 par personne)</span>
                            </li>
                        </ul>
                        <a href="#reservation" class="block text-center bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">Réserver</a>
                    </div>
                </div>
                
                <!-- Tarif 3 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-red-600 text-white py-6 px-8">
                        <h3 class="text-xl font-bold">Transfert premium</h3>
                        <div class="mt-4">
                            <span class="text-3xl font-bold">100 000 FCFA</span>
                            <span class="text-red-200">/personne</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <ul class="space-y-3 text-gray-600 mb-8">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Véhicule haut de gamme</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Chauffeur professionnel</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Wifi à bord</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Consiergerie (Meet & Great)</span>
                            </li>
                        </ul>
                        <a href="#reservation" class="block text-center bg-gray-100 hover:bg-red-600 hover:text-white text-red-600 font-bold py-3 px-4 rounded-lg transition duration-300">Réserver</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="tarifs" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Tarif 1 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-red-600 text-white py-6 px-8">
                        <h3 class="text-xl font-bold">Location voiture <span class="text-red-200">/hors Dakar</span>
                        </h3>
                        
                        <div class="mt-4">
                            <span class="text-3xl font-bold">80 000 FCFA</span>
                            <span class="text-red-200">/Jour</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <ul class="space-y-3 text-gray-600 mb-8">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Chauffeur professionnel</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Plein carburant</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Climatisation</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Circulez partout</span>
                            </li>
                        </ul>
                        <a href="#reservation" class="block text-center bg-gray-100 hover:bg-red-600 hover:text-white text-red-600 font-bold py-3 px-4 rounded-lg transition duration-300">Réserver</a>
                    </div>
                </div>
                
                <!-- Tarif 2 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden transform scale-105">
                    <div class="bg-red-800 text-white py-6 px-8">
                        <h3 class="text-xl font-bold">Transfert AIBD VIP</h3>
                        <div class="mt-4">
                            <span class="text-3xl font-bold">45 500 FCFA</span>
                            <span class="text-red-200">/trajet</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <ul class="space-y-3 text-gray-600 mb-8">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Service privé</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Disponible 24h/24</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Wifi à bord</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Tout confort inclus</span>
                            </li>
                        </ul>
                        <a href="#reservation" class="block text-center bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">Réserver</a>
                    </div>
                </div>
                
                <!-- Tarif 3 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-red-600 text-white py-6 px-8">
                    <h3 class="text-xl font-bold">Location voiture <span class="text-red-200">/hors Dakar</span>
                    <div class="mt-4">
                            <span class="text-3xl font-bold">65 000 FCFA</span>
                            <span class="text-red-200">/trajet</span>
                        </div>
                    </div>
                    <div class="p-8">
                    <ul class="space-y-3 text-gray-600 mb-8">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Chauffeur professionnel</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Disponible 24h/24</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Climatisation</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Tout confort compris</span>
                            </li>
                        </ul>
                        <a href="#reservation" class="block text-center bg-gray-100 hover:bg-red-600 hover:text-white text-red-600 font-bold py-3 px-4 rounded-lg transition duration-300">Réserver</a>
                    </div>
                </div>
            </div>
            
            <div class="mt-12 text-center">
                <p class="text-gray-600 mb-4">* Les tarifs peuvent varier selon la distance et le nombre de passagers.</p>
                <a href="#contact" class="text-red-600 hover:text-red-800 font-semibold">Contactez-nous pour un devis personnalisé</a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Contactez-nous</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Nous sommes disponibles 24h/24 pour répondre à vos questions et prendre vos réservations</p>
            </div>
            
            <div class="md:flex">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <div class="bg-white p-8 rounded-lg shadow-md h-full">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Nos coordonnées</h3>
                        
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="text-red-600 mr-4 mt-1">
                                    <i class="fas fa-map-marker-alt text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Adresse</h4>
                                    <p class="text-gray-600">Sacré cœur, Dakar, Sénégal</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="text-red-600 mr-4 mt-1">
                                    <i class="fas fa-phone-alt text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Téléphone</h4>
                                    <p class="text-gray-600">+221 77 705 67 67</p>
                                    <p class="text-gray-600">+221 77 705 69 69 (WhatsApp)</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="text-red-600 mr-4 mt-1">
                                    <i class="fas fa-envelope text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Email</h4>
                                    <p class="text-gray-600">221cproservices@gmail.com</p>
                                    <p class="text-gray-600">reservation@dakartransport.sn</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="text-red-600 mr-4 mt-1">
                                    <i class="fas fa-clock text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Horaires</h4>
                                    <p class="text-gray-600">Service disponible 24h/24, 7j/7</p>
                                    <p class="text-gray-600">Bureau: Lundi - Samedi, 8h - 18h</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8">
                            <h4 class="font-bold text-gray-800 mb-4">Suivez-nous</h4>
                            <div class="flex space-x-4">
                                <a href="#" class="bg-red-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-red-700 transition">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="bg-red-400 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-red-500 transition">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="bg-pink-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-pink-700 transition">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="md:w-1/2">
                    <div class="bg-white p-8 rounded-lg shadow-md h-full">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Envoyez-nous un message</h3>
                        <form>
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2">Nom complet</label>
                                <input type="text" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2">Email</label>
                                <input type="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2">Téléphone</label>
                                <input type="tel" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2">Sujet</label>
                                <select class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <option>Demande d'information</option>
                                    <option>Réservation</option>
                                    <option>Réclamation</option>
                                    <option>Partenariat</option>
                                    <option>Autre</option>
                                </select>
                            </div>
                            <div class="mb-6">
                                <label class="block text-gray-700 mb-2">Message</label>
                                <textarea rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">Envoyer le message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="md:flex justify-between">
                <div class="mb-8 md:mb-0">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 100px; width: auto;" class="me-2">
                    <p class="mt-4 text-gray-400 max-w-xs">Service de transport professionnel vers l'aéroport international Blaise Diagne de Dakar.</p>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Liens rapides</h4>
                        <ul class="space-y-2">
                            <li><a href="#accueil" class="text-gray-400 hover:text-white transition">Accueil</a></li>
                            <li><a href="#services" class="text-gray-400 hover:text-white transition">Services</a></li>
                            <li><a href="#reservation" class="text-gray-400 hover:text-white transition">Réservation</a></li>
                            <li><a href="#tarifs" class="text-gray-400 hover:text-white transition">Tarifs</a></li>
                            <li><a href="#contact" class="text-gray-400 hover:text-white transition">Contact</a></li>
                            <li><a href="#compte" class="text-gray-400 hover:text-white transition">Mon compte</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Services</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 hover:text-white transition">Navettes</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition">Location voiture</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition">Transferts privés</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition">Service VIP</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition">Location hors Dakar</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition">Services entreprises</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Légal</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 hover:text-white transition">CGV</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition">Confidentialité</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition">Mentions légales</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 mb-4 md:mb-0">© 2023 CPRO-VLC. Tous droits réservés.</p>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
                
                // Close mobile menu if open
                mobileMenu.classList.add('hidden');
            });
        });
        
        // Highlight active nav link on scroll
        const sections = document.querySelectorAll('section');
        const navLinks = document.querySelectorAll('.nav-link');
        
        window.addEventListener('scroll', () => {
            let current = '';
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                
                if (pageYOffset >= (sectionTop - 100)) {
                    current = section.getAttribute('id');
                }
            });
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });
        
        // Rating stars interaction
        const stars = document.querySelectorAll('.rating-stars button');
        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                stars.forEach((s, i) => {
                    if (i <= index) {
                        s.classList.remove('text-gray-300');
                        s.classList.add('text-yellow-400');
                    } else {
                        s.classList.remove('text-yellow-400');
                        s.classList.add('text-gray-300');
                    }
                });
            });
        });
    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $('#availability-form').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            url: '/reservations/check-availability',
            method: 'POST',
            data: {
                trip_id: $('#trip_id').val(),
                date: $('#date').val(),
                heure_ramassage: $('#heure_ramassage').val(),
                _token: '{{ csrf_token() }}' // important pour sécuriser la requête
            },
            success: function(response) {
                if (response.available) {
                    $('#availability-result').html(
                        '✅ Chauffeur disponible : ' + response.chauffeur.first_name + ' ' + response.chauffeur.last_name
                    ).css('color', 'green');
                } else {
                    $('#availability-result').html('❌ ' + response.message).css('color', 'red');
                }
            },
            error: function(xhr) {
                $('#availability-result').html('Erreur lors de la vérification.').css('color', 'red');
            }
        });
    });
});
</script>

<script>
function updateTarif() {
    var nbPersonnes = parseInt(document.getElementById('nb_personnes').value) || 0;
    var nbValises = parseInt(document.getElementById('nb_valises').value) || 0;

    var tarifBasePersonnes = 32500;
    var tarifParPersonneSupplementaire = 5000;
    var tarifParValiseSupplementaire = 5000;

    var tarif = 0;

    if (nbPersonnes <= 3) {
        tarif = tarifBasePersonnes;
    } else {
        tarif = tarifBasePersonnes + (nbPersonnes - 3) * tarifParPersonneSupplementaire;
    }

    if (nbValises > nbPersonnes * 2) {
        var valisesSupplementaires = nbValises - (nbPersonnes * 2);
        tarif += valisesSupplementaires * tarifParValiseSupplementaire;
    }

    document.getElementById('tarif').value = tarif + ' F';
}

// Ajoute ces écouteurs
document.getElementById('nb_personnes').addEventListener('input', updateTarif);
document.getElementById('nb_valises').addEventListener('input', updateTarif);

// Et forcer une mise à jour au chargement
updateTarif();
</script>

</body>
</html>