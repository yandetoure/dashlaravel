<?php declare(strict_types=1); ?>
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
            color: #3b82f6;
            font-weight: 600;
        }
        
        .booking-form {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }


        /* Couleurs personnalisées */
        .bg-primary {
            background-color: rgb(168, 16, 16);
        }
        
        .bg-primary-dark {
            background-color: rgb(120, 10, 10);
        }
        
        .text-primary {
            color: rgb(168, 16, 16);
        }
        
        .border-primary {
            border-color: rgb(168, 16, 16);
        }
        
        .focus\:ring-primary:focus {
            --tw-ring-color: rgb(168, 16, 16);
        }
        
        .hover\:bg-primary-dark:hover {
            background-color: rgb(120, 10, 10);
        }
    </style>
</head>
<body class="font-sans">
    <!-- Navigation -->
    <nav class="fixed w-full bg-white shadow-md z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                <a href="#accueil" class="text-xl font-bold text-primary">CPRO<span class="text-gray-800">-VLC</span></a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#accueil" class="text-gray-700 hover:text-blue-600 transition nav-link active">Accueil</a>
                    <a href="#services" class="text-gray-700 hover:text-blue-600 transition nav-link">Services</a>
                    <a href="#reservation" class="text-gray-700 hover:text-blue-600 transition nav-link">Réservation</a>
                    <a href="#tarifs" class="text-gray-700 hover:text-blue-600 transition nav-link">Tarifs</a>
                    <a href="#contact" class="text-gray-700 hover:text-blue-600 transition nav-link">Contact</a>
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
            <a href="#accueil" class="block py-2 text-gray-700 hover:text-blue-600 nav-link active">Accueil</a>
            <a href="#services" class="block py-2 text-gray-700 hover:text-blue-600 nav-link">Services</a>
            <a href="#reservation" class="block py-2 text-gray-700 hover:text-blue-600 nav-link">Réservation</a>
            <a href="#tarifs" class="block py-2 text-gray-700 hover:text-blue-600 nav-link">Tarifs</a>
            <a href="#contact" class="block py-2 text-gray-700 hover:text-blue-600 nav-link">Contact</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="accueil" class="hero pt-24 pb-32 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="md:flex items-center justify-between">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">Transport confortable vers l'aéroport AIBD</h1>
                    <p class="text-xl mb-8">Service de navette, location de voiture avec chauffeur et transferts privés vers l'aéroport international Blaise Diagne.</p>
                    <a href="#reservation" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 inline-block">Réserver maintenant</a>
                </div>
                <div class="md:w-1/2">
                    <div class="booking-form p-8 max-w-md mx-auto">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Réserver votre trajet</h3>
                        <form>
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2">Type de service</label>
                                <select class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option>Navette partagée</option>
                                    <option>Voiture avec chauffeur</option>
                                    <option>Transfert privé</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2">Date</label>
                                <input type="date" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2">Heure</label>
                                <input type="time" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">Vérifier disponibilité</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Nos Services</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Nous offrons des solutions de transport adaptées à tous vos besoins vers l'aéroport AIBD</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="service-card bg-white p-8 rounded-lg shadow-md transition duration-300">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-shuttle-van text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Navettes partagées</h3>
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
                    <div class="text-blue-600 mb-4">
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
                    <div class="text-blue-600 mb-4">
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
                            <div class="bg-blue-100 text-blue-600 rounded-full w-10 h-10 flex items-center justify-center mr-4 mt-1">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Réservation instantanée</h4>
                                <p class="text-gray-600">Confirmation immédiate de votre réservation par email et SMS.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="bg-blue-100 text-blue-600 rounded-full w-10 h-10 flex items-center justify-center mr-4 mt-1">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Paiement sécurisé</h4>
                                <p class="text-gray-600">Payez en ligne de manière sécurisée ou en espèces au chauffeur.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="bg-blue-100 text-blue-600 rounded-full w-10 h-10 flex items-center justify-center mr-4 mt-1">
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
                        <form>
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2">Nom complet</label>
                                <input type="text" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2">Email</label>
                                <input type="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2">Téléphone</label>
                                <input type="tel" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2">Point de départ</label>
                                <select class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option>Hôtel Terrou-Bi</option>
                                    <option>Radisson Blu</option>
                                    <option>King Fahd Palace</option>
                                    <option>Almadies</option>
                                    <option>Plateau</option>
                                    <option>Autre (préciser)</option>
                                </select>
                            </div>
                            <div class="mb-6">
                                <label class="block text-gray-700 mb-2">Nombre de passagers</label>
                                <input type="number" min="1" max="8" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">Finaliser la réservation</button>
                        </form>
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
                    <div class="bg-blue-600 text-white py-6 px-8">
                        <h3 class="text-xl font-bold">Navette partagée</h3>
                        <div class="mt-4">
                            <span class="text-3xl font-bold">5 000 FCFA</span>
                            <span class="text-blue-200">/personne</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <ul class="space-y-3 text-gray-600 mb-8">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Départs toutes les heures</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Arrêts multiples</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Climatisation</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Bagages inclus (1 par personne)</span>
                            </li>
                        </ul>
                        <a href="#reservation" class="block text-center bg-gray-100 hover:bg-blue-600 hover:text-white text-blue-600 font-bold py-3 px-4 rounded-lg transition duration-300">Réserver</a>
                    </div>
                </div>
                
                <!-- Tarif 2 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden transform scale-105">
                    <div class="bg-blue-800 text-white py-6 px-8">
                        <h3 class="text-xl font-bold">Voiture avec chauffeur</h3>
                        <div class="mt-4">
                            <span class="text-3xl font-bold">15 000 FCFA</span>
                            <span class="text-blue-200">/trajet</span>
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
                                <span>Jusqu'à 4 passagers</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Bagages inclus (2 par personne)</span>
                            </li>
                        </ul>
                        <a href="#reservation" class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">Réserver</a>
                    </div>
                </div>
                
                <!-- Tarif 3 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-blue-600 text-white py-6 px-8">
                        <h3 class="text-xl font-bold">Transfert premium</h3>
                        <div class="mt-4">
                            <span class="text-3xl font-bold">25 000 FCFA</span>
                            <span class="text-blue-200">/trajet</span>
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
                                <span>Service VIP</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Eau et journaux offerts</span>
                            </li>
                        </ul>
                        <a href="#reservation" class="block text-center bg-gray-100 hover:bg-blue-600 hover:text-white text-blue-600 font-bold py-3 px-4 rounded-lg transition duration-300">Réserver</a>
                    </div>
                </div>
            </div>
            
            <div class="mt-12 text-center">
                <p class="text-gray-600 mb-4">* Les tarifs peuvent varier selon la distance et le nombre de passagers.</p>
                <a href="#contact" class="text-blue-600 hover:text-blue-800 font-semibold">Contactez-nous pour un devis personnalisé</a>
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
                                <div class="text-blue-600 mr-4 mt-1">
                                    <i class="fas fa-map-marker-alt text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Adresse</h4>
                                    <p class="text-gray-600">Avenue Blaise Diagne, Dakar, Sénégal</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="text-blue-600 mr-4 mt-1">
                                    <i class="fas fa-phone-alt text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Téléphone</h4>
                                    <p class="text-gray-600">+221 33 123 45 67</p>
                                    <p class="text-gray-600">+221 77 123 45 67 (WhatsApp)</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="text-blue-600 mr-4 mt-1">
                                    <i class="fas fa-envelope text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Email</h4>
                                    <p class="text-gray-600">contact@dakartransport.sn</p>
                                    <p class="text-gray-600">reservation@dakartransport.sn</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="text-blue-600 mr-4 mt-1">
                                    <i class="fas fa-clock text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Horaires</h4>
                                    <p class="text-gray-600">Service disponible 24h/24, 7j/7</p>
                                    <p class="text-gray-600">Bureau: Lundi - Samedi, 8h - 20h</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8">
                            <h4 class="font-bold text-gray-800 mb-4">Suivez-nous</h4>
                            <div class="flex space-x-4">
                                <a href="#" class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-700 transition">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="bg-blue-400 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-500 transition">
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
                                <input type="text" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2">Email</label>
                                <input type="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2">Téléphone</label>
                                <input type="tel" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2">Sujet</label>
                                <select class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option>Demande d'information</option>
                                    <option>Réservation</option>
                                    <option>Réclamation</option>
                                    <option>Partenariat</option>
                                    <option>Autre</option>
                                </select>
                            </div>
                            <div class="mb-6">
                                <label class="block text-gray-700 mb-2">Message</label>
                                <textarea rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">Envoyer le message</button>
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
                    <a href="#accueil" class="text-xl font-bold text-white">Dakar<span class="text-blue-400">Transport</span></a>
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
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Services</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 hover:text-white transition">Navettes</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition">Location voiture</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition">Transferts privés</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition">Service VIP</a></li>
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
                <p class="text-gray-400 mb-4 md:mb-0">© 2023 DakarTransport. Tous droits réservés.</p>
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
    </script>
</body>
</html>