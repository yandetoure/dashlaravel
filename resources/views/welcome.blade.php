<?php declare(strict_types=1); ?>
<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            background-color: rgba(239, 68, 68, 0.1);
            padding: 8px 16px;
            border-radius: 6px;
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

        /* Personnalisation de la scrollbar pour Webkit (Chrome, Safari, etc.) */
        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }

        /* Pour Firefox */
        .overflow-x-auto {
            scrollbar-color: #cbd5e0 #f1f1f1;
        }

        /* Pour masquer la scrollbar sur mobile tout en gardant la fonctionnalité */
        @media (max-width: 640px) {
            .overflow-x-auto {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
            .overflow-x-auto::-webkit-scrollbar {
                display: none;
            }
        }

        /* Animation de survol des cartes */
        .hover\:shadow-md {
            transition: all 0.2s ease-in-out;
        }
        .hover\:shadow-md:hover {
            transform: translateY(-2px);
        }

        /* Personnalisation de la scrollbar pour la sidebar */
        .overflow-y-auto::-webkit-scrollbar {
            width: 4px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 2px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }

        /* Pour Firefox */
        .overflow-y-auto {
            scrollbar-color: #cbd5e0 #f1f1f1;
            scrollbar-width: thin;
        }

        /* Responsive : cacher la sidebar sur mobile */
        @media (max-width: 768px) {
            .fixed.left-0 {
                display: none;
            }
            .ml-80 {
                margin-left: 0;
            }
            
            /* Responsive pour les actualités sur mobile */
            #sidebar {
                position: relative !important;
                width: 100% !important;
                margin-left: 0 !important;
            }
            
            .sidebar-container {
                width: 100% !important;
                position: relative !important;
            }
            
            .main-content {
                margin-left: 0 !important;
            }
            
            /* Style pour le bouton toggle des actualités */
            #toggle-news-btn {
                display: block;
            }
            
            .news-content.collapsed {
                max-height: 200px;
                overflow: hidden;
                position: relative;
            }
            
            .news-content.collapsed::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 50px;
                background: linear-gradient(transparent, white);
                pointer-events: none;
            }
        }

        /* Desktop/Tablette : sidebar fixe après le hero */
        @media (min-width: 769px) {
            #toggle-news-btn {
                display: none;
            }
            
            .sidebar-container {
                position: relative;
                z-index: 10;
            }
            
            #sidebar {
                transition: all 0.3s ease;
            }
            
            #sidebar.fixed-sidebar {
                position: fixed;
                top: 80px;
                left: 0;
                width: 320px;
                height: calc(100vh - 80px);
                z-index: 30;
            }
            
            .main-content {
                transition: margin-left 0.3s ease;
            }
            
            .main-content.with-fixed-sidebar {
                margin-left: 320px;
            }
        }

        /* Variables CSS pour le mode sombre */
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f9fafb;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --card-bg: #ffffff;
            --nav-bg: rgba(255, 255, 255, 0.95);
        }

        [data-theme="dark"] {
            --bg-primary: #111827;
            --bg-secondary: #1f2937;
            --text-primary: #f9fafb;
            --text-secondary: #d1d5db;
            --border-color: #374151;
            --card-bg: #1f2937;
            --nav-bg: rgba(17, 24, 39, 0.95);
        }

        /* Application des variables CSS */
        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .bg-gray-50 {
            background-color: var(--bg-secondary) !important;
        }

        .bg-white {
            background-color: var(--card-bg) !important;
        }

        .text-gray-800 {
            color: var(--text-primary) !important;
        }

        .text-gray-600 {
            color: var(--text-secondary) !important;
        }

        .border-gray-200 {
            border-color: var(--border-color) !important;
        }

        .navbar-scrolled {
            background: var(--nav-bg) !important;
        }

        /* Styles pour les boutons de contrôle */
        .control-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .control-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.9);
            padding: 6px 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 14px;
            cursor: pointer;
        }

        .control-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        .navbar-scrolled .control-btn {
            background: rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.1);
            color: var(--text-primary);
        }

        .navbar-scrolled .control-btn:hover {
            background: rgba(0, 0, 0, 0.1);
        }

        /* Styles pour le modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .modal.active {
            display: flex;
            opacity: 1;
        }

        .modal-content {
            background: white;
            margin: auto;
            width: 90%;
            max-width: 600px;
            border-radius: 8px;
            transform: translateY(-20px);
            transition: transform 0.3s ease-in-out;
        }

        .modal.active .modal-content {
            transform: translateY(0);
        }

        .modal-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        body.modal-open {
            overflow: hidden;
        }

        /* Animations pour le chatbox */
        @keyframes bounce-attention {
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

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-2px); }
            20%, 40%, 60%, 80% { transform: translateX(2px); }
        }

        .bounce-attention {
            animation: bounce-attention 2s infinite;
        }

        .shake {
            animation: shake 0.5s ease-in-out;
        }

        /* Style pour la bulle d'invitation */
        #invitation-bubble {
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        #invitation-bubble.show {
            transform: scale(1);
            animation: slideInUp 0.6s ease-out;
        }

        /* Animations pour la bulle d'invitation */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.8);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-5px);
            }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        /* Gradient animé pour la bulle */
        @keyframes gradient-shift {
            0%, 100% {
                background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
            }
            50% {
                background: linear-gradient(135deg, #fef2f2 0%, #fef7f7 100%);
            }
        }

        #invitation-bubble:hover {
            animation: gradient-shift 2s ease-in-out infinite;
        }

        /* Animation de pulsation personnalisée */
        .pulse-glow {
            animation: pulse-glow 2s infinite;
        }

        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(239, 68, 68, 0.5);
                transform: scale(1);
            }
            50% {
                box-shadow: 0 0 30px rgba(239, 68, 68, 0.8);
                transform: scale(1.05);
            }
        }

        /* Styles pour la navbar transparente */
        .navbar-transparent {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease-in-out;
        }

        .navbar-scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Styles pour les liens de navigation sur fond transparent */
        .nav-transparent .nav-link {
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .nav-transparent .nav-link:hover {
            color: #ef4444 !important;
            background-color: rgba(255, 255, 255, 0.15);
            text-shadow: none;
        }

        .nav-transparent .nav-link.active {
            color: #ef4444 !important;
            background-color: rgba(255, 255, 255, 0.2);
            text-shadow: none;
        }

        .nav-transparent .phone-links a {
            color: rgba(255, 255, 255, 0.8);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .nav-transparent .phone-links a:hover {
            color: #ffffff;
        }

        .nav-transparent .mobile-menu-btn {
            color: rgba(255, 255, 255, 0.9);
        }
    </style>
</head>
<body class="font-sans">
    <!-- Navigation -->
    <nav id="navbar" class="fixed w-full navbar-transparent z-50 nav-transparent">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Phone numbers banner -->
            <div class="py-2 border-b border-white/10 flex justify-between items-center text-sm phone-links">
                <div class="hidden md:flex space-x-4">
                    <a href="tel:+221777056767" class="hover:text-white transition-colors">
                        <i class="fas fa-phone mr-1"></i> +221 77 705 67 67
                    </a>
                    <a href="tel:+221777056969" class="hover:text-white transition-colors">
                        <i class="fab fa-whatsapp mr-1"></i> +221 77 705 69 69 (WhatsApp)
                    </a>
                </div>
                <!-- Numéros pour mobile -->
                <div class="flex md:hidden space-x-2 text-xs">
                    <a href="tel:+221777056767" class="hover:text-white transition-colors">
                        <i class="fas fa-phone"></i>
                    </a>
                    <a href="tel:+221777056969" class="hover:text-white transition-colors">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
                <div class="hidden md:block"></div>
            </div>
            <div class="flex justify-between h-14">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 100px; width: auto;" class="me-2">
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#accueil" class="nav-link hover:text-white transition">Accueil</a>
                    <a href="#actualites" class="nav-link hover:text-white transition">Actualités</a>
                    <a href="#tarifs" class="nav-link hover:text-white transition">Tarifs</a>
                    <a href="#services" class="nav-link hover:text-white transition">Services</a>
                    <a href="#reservation" class="nav-link hover:text-white transition">Réservation</a>
                    <a href="#contact" class="nav-link hover:text-white transition">Contact</a>
                    @auth
                        <a href="{{ route('profile.edit') }}" class="nav-link hover:text-white transition">Mon compte</a>
                    @else
                        <a href="#compte" class="nav-link hover:text-white transition">Mon compte</a>
                    @endauth
                    
                    <!-- Boutons de contrôle -->
                    <div class="control-buttons">
                        <!-- Sélecteur de langue -->
                        <div class="relative">
                            <button id="language-btn" class="control-btn flex items-center">
                                <i class="fas fa-globe mr-2"></i>
                                <span id="current-language">FR</span>
                                <i class="fas fa-chevron-down ml-1"></i>
                            </button>
                            <div id="language-dropdown" class="hidden absolute top-full right-0 mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg py-2 min-w-[150px] z-50">
                                <button class="language-option flex items-center w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200" data-lang="fr">
                                    <span class="flag-icon mr-3">🇫🇷</span>
                                    <span>Français</span>
                                </button>
                                <button class="language-option flex items-center w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200" data-lang="en">
                                    <span class="flag-icon mr-3">🇬🇧</span>
                                    <span>English</span>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Bouton mode sombre -->
                        <button id="theme-toggle" class="control-btn">
                            <i id="theme-icon" class="fas fa-moon"></i>
                        </button>
                    </div>
                </div>
                <div class="md:hidden flex items-center">
                    <button id="menu-btn" class="mobile-menu-btn text-white focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Menu mobile -->
        <div id="mobile-menu" class="hidden md:hidden bg-white/95 backdrop-blur-md border-t border-gray-200">
            <div class="px-4 py-4 space-y-3">
                <!-- Numéros de téléphone en haut -->
                <div class="border-b border-gray-200 pb-3 mb-3">
                    <div class="text-center space-y-2">
                        <a href="tel:+221777056767" class="flex items-center justify-center text-gray-700 hover:text-red-600 transition-colors text-sm">
                            <i class="fas fa-phone mr-2 text-red-600"></i> +221 77 705 67 67
                        </a>
                        <a href="tel:+221777056969" class="flex items-center justify-center text-gray-700 hover:text-red-600 transition-colors text-sm">
                            <i class="fab fa-whatsapp mr-2 text-green-600"></i> +221 77 705 69 69 (WhatsApp)
                        </a>
                    </div>
                </div>
                
                <!-- Liens de navigation -->
                <a href="#accueil" class="mobile-nav-link block text-gray-700 hover:text-red-600 hover:bg-red-50 px-3 py-2 rounded transition-colors">
                    <i class="fas fa-home mr-3"></i>Accueil
                </a>
                <a href="#actualites" class="mobile-nav-link block text-gray-700 hover:text-red-600 hover:bg-red-50 px-3 py-2 rounded transition-colors">
                    <i class="fas fa-newspaper mr-3"></i>Actualités
                </a>
                <a href="#tarifs" class="mobile-nav-link block text-gray-700 hover:text-red-600 hover:bg-red-50 px-3 py-2 rounded transition-colors">
                    <i class="fas fa-tags mr-3"></i>Tarifs
                </a>
                <a href="#services" class="mobile-nav-link block text-gray-700 hover:text-red-600 hover:bg-red-50 px-3 py-2 rounded transition-colors">
                    <i class="fas fa-concierge-bell mr-3"></i>Services
                </a>
                <a href="#reservation" class="mobile-nav-link block text-gray-700 hover:text-red-600 hover:bg-red-50 px-3 py-2 rounded transition-colors">
                    <i class="fas fa-calendar-check mr-3"></i>Réservation
                </a>
                <a href="#contact" class="mobile-nav-link block text-gray-700 hover:text-red-600 hover:bg-red-50 px-3 py-2 rounded transition-colors">
                    <i class="fas fa-envelope mr-3"></i>Contact
                </a>
                @auth
                    <a href="{{ route('profile.edit') }}" class="mobile-nav-link block text-gray-700 hover:text-red-600 hover:bg-red-50 px-3 py-2 rounded transition-colors">
                        <i class="fas fa-user mr-3"></i>Mon compte
                    </a>
                @else
                    <a href="#compte" class="mobile-nav-link block text-gray-700 hover:text-red-600 hover:bg-red-50 px-3 py-2 rounded transition-colors">
                        <i class="fas fa-user mr-3"></i>Mon compte
                    </a>
                @endauth
                
                <!-- Bouton de réservation rapide -->
                <div class="pt-3 border-t border-gray-200">
                    <a href="#reservation" class="block bg-red-600 hover:bg-red-700 text-white text-center py-3 px-4 rounded-lg transition-colors font-semibold">
                        <i class="fas fa-car mr-2"></i>Réserver maintenant
                    </a>
                </div>
            </div>
        </div>
    </nav>

<!-- Hero Section -->
    <section id="accueil" class="hero w-full pt-24 pb-32 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex items-center justify-between mt-10">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">Nous sommes le Leader du Transfert/Shuttle Aéroportuaire</h1>
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

    <!-- Main Content with Sidebar -->
    <div class="flex">
        <!-- Sidebar Container -->
        <div class="w-80 relative sidebar-container">
            <!-- Sidebar Content -->
            <div id="sidebar" class="w-80">
                <div class="bg-white shadow-lg border-r border-gray-200">
                    <!-- En-tête de la sidebar -->
                    <div id="actualites" class="p-4 border-b border-gray-200 bg-gray-50 sticky top-0 z-10">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-semibold text-gray-800">Dernières actualités</h3>
                            <!-- Bouton toggle pour mobile -->
                            <button id="toggle-news-btn" class="hidden md:hidden bg-red-600 text-white px-3 py-1 rounded-md text-sm hover:bg-red-700 transition-colors">
                                <span id="toggle-text">Voir moins</span>
                                <i id="toggle-icon" class="fas fa-chevron-up ml-1"></i>
                            </button>
                        </div>
                        <p class="text-sm text-gray-500">Restez informé de nos actualités</p>
                    </div>

                    <!-- Liste des actualités scrollable -->
                    <div id="news-content" class="news-content overflow-y-auto p-4 space-y-4" style="max-height: calc(100vh - 100px);">
                        @foreach($actus->take(5) as $actu)
                            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden border border-gray-100 cursor-pointer actu-card" 
                                 data-actu-id="{{ $actu->id }}"
                                 data-actu-title="{{ $actu->title }}"
                                 data-actu-content="{{ $actu->content }}"
                                 data-actu-category="{{ $actu->category }}"
                                 data-actu-date="{{ $actu->created_at->format('d/m/Y') }}"
                                 data-actu-image="{{ $actu->image ? asset('storage/' . $actu->image) : '' }}"
                                 data-actu-link="{{ $actu->external_link }}">
                                    @if($actu->image)
                                        <div class="relative h-32">
                                            <img src="{{ asset('storage/' . $actu->image) }}" 
                                                 alt="{{ $actu->title }}" 
                                                 class="w-full h-full object-cover">
                                            <div class="absolute top-2 right-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $actu->category === 'infos' ? 'infos utiles' : $actu->category }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="p-3">
                                        <h4 class="font-medium text-gray-900 text-sm mb-1 line-clamp-1">{{ $actu->title }}</h4>
                                        <p class="text-gray-500 text-xs mb-2 line-clamp-2">{{ Str::limit($actu->content, 80) }}</p>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-400">{{ $actu->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Modal pour les détails de l'actualité -->
                        <div id="actuModal" class="modal">
                            <div class="modal-content max-h-[90vh] overflow-y-auto">
                                <div class="relative">
                                    <img id="modalImage" src="" alt="" class="modal-image hidden">
                                    <button class="absolute top-4 right-4 text-white bg-gray-800 bg-opacity-50 rounded-full p-2 hover:bg-opacity-75" onclick="closeModal()">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <span id="modalCategory" class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800"></span>
                                        <span id="modalDate" class="text-sm text-gray-500"></span>
                                    </div>
                                    <h3 id="modalTitle" class="text-2xl font-bold text-gray-900 mb-4"></h3>
                                    <div id="modalContent" class="prose max-w-none text-gray-600 mb-6"></div>
                                    <div id="modalLinkContainer" class="hidden mt-4 pt-4 border-t border-gray-200">
                                        <a id="modalLink" href="#" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200" onclick="window.open(this.getAttribute('data-href'), '_blank')">
                                            <span>En savoir plus</span>
                                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 main-content" style="margin-left: 320px;">
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
                        <div class="bg-red-600 text-white py-6 px-6">
                            <h3 class="text-lg font-bold mb-2">Transfert AIBD VIP</h3>
                            <div class="mt-2">
                                <span class="text-2xl font-bold">45 000</span>
                                <span class="text-base ml-1">FCFA</span>
                                <span class="text-sm text-red-200">/trajet</span>
                        </div>
                    </div>
                        <div class="p-6">
                            <ul class="space-y-3 text-sm text-gray-600 mb-6">
                            <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span>Navette privée</span>
                            </li>
                            <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span>Disponible 24h/24</span>
                            </li>
                            <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span>Wifi à bord</span>
                            </li>
                            <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span>Bagages inclus (2 par personne)</span>
                            </li>
                        </ul>
                            <a href="#reservation" class="block text-center bg-gray-100 hover:bg-red-600 hover:text-white text-red-600 font-bold py-3 px-4 rounded-lg transition duration-300">Réserver</a>
                    </div>
                </div>
                                <!-- Tarif 2 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-red-800 text-white py-6 px-6">
                            <h3 class="text-lg font-bold mb-2">Transfert AIBD</h3>
                            <div class="mt-2">
                                <span class="text-2xl font-bold">32 500</span>
                                <span class="text-base ml-1">FCFA</span>
                                <span class="text-sm text-red-200">/trajet</span>
                        </div>
                    </div>
                        <div class="p-6">
                            <ul class="space-y-3 text-sm text-gray-600 mb-6">
                            <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span>Navette privée</span>
                            </li>
                            <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span>Disponible 24h/24</span>
                            </li>
                            <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span>Jusqu'à 3 personnes</span>
                            </li>
                            <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span>Tout confort inclus</span>
                            </li>
                        </ul>
                            <a href="#reservation" class="block text-center bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">Réserver</a>
                    </div>
                </div>
                
                <!-- Tarif 3 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-red-600 text-white py-6 px-6">
                            <h3 class="text-lg font-bold mb-2">Transfert PREM/(Meet & Greet)</h3>
                            <div class="mt-2">
                                <span class="text-2xl font-bold">65 000</span>
                                <span class="text-base ml-1">FCFA</span>
                                <span class="text-sm text-red-200">/trajet</span>
                        </div>
                    </div>
                        <div class="p-6">
                            <ul class="space-y-3 text-sm text-gray-600 mb-6">
                            <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span>Véhicule haut de gamme</span>
                            </li>
                            <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span>Chauffeur professionnel</span>
                            </li>
                            <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span>Wifi à bord</span>
                            </li>
                            <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span>Conciergerie (Meet & Greet)</span>
                            </li>
                        </ul>
                            <a href="#reservation" class="block text-center bg-gray-100 hover:bg-red-600 hover:text-white text-red-600 font-bold py-3 px-4 rounded-lg transition duration-300">Réserver</a>
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
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Nous offrons des solutions de transport adaptées à tous vos besoins vers l'aéroport AIBD et au-delà</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Service 1 -->
                {{-- <div class="service-card bg-white p-8 rounded-lg shadow-md transition duration-300">
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
                 --}}

                                 <!-- Service 6 -->
                <div class="service-card bg-white p-8 rounded-lg shadow-md transition duration-300">
                       <div class="mb-4 flex justify-center">
                        <img src="images/serv4.jpeg" alt="Icone Service 1" class="w-full h-auto object-contain" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Transferts Aéroport</h3>
                    <p class="text-gray-600 mb-4">Flotte exclusive de Vans.</p>
                    <ul class="text-gray-600 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Sécurité : Chauffeurs expérimentés, vitesse controlée</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Confort : clim / wifi / eau à bord</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Espace : jusqu'à 6 personnes et 12 valises</span>
                        </li>
                    </ul>
                </div>

                
                <!-- Service 5 -->
                <div class="service-card bg-white p-8 rounded-lg shadow-md transition duration-300">
                       <div class="mb-4 flex justify-center">
                        <img src="images/ent.png" alt="Icone Service 1" class="w-full h-auto object-contain" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Services aux Entreprises</h3>
                    <p class="text-gray-600 mb-4">Solutions de transport professionnelles pour les besoins de mobilité de votre entreprise.</p>
                    <ul class="text-gray-600 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Transferts d'employés</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Références compagnies internationales</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Partenaire AIBD</span>
                        </li>
                    </ul>
                </div>
                

              <!-- Service 4 -->
                <div class="service-card bg-white p-8 rounded-lg shadow-md transition duration-300">
                       <div class="mb-4 flex justify-center">
                        <img src="images/meet.png" alt="Icone Service 1" class="w-full h-auto object-contain" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Assistance & Conciergerie</h3>
                    <p class="text-gray-600 mb-4">Service de location avec chauffeur pour des trajets longue distance en dehors de Dakar.</p>
                    <ul class="text-gray-600 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Suivi et informations de vol</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Accueil et Facilitation de passage</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Récupération valises / Réclamations</span>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="tarifs" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                                <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Services annexes</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Tarif 1 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-red-600 text-white py-5 px-6">
                        <h3 class="text-base font-bold">Location voiture<span class="text-red-200">/Dakar</span></h3>
                        <div class="mt-3">
                            <span class="text-xl font-bold">50 000 FCFA</span>
                            <span class="text-red-200 text-sm">/Jour</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-2 text-sm text-gray-600 mb-6">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Chauffeur professionnel</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Hors carburant</span>
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
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-red-800 text-white py-5 px-6">
                        <h3 class="text-base font-bold">Location voiture<span class="text-red-200">/hors Dakar</span></h3>
                        <div class="mt-3">
                            <span class="text-xl font-bold">80 000 FCFA</span>
                            <span class="text-red-200 text-sm">/Jour</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-2 text-sm text-gray-600 mb-6">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Chauffeur professionnel</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                <span>Hors carburant</span>
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
                        <a href="#reservation" class="block text-center bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">Réserver</a>
                    </div>
                </div>
             
                <!-- Tarif 3 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-red-600 text-white py-5 px-6">
                    <h3 class="text-base font-bold">Location voiture <span class="text-red-200">/Dakar</span></h3>
                <div class="mt-3">
                            <span class="text-xl font-bold">65 000 FCFA</span>
                            <span class="text-red-200 text-sm">/jour</span>
                    </div>
                </div>
                <div class="p-6">
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
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
                        
                        <!-- Messages de succès -->
                        <div id="success-message" class="hidden bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium">
                                        Réservation confirmée ! 🎉
                                    </p>
                                    <p class="text-sm mt-1">
                                        Vous recevrez une confirmation par email et SMS dans quelques minutes.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
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
                        <form id="reservation-form" action="{{ route('reservations.storeByProspect') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                <label for="phone" class="block text-gray-700 mb-2">Téléphone</label>
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
                                <label for="trip_id_reservation" class="block text-sm font-medium text-gray-700 mb-1">Sens du trajet <span class="text-red-500">*</span></label>
                                <select id="trip_id_reservation" name="trip_id" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                    <option value="">-- Sélectionner un trajet --</option>
                                    @foreach($trips as $trip)
                                        <option value="{{ $trip->id }}">{{ $trip->departure }} - {{ $trip->destination }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="tarif_reservation" class="block text-sm font-medium text-gray-700 mb-1">Tarif estimé</label>
                                <input type="text" id="tarif_reservation" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-gray-100" readonly>
                            </div>

                            <!-- Bouton -->
                            <div class="md:col-span-2">
                                <button type="submit" id="submit-btn" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                                    <span class="submit-text">Finaliser la réservation</span>
                                    <i class="fas fa-spinner fa-spin hidden submit-spinner ml-2"></i>
                                </button>
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
                    <img src="images/register.png" alt="Application mobile" class="rounded-lg shadow-xl">
                </div>
                <div class="md:w-1/2">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Créez votre compte client</h2>
                    <p class="text-xl text-gray-600 mb-8">Accédez à toutes vos réservations passées et futures, consultez votre historique de trajets et vos points de fidélité pour gagner des avantages.</p>
                    
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
        const sidebar = document.getElementById('sidebar');
        const sidebarContainer = sidebar.parentElement;
        
        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            
            // Changer l'icône du menu burger
            const icon = menuBtn.querySelector('i');
            if (mobileMenu.classList.contains('hidden')) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            } else {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            }
        });

        // Fermer le menu mobile quand on clique sur un lien
        document.querySelectorAll('.mobile-nav-link').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                const icon = menuBtn.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            });
        });

        // Fermer le menu mobile quand on clique à l'extérieur
        document.addEventListener('click', (e) => {
            if (!mobileMenu.contains(e.target) && !menuBtn.contains(e.target)) {
                mobileMenu.classList.add('hidden');
                const icon = menuBtn.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
        
        // Navbar transparent effect on scroll
        const navbar = document.getElementById('navbar');
        
        function updateNavbarOnScroll() {
            if (window.scrollY > 50) {
                navbar.classList.remove('navbar-transparent', 'nav-transparent');
                navbar.classList.add('navbar-scrolled');
                
                // Changer les couleurs des liens pour le mode scrollé
                const navLinks = navbar.querySelectorAll('.nav-link');
                const phoneLinks = navbar.querySelectorAll('.phone-links a');
                const menuBtn = navbar.querySelector('.mobile-menu-btn');
                
                navLinks.forEach(link => {
                    link.classList.remove('text-white');
                    link.classList.add('text-gray-700');
                    link.style.textShadow = 'none';
                });
                
                phoneLinks.forEach(link => {
                    link.classList.remove('text-white');
                    link.classList.add('text-gray-600');
                    link.style.textShadow = 'none';
                });
                
                if (menuBtn) {
                    menuBtn.classList.remove('text-white');
                    menuBtn.classList.add('text-gray-700');
                }
                
            } else {
                navbar.classList.remove('navbar-scrolled');
                navbar.classList.add('navbar-transparent', 'nav-transparent');
                
                // Remettre les couleurs transparentes
                const navLinks = navbar.querySelectorAll('.nav-link');
                const phoneLinks = navbar.querySelectorAll('.phone-links a');
                const menuBtn = navbar.querySelector('.mobile-menu-btn');
                
                navLinks.forEach(link => {
                    link.classList.remove('text-gray-700');
                    link.classList.add('text-white');
                    link.style.textShadow = '0 1px 3px rgba(0, 0, 0, 0.3)';
                });
                
                phoneLinks.forEach(link => {
                    link.classList.remove('text-gray-600');
                    link.classList.add('text-white');
                    link.style.textShadow = '0 1px 2px rgba(0, 0, 0, 0.3)';
                });
                
                if (menuBtn) {
                    menuBtn.classList.remove('text-gray-700');
                    menuBtn.classList.add('text-white');
                }
            }
        }
        
        // Appliquer l'effet au scroll
        window.addEventListener('scroll', updateNavbarOnScroll);
        
        // Appliquer l'état initial
        updateNavbarOnScroll();
        
        // Gestion du scroll de la sidebar
        function updateSidebarPosition() {
            const sidebarRect = sidebar.getBoundingClientRect();
            const heroSection = document.querySelector('.hero');
            const heroBottom = heroSection.getBoundingClientRect().bottom;
            
            // Si on est dans la zone de la bannière, on retire la position fixe
            if (heroBottom > 0) {
                sidebar.style.position = 'relative';
                sidebar.style.top = 'auto';
                sidebar.style.left = 'auto';
                sidebar.style.width = 'auto';
                return;
            }
            
            // Sinon, on applique la logique de fixation normale
            if (sidebarRect.top <= 0) {
                sidebar.style.position = 'fixed';
                sidebar.style.top = '0';
                sidebar.style.left = '0';
                sidebar.style.width = '20rem';
            } else {
                sidebar.style.position = 'relative';
                sidebar.style.top = 'auto';
                sidebar.style.left = 'auto';
                sidebar.style.width = 'auto';
            }
        }
        
        // Écouteurs d'événements
        window.addEventListener('scroll', updateSidebarPosition);
        window.addEventListener('resize', updateSidebarPosition);
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
                
                // Close mobile menu if open
                mobileMenu.classList.add('hidden');
                const icon = menuBtn.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
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
        
        // Gestion du modal des actualités
        const modal = document.getElementById('actuModal');
        const modalImage = document.getElementById('modalImage');
        const modalCategory = document.getElementById('modalCategory');
        const modalDate = document.getElementById('modalDate');
        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');
        const modalLink = document.getElementById('modalLink');
        const modalLinkContainer = document.getElementById('modalLinkContainer');

        // Ajouter les écouteurs d'événements pour les cartes d'actualités
        document.querySelectorAll('.actu-card').forEach(card => {
            card.addEventListener('click', () => {
                const data = card.dataset;
                
                // Mise à jour du contenu du modal
                if (data.actuImage) {
                    modalImage.src = data.actuImage;
                    modalImage.classList.remove('hidden');
                    } else {
                    modalImage.classList.add('hidden');
                }
                
                modalCategory.textContent = data.actuCategory === 'infos' ? 'infos utiles' : data.actuCategory;
                modalDate.textContent = data.actuDate;
                modalTitle.textContent = data.actuTitle;
                modalContent.innerHTML = data.actuContent.replace(/\n/g, '<br>');
                
                // Gestion du lien externe
                if (data.actuLink && data.actuLink !== "null" && data.actuLink.trim() !== "") {
                    modalLinkContainer.classList.remove('hidden');
                    modalLink.setAttribute('data-href', data.actuLink);
                } else {
                    modalLinkContainer.classList.add('hidden');
                }
                
                // Afficher le modal
                modal.classList.add('active');
                document.body.classList.add('modal-open');
                });
            });

        // Fonction pour fermer le modal
        function closeModal() {
            modal.classList.remove('active');
            document.body.classList.remove('modal-open');
        }

        // Fermer le modal en cliquant sur l'arrière-plan
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Fermer le modal avec la touche Echap
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        });

        // NOUVEAU : Gestion du formulaire de vérification de disponibilité (section hero)
        document.getElementById('availability-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const resultDiv = document.getElementById('availability-result');
            
            // Vérifier que tous les champs sont remplis
            if (!formData.get('trip_id') || !formData.get('date') || !formData.get('heure_ramassage')) {
                resultDiv.innerHTML = '<div class="text-red-600">Veuillez remplir tous les champs.</div>';
                return;
            }
            
            resultDiv.innerHTML = '<div class="text-blue-600">Vérification en cours...</div>';
            
            fetch('{{ route("reservations.checkAvailability") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    resultDiv.innerHTML = `<div class="text-green-600">${data.message}</div>`;
                } else {
                    resultDiv.innerHTML = `<div class="text-red-600">${data.message}</div>`;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                resultDiv.innerHTML = '<div class="text-red-600">Erreur lors de la vérification. Veuillez réessayer.</div>';
            });
        });

        // NOUVEAU : Calcul automatique des tarifs pour le formulaire de réservation
        function calculateTariff() {
            const nbPersonnes = parseInt(document.getElementById('nb_personnes').value) || 0;
            const nbValises = parseInt(document.getElementById('nb_valises').value) || 0;
            const tarifField = document.getElementById('tarif_reservation');
            
            if (nbPersonnes === 0) {
                tarifField.value = '';
                return;
            }
            
            // Tarif de base pour 1 à 3 personnes
            let tarif = 32500;
            
            // Supplément pour personnes supplémentaires (au-delà de 3)
            if (nbPersonnes > 3) {
                tarif += (nbPersonnes - 3) * 5000;
            }
            
            // Valises incluses : 2 par personne
            const valisesIncluses = nbPersonnes * 2;
            if (nbValises > valisesIncluses) {
                tarif += (nbValises - valisesIncluses) * 5000;
            }
            
            // Afficher le tarif formaté
            tarifField.value = tarif.toLocaleString('fr-FR') + ' FCFA';
        }

        // Écouter les changements pour calculer automatiquement le tarif
        document.getElementById('nb_personnes').addEventListener('input', calculateTariff);
        document.getElementById('nb_valises').addEventListener('input', calculateTariff);

        // Ajouter le meta CSRF token si pas déjà présent
        if (!document.querySelector('meta[name="csrf-token"]')) {
            const meta = document.createElement('meta');
            meta.name = 'csrf-token';
            meta.content = '{{ csrf_token() }}';
            document.head.appendChild(meta);
        }

        // Si la page est déjà chargée, déclencher immédiatement
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', showWelcomeInvitation);
        } else {
            showWelcomeInvitation();
        }

        // Fonction pour fermer la bulle d'invitation
        function closeInvitation() {
            invitationBubble.classList.remove('show', 'float-animation');
            chatButton.classList.remove('pulse-glow', 'bounce-attention');
            chatButton.classList.add('animate-pulse');
        }

        // Gestion du toggle des actualités sur mobile
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggle-news-btn');
            const newsContent = document.getElementById('news-content');
            const toggleText = document.getElementById('toggle-text');
            const toggleIcon = document.getElementById('toggle-icon');
            let isCollapsed = false;

            // Vérifier si on est sur mobile et initialiser l'état
            function checkMobile() {
                const isMobile = window.innerWidth <= 768;
                if (isMobile) {
                    toggleBtn.classList.remove('hidden');
                    toggleBtn.classList.add('block');
                    // Démarrer avec les actualités réduites sur mobile
                    if (!isCollapsed) {
                        newsContent.classList.add('collapsed');
                        toggleText.textContent = 'Voir plus';
                        toggleIcon.classList.remove('fa-chevron-up');
                        toggleIcon.classList.add('fa-chevron-down');
                        isCollapsed = true;
                    }
                } else {
                    toggleBtn.classList.add('hidden');
                    toggleBtn.classList.remove('block');
                    newsContent.classList.remove('collapsed');
                    isCollapsed = false;
                }
            }

            // Vérifier au chargement
            checkMobile();

            // Vérifier au redimensionnement
            window.addEventListener('resize', checkMobile);

            // Gestion du clic sur le bouton toggle
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    if (isCollapsed) {
                        newsContent.classList.remove('collapsed');
                        toggleText.textContent = 'Voir moins';
                        toggleIcon.classList.remove('fa-chevron-down');
                        toggleIcon.classList.add('fa-chevron-up');
                        isCollapsed = false;
                    } else {
                        newsContent.classList.add('collapsed');
                        toggleText.textContent = 'Voir plus';
                        toggleIcon.classList.remove('fa-chevron-up');
                        toggleIcon.classList.add('fa-chevron-down');
                        isCollapsed = true;
                    }
                });
            }
        });

        // Gestion du formulaire de réservation avec AJAX
        document.getElementById('reservation-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const submitBtn = document.getElementById('submit-btn');
            const submitText = submitBtn.querySelector('.submit-text');
            const submitSpinner = submitBtn.querySelector('.submit-spinner');
            const successMessage = document.getElementById('success-message');
            
            // Désactiver le bouton et afficher le spinner
            submitBtn.disabled = true;
            submitText.textContent = 'Réservation en cours...';
            submitSpinner.classList.remove('hidden');
            
            // Cacher les messages précédents
            successMessage.classList.add('hidden');
            document.querySelectorAll('.error-message').forEach(el => el.remove());
            
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Afficher le message de succès
                    successMessage.classList.remove('hidden');
                    
                    // Réinitialiser le formulaire
                    form.reset();
                    document.getElementById('tarif_reservation').value = '';
                    
                    // Faire défiler vers le message de succès
                    successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                } else {
                    // Afficher les erreurs
                    if (data.errors) {
                        for (const [field, messages] of Object.entries(data.errors)) {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'error-message text-red-600 text-sm mt-1';
                                errorDiv.textContent = messages[0];
                                input.parentNode.appendChild(errorDiv);
                            }
                        }
                    } else {
                        alert(data.message || 'Une erreur est survenue. Veuillez réessayer.');
                    }
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue. Veuillez réessayer.');
            })
            .finally(() => {
                // Réactiver le bouton
                submitBtn.disabled = false;
                submitText.textContent = 'Finaliser la réservation';
                submitSpinner.classList.add('hidden');
            });
        });
    </script>

    <!-- Chat Box -->
    <div id="chat-container" class="fixed bottom-4 right-4 z-50">
        <!-- Message d'invitation (affiché temporairement) -->
        <div id="invitation-bubble" class="absolute bottom-20 right-0 bg-gradient-to-r from-white to-gray-50 rounded-xl shadow-2xl p-6 w-80 transform scale-0 transition-all duration-500 border border-gray-100 backdrop-blur-sm">
            <!-- Bouton de fermeture -->
            <button id="close-invitation" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 transition-colors" onclick="closeInvitation()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            
            <!-- Header avec avatar et statut -->
            <div class="flex items-center justify-center mb-4">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-red-100 shadow-lg mb-2">
                        <img src="{{ asset('images/avatar.png') }}" alt="Assistant Mami" class="w-full h-full object-cover">
                    </div>
                    <div class="flex items-center">
                        <h4 class="text-base font-bold text-gray-800 mr-2">Mima</h4>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-1 animate-pulse"></span>
                            En ligne
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Message principal -->
            <div class="text-center">
                <p class="text-lg font-medium text-gray-800 mb-2">Bonjour ! 👋</p>
                <p class="text-sm text-gray-600 mb-4">Besoin d'aide pour votre transport vers l'aéroport ? Je suis là pour vous accompagner !</p>
                <div class="flex items-center justify-center text-sm text-red-600 font-medium cursor-pointer hover:text-red-700 transition-colors">
                    <i class="fas fa-comment-dots mr-2"></i>
                    Cliquez pour discuter
                    <i class="fas fa-arrow-right ml-2 animate-bounce"></i>
                </div>
            </div>
            
            <!-- Petite flèche pointant vers le bouton -->
            <div class="absolute bottom-0 right-6 transform translate-y-1/2 rotate-45 w-4 h-4 bg-gradient-to-r from-white to-gray-50 border-r border-b border-gray-100 shadow-md"></div>
        </div>

        <!-- Chat Button -->
        <button id="chat-button" class="bg-red-600 text-white rounded-full p-1 shadow-lg hover:bg-red-700 transition-all duration-300 flex items-center justify-center w-14 h-14 animate-pulse">
            <img src="{{ asset('images/avatar.png') }}" alt="Assistant Mami" class="w-12 h-12 rounded-full object-cover border-2 border-white">
        </button>

        <!-- Chat Window -->
        <div id="chat-window" class="hidden fixed bottom-20 right-4 w-80 bg-white rounded-lg shadow-xl border border-gray-200 max-h-96">
            <!-- Chat Header -->
            <div class="bg-red-600 text-white p-3 rounded-t-lg flex justify-between items-center">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full overflow-hidden mr-2 border border-white">
                        <img src="{{ asset('images/avatar.png') }}" alt="Assistant Mami" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h3 class="font-bold text-sm">Mami</h3>
                        <p class="text-xs text-red-100">Assistant virtuel</p>
                    </div>
                </div>
                <button id="close-chat" class="text-white hover:text-red-200 text-sm">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Chat Messages -->
            <div id="chat-messages" class="p-3 h-32 overflow-y-auto bg-gray-50">
                <!-- Initial message will be added here -->
            </div>

            <!-- Quick Questions -->
            <div id="quick-questions" class="border-t border-gray-200 p-3 max-h-48 overflow-y-auto">
                <p class="text-xs text-gray-600 mb-2 font-medium">Choisissez une question :</p>
                <div class="space-y-1" id="questions-container">
                    <!-- Questions initiales -->
                    <button class="quick-question-btn bg-gray-100 hover:bg-red-50 text-gray-700 p-2 rounded-md text-xs text-left transition-colors w-full flex items-center" data-category="reservation">
                        <i class="fas fa-car text-red-500 mr-2 text-xs"></i>
                        Comment réserver un transport ?
                    </button>
                    <button class="quick-question-btn bg-gray-100 hover:bg-red-50 text-gray-700 p-2 rounded-md text-xs text-left transition-colors w-full flex items-center" data-category="info">
                        <i class="fas fa-info-circle text-red-500 mr-2 text-xs"></i>
                        Informations générales
                    </button>
                </div>
                
                <!-- Bouton pour saisir une question personnalisée -->
                <button id="custom-question-btn" class="mt-2 w-full bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-md text-xs text-center transition-colors flex items-center justify-center">
                    <i class="fas fa-edit mr-2 text-xs"></i>
                    Poser ma propre question
                </button>
            </div>

            <!-- Zone de saisie personnalisée (cachée par défaut) -->
            <div id="custom-input-section" class="border-t p-3 hidden">
                <form id="chat-form" class="flex items-center">
                    <input type="text" id="chat-input" 
                           class="flex-1 border rounded-l-md px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-red-500" 
                           placeholder="Tapez votre question...">
                    <button type="submit" 
                            class="bg-red-600 text-white px-3 py-1 rounded-r-md hover:bg-red-700 transition-colors text-xs">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
                <button id="back-to-questions" class="mt-2 text-xs text-gray-500 hover:text-gray-700">
                    ← Retour aux questions
                </button>
            </div>
        </div>
    </div>

    <script>
        // Chat functionality
        const chatButton = document.getElementById('chat-button');
        const chatWindow = document.getElementById('chat-window');
        const closeChat = document.getElementById('close-chat');
        const chatMessages = document.getElementById('chat-messages');
        const questionsContainer = document.getElementById('questions-container');
        const customQuestionBtn = document.getElementById('custom-question-btn');
        const customInputSection = document.getElementById('custom-input-section');
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');
        const backToQuestionsBtn = document.getElementById('back-to-questions');
        const invitationBubble = document.getElementById('invitation-bubble');

        // Questions contextuelles par catégorie
        const contextualQuestions = {
            'reservation': [
                { text: 'Quels sont vos tarifs ?', icon: 'fas fa-money-bill-wave', category: 'tarifs' },
                { text: 'Horaires et disponibilités', icon: 'fas fa-clock', category: 'horaires' },
                { text: 'Comment vous contacter ?', icon: 'fas fa-phone', category: 'contact' }
            ],
            'info': [
                { text: 'Quels services proposez-vous ?', icon: 'fas fa-concierge-bell', category: 'services' },
                { text: 'Zones de service couvertes', icon: 'fas fa-map-marker-alt', category: 'zones' },
                { text: 'Mesures de sécurité', icon: 'fas fa-shield-alt', category: 'securite' }
            ],
            'tarifs': [
                { text: 'Modes de paiement acceptés', icon: 'fas fa-credit-card', category: 'paiement' },
                { text: 'Comment réserver ?', icon: 'fas fa-car', category: 'reservation' }
            ],
            'services': [
                { text: 'Nos tarifs', icon: 'fas fa-money-bill-wave', category: 'tarifs' },
                { text: 'Comment réserver ?', icon: 'fas fa-car', category: 'reservation' }
            ]
        };

        // Predefined responses with keywords and detailed responses
        const responses = {
            'greeting': {
                keywords: ['bonjour', 'bonsoir', 'salut', 'hey', 'hello', 'hi', 'coucou'],
                response: `Bonjour ! Je suis Mami, votre assistante virtuelle. 
Choisissez une question ci-dessous pour obtenir des informations détaillées !`
            },
            'reservation': {
                keywords: ['réserver', 'reservation', 'comment réserver', 'transport'],
                response: `Je peux vous aider à réserver votre transfert ! Voici les différentes options :

1. 📱 Réservation en ligne :
   - Remplissez notre formulaire en ligne sur cette page
   - Choisissez votre trajet et vos horaires
   - Recevez une confirmation immédiate

2. ☎️ Par téléphone :
   - Appelez-nous au +221 77 705 67 67
   - Disponible 24h/24 et 7j/7

3. 💬 Via WhatsApp :
   - Contactez-nous au +221 77 705 69 69
   - Service rapide et personnalisé`
            },
            'info': {
                keywords: ['informations', 'générales', 'info'],
                response: `Voici les informations essentielles sur nos services :

🚗 Nous sommes spécialisés dans le transport vers l'aéroport AIBD
✈️ Service disponible 24h/24 et 7j/7
🏆 Leader du transfert aéroportuaire au Sénégal
👨‍✈️ Chauffeurs professionnels et expérimentés
🛡️ Véhicules entretenus et assurés

Que souhaitez-vous savoir de plus spécifique ?`
            },
            'tarifs': {
                keywords: ['tarifs', 'voir les tarifs', 'prix', 'coût'],
                response: `Voici nos différentes formules de transport :

🌟 Transfert AIBD VIP (45 000 FCFA)
- Navette privée haut de gamme
- Wifi et confort premium
- Bagages inclus (2 par personne)

✨ Transfert AIBDP (32 500 FCFA)
- Service privé confortable
- Jusqu'à 3 personnes
- Disponible 24h/24

👑 Transfert PREM/Meet & Greet (65 000 FCFA)
- Service conciergerie complet
- Accueil personnalisé
- Véhicule haut de gamme`
            },
            'horaires': {
                keywords: ['horaires', 'disponibilités', 'heures'],
                response: `Nos services sont organisés pour votre confort :

🕒 Service de transfert :
- Disponible 24h/24 et 7j/7
- Réservation possible à toute heure
- Adaptable à vos horaires de vol

🏢 Bureau d'accueil :
- Ouvert du lundi au samedi
- De 8h à 18h
- Service client disponible`
            },
            'services': {
                keywords: ['services', 'services proposés', 'proposez-vous'],
                response: `Découvrez notre gamme complète de services :

🚗 Transport aéroport
- Transferts privés vers AIBD
- Service VIP avec conciergerie
- Navettes sur mesure

🌟 Services Premium
- Meet & Greet à l'aéroport
- Assistance bagages
- Suivi de vol en temps réel

🏢 Services Entreprises
- Transport professionnel
- Contrats corporate
- Solutions sur mesure`
            },
            'contact': {
                keywords: ['contact', 'nous contacter', 'joindre'],
                response: `Vous pouvez nous joindre facilement :

📞 Téléphone : +221 77 705 67 67
📱 WhatsApp : +221 77 705 69 69
📧 Email : 221cproservices@gmail.com

📍 Adresse : Sacré-Cœur, Dakar, Sénégal

Notre équipe est disponible pour :
- Réservations
- Informations
- Devis personnalisés
- Service client`
            },
            'paiement': {
                keywords: ['paiement', 'modes de paiement', 'acceptés'],
                response: `Nous acceptons plusieurs modes de paiement :

💳 Options de paiement :
- Espèces (FCFA)
- Carte bancaire
- Virement bancaire
- Wave
- Orange Money

🔒 Paiement sécurisé garanti
💰 Possibilité de payer à bord ou à l'avance`
            },
            'zones': {
                keywords: ['zones', 'service couvertes', 'destinations'],
                response: `Nos zones de service incluent :

🏙️ Dakar et banlieue :
- Tous les quartiers de Dakar
- Pikine, Guédiawaye
- Rufisque, Bargny

🏖️ Destinations touristiques :
- Saly Portudal
- Mbour, Somone
- Joal-Fadiouth

✈️ Aéroport AIBD :
- Transferts aller-retour
- Service 24h/24
- Suivi des vols`
            },
            'securite': {
                keywords: ['sécurité', 'mesures', 'sûreté'],
                response: `Votre sécurité est notre priorité :

🚗 Véhicules :
- Entretien régulier et contrôles techniques
- Assurance tous risques
- Équipements de sécurité à bord

👨‍✈️ Chauffeurs :
- Formation professionnelle
- Vérification des antécédents
- Conduite défensive

🛡️ Protocoles :
- Respect du code de la route
- Vitesse contrôlée
- Suivi GPS en temps réel`
            }
        };

        // Add initial message when chat opens
        function addInitialMessage() {
            chatMessages.innerHTML = '';
            addMessage(responses.greeting.response, 'bot');
            showInitialQuestions();
        }

        // Afficher les questions initiales
        function showInitialQuestions() {
            questionsContainer.innerHTML = `
                <button class="quick-question-btn bg-gray-100 hover:bg-red-50 text-gray-700 p-2 rounded-md text-xs text-left transition-colors w-full flex items-center" data-category="reservation">
                    <i class="fas fa-car text-red-500 mr-2 text-xs"></i>
                    Comment réserver un transport ?
                </button>
                <button class="quick-question-btn bg-gray-100 hover:bg-red-50 text-gray-700 p-2 rounded-md text-xs text-left transition-colors w-full flex items-center" data-category="info">
                    <i class="fas fa-info-circle text-red-500 mr-2 text-xs"></i>
                    Informations générales
                </button>
            `;
            attachQuestionListeners();
        }

        // Afficher les questions contextuelles
        function showContextualQuestions(category) {
            const questions = contextualQuestions[category] || [];
            let questionsHtml = '';
            
            questions.forEach(question => {
                questionsHtml += `
                    <button class="quick-question-btn bg-gray-100 hover:bg-red-50 text-gray-700 p-2 rounded-md text-xs text-left transition-colors w-full flex items-center" data-category="${question.category}">
                        <i class="${question.icon} text-red-500 mr-2 text-xs"></i>
                        ${question.text}
                    </button>
                `;
            });

            // Ajouter un bouton pour revenir aux questions principales
            questionsHtml += `
                <button id="back-to-main" class="mt-2 w-full bg-gray-200 hover:bg-gray-300 text-gray-700 p-2 rounded-md text-xs text-center transition-colors">
                    ← Questions principales
                </button>
            `;

            questionsContainer.innerHTML = questionsHtml;
            attachQuestionListeners();
            
            // Attacher l'événement pour revenir aux questions principales
            document.getElementById('back-to-main')?.addEventListener('click', showInitialQuestions);
        }

        // Attacher les événements aux boutons de questions
        function attachQuestionListeners() {
            document.querySelectorAll('.quick-question-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const questionText = button.textContent.trim();
                    const category = button.getAttribute('data-category');
                    
                    addMessage(questionText, 'user');
                    
                    // Trouver la réponse correspondante
                    let response = responses[category]?.response || responses.greeting.response;
                    
                    setTimeout(() => {
                        addMessage(response, 'bot');
                        // Afficher les questions contextuelles après la réponse
                        if (contextualQuestions[category]) {
                            showContextualQuestions(category);
                        }
                    }, 500);
                });
            });
        }

        // Toggle chat window
        chatButton.addEventListener('click', () => {
            chatWindow.classList.toggle('hidden');
            if (!chatWindow.classList.contains('hidden')) {
                addInitialMessage();
                customInputSection.classList.add('hidden');
                document.getElementById('quick-questions').classList.remove('hidden');
                
                // Cacher la bulle d'invitation et arrêter les animations
                invitationBubble.classList.remove('show', 'float-animation');
                chatButton.classList.remove('pulse-glow', 'bounce-attention', 'animate-pulse');
            }
            chatButton.classList.toggle('rotate-180');
        });

        closeChat.addEventListener('click', () => {
            chatWindow.classList.add('hidden');
            chatButton.classList.remove('rotate-180');
            // Remettre l'animation de pulsation normale
            chatButton.classList.add('animate-pulse');
        });

        // Permettre de cliquer sur la bulle d'invitation pour ouvrir le chat
        invitationBubble.addEventListener('click', () => {
            if (chatWindow.classList.contains('hidden')) {
                chatButton.click(); // Simule un clic sur le bouton de chat
            }
        });

        // Gestion du bouton pour saisir une question personnalisée
        customQuestionBtn.addEventListener('click', () => {
            document.getElementById('quick-questions').classList.add('hidden');
            customInputSection.classList.remove('hidden');
            chatInput.focus();
        });

        // Retour aux questions prédéfinies
        backToQuestionsBtn.addEventListener('click', () => {
            customInputSection.classList.add('hidden');
            document.getElementById('quick-questions').classList.remove('hidden');
            chatInput.value = '';
        });

        // Gestion du formulaire de saisie personnalisée
        chatForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const message = chatInput.value.trim();
            if (!message) return;

            addMessage(message, 'user');
            chatInput.value = '';

            setTimeout(() => {
                const response = getBotResponse(message.toLowerCase());
                addMessage(response, 'bot');
            }, 500);
        });

        // Fonction pour obtenir une réponse basée sur les mots-clés
        function getBotResponse(message) {
            for (const category in responses) {
                if (responses[category].keywords && responses[category].keywords.some(keyword => message.includes(keyword))) {
                    return responses[category].response;
                }
            }
            return `Je comprends votre question, mais je n'ai pas d'information spécifique à ce sujet. 
            
Voici ce que je peux vous aider :
- Réservations de transport
- Informations sur nos tarifs
- Horaires et disponibilités
- Nos services
- Coordonnées de contact

N'hésitez pas à choisir une question dans la liste ou à reformuler votre demande !`;
        }

        function addMessage(message, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex mb-3 ' + (sender === 'user' ? 'justify-end' : '');

            if (sender === 'bot') {
                messageDiv.innerHTML = `
                    <div class="w-6 h-6 rounded-full overflow-hidden mr-2 flex-shrink-0 mt-1 border border-gray-200">
                        <img src="{{ asset('images/avatar.png') }}" alt="Assistant Mami" class="w-full h-full object-cover">
                    </div>
                    <div class="bg-white rounded-lg p-2 max-w-[85%] shadow-sm border">
                        <p class="text-xs leading-relaxed whitespace-pre-line">${message}</p>
                    </div>
                `;
            } else {
                messageDiv.innerHTML = `
                    <div class="bg-red-600 text-white rounded-lg p-2 max-w-[85%]">
                        <p class="text-xs">${message}</p>
                    </div>
                `;
            }

            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Initialiser les événements des questions au chargement
        attachQuestionListeners();

        // Animation d'invitation automatique au chargement de la page
        function showWelcomeInvitation() {
            // Attendre 2 secondes après le chargement de la page
            setTimeout(() => {
                // Afficher la bulle d'invitation avec animation de flottement
                invitationBubble.classList.add('show', 'float-animation');
                
                // Ajouter l'animation de pulsation avec lueur
                chatButton.classList.remove('animate-pulse');
                chatButton.classList.add('pulse-glow');
                
                // Après 5 secondes, faire une animation d'attention supplémentaire
                setTimeout(() => {
                    chatButton.classList.add('bounce-attention');
                    
                    // Retirer l'animation après 4 secondes
                    setTimeout(() => {
                        chatButton.classList.remove('bounce-attention');
                    }, 4000);
                }, 5000);
                
                // Cacher la bulle après 10 secondes si l'utilisateur n'a pas cliqué
                setTimeout(() => {
                    if (chatWindow.classList.contains('hidden')) {
                        invitationBubble.classList.remove('show', 'float-animation');
                        chatButton.classList.remove('pulse-glow');
                        chatButton.classList.add('animate-pulse');
                    }
                }, 10000);
                
            }, 2000);
        }

        // Déclencher l'invitation dès que la page est chargée
        document.addEventListener('DOMContentLoaded', showWelcomeInvitation);

        // Si la page est déjà chargée, déclencher immédiatement
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', showWelcomeInvitation);
        } else {
            showWelcomeInvitation();
        }

        // Gestion du mode sombre
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            const languageBtn = document.getElementById('language-btn');
            const languageDropdown = document.getElementById('language-dropdown');
            const currentLanguage = document.getElementById('current-language');
            
            // Charger le thème sauvegardé
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);
            
            // Toggle du thème
            themeToggle.addEventListener('click', () => {
                const currentTheme = document.documentElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeIcon(newTheme);
            });
            
            function updateThemeIcon(theme) {
                if (theme === 'dark') {
                    themeIcon.className = 'fas fa-sun';
                } else {
                    themeIcon.className = 'fas fa-moon';
                }
            }
            
            // Gestion du sélecteur de langue
            const languages = {
                'fr': { code: 'FR', flag: '🇫🇷', name: 'Français' },
                'en': { code: 'EN', flag: '🇬🇧', name: 'English' }
            };
            
            // Charger la langue sauvegardée
            const savedLanguage = localStorage.getItem('language') || 'fr';
            updateLanguageDisplay(savedLanguage);
            
            // Toggle du dropdown de langue
            languageBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                languageDropdown.classList.toggle('hidden');
            });
            
            // Fermer le dropdown en cliquant à l'extérieur
            document.addEventListener('click', () => {
                languageDropdown.classList.add('hidden');
            });
            
            // Sélection de langue
            document.querySelectorAll('.language-option').forEach(option => {
                option.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const selectedLang = option.getAttribute('data-lang');
                    localStorage.setItem('language', selectedLang);
                    updateLanguageDisplay(selectedLang);
                    languageDropdown.classList.add('hidden');
                    
                    // Ici vous pouvez ajouter la logique de traduction
                    translatePage(selectedLang);
                });
            });
            
            function updateLanguageDisplay(lang) {
                const langData = languages[lang];
                if (langData) {
                    currentLanguage.textContent = langData.code;
                }
            }
            
            // Dictionnaire de traductions étendu
            const fullTranslations = {
                'en': {
                    // Navigation
                    'Accueil': 'Home',
                    'Actualités': 'News',
                    'Tarifs': 'Rates',
                    'Services': 'Services',
                    'Réservation': 'Booking',
                    'Contact': 'Contact',
                    'Mon compte': 'My Account',
                    
                    // Hero Section
                    'Nous sommes le Leader du Transfert/Shuttle Aéroportuaire': 'We are the Leader in Airport Transfer/Shuttle',
                    'Service de navette, location de voiture avec chauffeur et Transferts privés vers l\'Aéroport International Blaise Diagne(AIBD).': 'Shuttle service, car rental with driver and private transfers to Blaise Diagne International Airport (AIBD).',
                    'Réserver maintenant': 'Book now',
                    'Réserver votre trajet': 'Book your trip',
                    'Sens du trajet': 'Trip direction',
                    'Date': 'Date',
                    'Heure': 'Time',
                    'Vérifier disponibilité': 'Check availability',
                    'Dakar - AIBD': 'Dakar - AIBD',
                    'AIBD - Dakar': 'AIBD - Dakar',
                    'AIBD - Saly': 'AIBD - Saly',
                    'Saly - AIBD': 'Saly - AIBD',
                    
                    // Actualités
                    'Dernières actualités': 'Latest news',
                    'Restez informé de nos actualités': 'Stay informed of our news',
                    'Voir moins': 'See less',
                    'Voir plus': 'See more',
                    'En savoir plus': 'Learn more',
                    
                    // Tarifs
                    'Nos Tarifs': 'Our Rates',
                    'Des prix transparents et compétitifs pour tous nos services': 'Transparent and competitive prices for all our services',
                    'Transfert AIBD VIP': 'AIBD VIP Transfer',
                    'Transfert AIBDP': 'AIBDP Transfer',
                    'Transfert PREM/(Meet & Greet)': 'PREM Transfer/(Meet & Greet)',
                    'trajet': 'trip',
                    'Navette privée': 'Private shuttle',
                    'Disponible 24h/24': 'Available 24/7',
                    'Wifi à bord': 'Wifi on board',
                    'Bagages inclus (2 par personne)': 'Luggage included (2 per person)',
                    'Jusqu\'à 3 personnes': 'Up to 3 people',
                    'Tout confort inclus': 'All comfort included',
                    'Véhicule haut de gamme': 'High-end vehicle',
                    'Chauffeur professionnel': 'Professional driver',
                    'Conciergerie (Meet & Greet)': 'Concierge (Meet & Greet)',
                    'Réserver': 'Book',
                    
                    // Services
                    'Nos Services': 'Our Services',
                    'Nous offrons des solutions de transport adaptées à tous vos besoins vers l\'aéroport AIBD et au-delà': 'We offer transport solutions adapted to all your needs to AIBD airport and beyond',
                    'Transferts Aéroport': 'Airport Transfers',
                    'Flotte exclusive de Vans.': 'Exclusive fleet of Vans.',
                    'Sécurité : Chauffeurs expérimentés, vitesse controlée': 'Safety: Experienced drivers, controlled speed',
                    'Confort : clim / wifi / eau à bord': 'Comfort: A/C / wifi / water on board',
                    'Espace : jusqu\'à 6 personnes et 12 valises': 'Space: up to 6 people and 12 suitcases',
                    'Services aux Entreprises': 'Corporate Services',
                    'Solutions de transport professionnelles pour les besoins de mobilité de votre entreprise.': 'Professional transport solutions for your company\'s mobility needs.',
                    'Transferts d\'employés': 'Employee transfers',
                    'Références compagnies internationales': 'International company references',
                    'Partenaire AIBD': 'AIBD Partner',
                    'Assistance & Conciergerie': 'Assistance & Concierge',
                    'Service de location avec chauffeur pour des trajets longue distance en dehors de Dakar.': 'Rental service with driver for long distance trips outside Dakar.',
                    'Suivi et informations de vol': 'Flight tracking and information',
                    'Accueil et Facilitation de passage': 'Welcome and facilitation of passage',
                    'Récupération valises / Réclamations': 'Baggage recovery / Claims',
                    
                    // Services annexes
                    'Services annexes': 'Additional Services',
                    'Location voiture': 'Car rental',
                    '/Dakar': '/Dakar',
                    '/hors Dakar': '/outside Dakar',
                    'Jour': 'Day',
                    'jour': 'day',
                    'Hors carburant': 'Excluding fuel',
                    'Climatisation': 'Air conditioning',
                    'Circulez partout à Dakar': 'Drive anywhere in Dakar',
                    'Circulez partout': 'Drive anywhere',
                    'Tout confort compris': 'All comfort included',
                    'Les tarifs peuvent varier selon la distance et le nombre de passagers.': 'Rates may vary according to distance and number of passengers.',
                    'Contactez-nous pour un devis personnalisé': 'Contact us for a personalized quote',
                    
                    // Section Entreprises
                    'Solutions de transport pour entreprises': 'Corporate transport solutions',
                    'Confiez-nous les déplacements professionnels de vos collaborateurs et bénéficiez d\'un service sur mesure, fiable et sécurisé.': 'Entrust us with your employees\' business travel and benefit from a tailor-made, reliable and secure service.',
                    'Gestion simplifiée': 'Simplified management',
                    'Une seule interface pour gérer tous les trajets de vos employés avec facturation centralisée.': 'A single interface to manage all your employees\' trips with centralized billing.',
                    'Chauffeurs sélectionnés': 'Selected drivers',
                    'Nos chauffeurs professionnels sont formés aux standards les plus exigeants.': 'Our professional drivers are trained to the most demanding standards.',
                    'Parc véhicules diversifié': 'Diversified vehicle fleet',
                    'Des berlines aux minibus, nous avons le véhicule adapté à chaque besoin.': 'From sedans to minibuses, we have the right vehicle for every need.',
                    'Demander un devis': 'Request a quote',
                    'Avantages pour les entreprises': 'Corporate benefits',
                    'Tarifs préférentiels': 'Preferential rates',
                    'Des réductions volume pour les clients professionnels.': 'Volume discounts for professional clients.',
                    'Facturation mensuelle': 'Monthly billing',
                    'Simplifiez votre gestion administrative.': 'Simplify your administrative management.',
                    'Service dédié': 'Dedicated service',
                    'Un interlocuteur unique pour vos réservations.': 'A single contact for your bookings.',
                    'Reporting complet': 'Complete reporting',
                    'Analysez et optimisez vos dépenses de transport.': 'Analyze and optimize your transport expenses.',
                    
                    // CTA Banner
                    'Vous voyagez bientôt ? Réservez dès maintenant !': 'Traveling soon? Book now!',
                    'Évitez les mauvaises surprises et garantissez votre transport vers l\'aéroport AIBD en réservant à l\'avance. Nos chauffeurs professionnels vous attendront à l\'heure convenue, peu importe votre destination de départ.': 'Avoid unpleasant surprises and guarantee your transport to AIBD airport by booking in advance. Our professional drivers will be waiting for you at the agreed time, regardless of your departure destination.',
                    'Faire une réservation': 'Make a booking',
                    
                    // Réservation Section
                    'Réservez votre transport en quelques clics': 'Book your transport in a few clicks',
                    'Notre plateforme simple et intuitive vous permet de réserver votre transport vers l\'aéroport AIBD en moins de 2 minutes.': 'Our simple and intuitive platform allows you to book your transport to AIBD airport in less than 2 minutes.',
                    'Réservation instantanée': 'Instant booking',
                    'Confirmation immédiate de votre réservation par email et SMS.': 'Immediate confirmation of your booking by email and SMS.',
                    'Paiement sécurisé': 'Secure payment',
                    'Payez en ligne de manière sécurisée ou en espèces au chauffeur.': 'Pay online securely or in cash to the driver.',
                    'Suivi en temps réel': 'Real-time tracking',
                    'Suivez votre chauffeur en temps réel grâce à notre application mobile.': 'Track your driver in real time with our mobile app.',
                    'Complétez votre réservation': 'Complete your booking',
                    'Prénom': 'First name',
                    'Nom': 'Last name',
                    'Email': 'Email',
                    'Téléphone': 'Phone',
                    'Point de départ': 'Departure point',
                    'Nombre de passagers': 'Number of passengers',
                    'Heure de ramassage': 'Pickup time',
                    'Nombre de valises': 'Number of suitcases',
                    'Sens du trajet': 'Trip direction',
                    '-- Sélectionner un trajet --': '-- Select a trip --',
                    'Tarif estimé': 'Estimated rate',
                    'Finaliser la réservation': 'Complete booking',
                    'Réservation confirmée !': 'Booking confirmed!',
                    'Vous recevrez une confirmation par email et SMS dans quelques minutes.': 'You will receive a confirmation by email and SMS within a few minutes.',
                    
                    // Section Compte
                    'Créez votre compte client': 'Create your customer account',
                    'Accédez à toutes vos réservations passées et futures, consultez votre historique de trajets et vos points de fidélité pour gagner des avantages.': 'Access all your past and future bookings, view your trip history and loyalty points to earn benefits.',
                    'Gestion des réservations': 'Booking management',
                    'Consultez, modifiez ou annulez facilement vos réservations.': 'Easily view, modify or cancel your bookings.',
                    'Historique complet': 'Complete history',
                    'Retrouvez tous vos trajets passés avec les détails et factures.': 'Find all your past trips with details and invoices.',
                    'Notation des chauffeurs': 'Driver rating',
                    'Évaluez votre expérience pour nous aider à améliorer nos services.': 'Rate your experience to help us improve our services.',
                    'Créer un compte': 'Create an account',
                    'Se connecter': 'Sign in',
                    
                    // Section Rating
                    'Comment s\'est passé votre trajet ?': 'How was your trip?',
                    'Votre avis compte ! Notez votre chauffeur et partagez votre expérience pour nous aider à améliorer continuellement nos services.': 'Your opinion matters! Rate your driver and share your experience to help us continuously improve our services.',
                    'moyenne': 'average',
                    'Noter un chauffeur': 'Rate a driver',
                    'Numéro de réservation': 'Booking number',
                    'Ex: DKR123456': 'Ex: DKR123456',
                    'Note (1 à 5 étoiles)': 'Rating (1 to 5 stars)',
                    'Commentaire (optionnel)': 'Comment (optional)',
                    'Décrivez votre expérience...': 'Describe your experience...',
                    'Envoyer l\'évaluation': 'Send evaluation',
                    
                    // Contact Section
                    'Contactez-nous': 'Contact us',
                    'Nous sommes disponibles 24h/24 pour répondre à vos questions et prendre vos réservations': 'We are available 24/7 to answer your questions and take your bookings',
                    'Nos coordonnées': 'Our contact details',
                    'Adresse': 'Address',
                    'Sacré cœur, Dakar, Sénégal': 'Sacré cœur, Dakar, Senegal',
                    'Horaires': 'Hours',
                    
                    // Contact
                    'Contactez-nous': 'Contact us',
                    'Adresse': 'Address',
                    'Téléphone': 'Phone',
                    // Messages et formulaires
                    'Veuillez remplir tous les champs.': 'Please fill in all fields.',
                    'Vérification en cours...': 'Checking...',
                    'Erreur lors de la vérification. Veuillez réessayer.': 'Error during verification. Please try again.',
                    'Réservation en cours...': 'Booking in progress...',
                    'Une erreur est survenue. Veuillez réessayer.': 'An error occurred. Please try again.',
                    
                    // Chatbox
                    'Bonjour ! 👋': 'Hi there! 👋',
                    'Besoin d\'aide pour votre transport vers l\'aéroport ? Je suis là pour vous accompagner !': 'Need help with your airport transport? I\'m here to assist you!',
                    'Cliquez pour discuter': 'Click to chat',
                    'Assistant virtuel': 'Virtual assistant',
                    'Choisissez une question :': 'Choose a question:',
                    'Comment réserver un transport ?': 'How to book transport?',
                    'Informations générales': 'General information',
                    'Poser ma propre question': 'Ask my own question',
                    'Tapez votre question...': 'Type your question...',
                    'Retour aux questions': '← Back to questions',
                    'Questions principales': 'Main questions',
                    'Quels sont vos tarifs ?': 'What are your rates?',
                    'Horaires et disponibilités': 'Hours and availability',
                    'Comment vous contacter ?': 'How to contact you?',
                    'Quels services proposez-vous ?': 'What services do you offer?',
                    'Zones de service couvertes': 'Service areas covered',
                    'Mesures de sécurité': 'Safety measures',
                    'Modes de paiement acceptés': 'Accepted payment methods'
                },
                'es': {
                    // Navigation
                    'Accueil': 'Inicio',
                    'Actualités': 'Noticias',
                    'Tarifs': 'Tarifas',
                    'Services': 'Servicios',
                    'Réservation': 'Reserva',
                    'Contact': 'Contacto',
                    'Mon compte': 'Mi Cuenta',
                    
                    // Contenido
                    'Nous sommes le Leader du Transfert/Shuttle Aéroportuaire': 'Somos el Líder en Traslados/Shuttle Aeroportuario',
                    'Réserver maintenant': 'Reservar ahora',
                    'Nos Tarifs': 'Nuestras Tarifas',
                    'Nos Services': 'Nuestros Servicios',
                    'Contactez-nous': 'Contáctanos'
                },
                'it': {
                    // Navigation
                    'Accueil': 'Home',
                    'Actualités': 'Notizie',
                    'Tarifs': 'Tariffe',
                    'Services': 'Servizi',
                    'Réservation': 'Prenotazione',
                    'Contact': 'Contatto',
                    'Mon compte': 'Il Mio Account',
                    
                    // Contenido
                    'Nous sommes le Leader du Transfert/Shuttle Aéroportuaire': 'Siamo il Leader nei Trasferimenti/Shuttle Aeroportuali',
                    'Réserver maintenant': 'Prenota ora',
                    'Nos Tarifs': 'Le Nostre Tariffe',
                    'Nos Services': 'I Nostri Servizi',
                    'Contactez-nous': 'Contattaci'
                }
            };
            
            // Fonction améliorée de traduction complète
            function translateFullPage(lang) {
                if (lang === 'fr') {
                    location.reload();
                    return;
                }
                
                const translations = fullTranslations[lang];
                if (!translations) return;
                
                // Fonction pour traduire récursivement tous les nœuds texte
                function walkAndTranslate(node) {
                    if (node.nodeType === Node.TEXT_NODE) {
                        const text = node.textContent.trim();
                        if (text && translations[text]) {
                            node.textContent = translations[text];
                        }
                    } else if (node.nodeType === Node.ELEMENT_NODE) {
                        // Éviter de traduire certains éléments
                        if (node.classList.contains('no-translate') || 
                            node.id === 'current-language' ||
                            node.tagName === 'SCRIPT' ||
                            node.tagName === 'STYLE') {
                            return;
                        }
                        
                        // Traduire les attributs
                        if (node.hasAttribute('placeholder')) {
                            const placeholder = node.getAttribute('placeholder');
                            if (translations[placeholder]) {
                                node.setAttribute('placeholder', translations[placeholder]);
                            }
                        }
                        
                        // Continuer avec les enfants
                        for (let child of node.childNodes) {
                            walkAndTranslate(child);
                        }
                    }
                }
                
                // Démarrer la traduction depuis le body
                walkAndTranslate(document.body);
                
                // Traitement spécial pour les liens de navigation avec icônes
                document.querySelectorAll('.nav-link, .mobile-nav-link').forEach(link => {
                    const originalText = link.getAttribute('data-original-text') || link.textContent.trim();
                    if (!link.hasAttribute('data-original-text')) {
                        link.setAttribute('data-original-text', originalText);
                    }
                    
                    if (translations[originalText]) {
                        const icon = link.querySelector('i');
                        if (icon) {
                            link.innerHTML = icon.outerHTML + translations[originalText];
                        } else {
                            link.textContent = translations[originalText];
                        }
                    }
                });
                
                console.log(`Page entièrement traduite en ${languages[lang].name}`);
            }
            
            // Remplacer l'ancienne fonction par la nouvelle
            function translatePage(lang) {
                translateFullPage(lang);
                updateChatboxLanguage(lang);
            }
            
            // Fonction pour mettre à jour la langue du chatbox
            function updateChatboxLanguage(lang) {
                const translations = fullTranslations[lang];
                if (!translations) return;
                
                // Mettre à jour les textes du chatbox
                const invitationBubble = document.querySelector('#invitation-bubble p:first-of-type');
                if (invitationBubble && translations['Bonjour ! 👋']) {
                    invitationBubble.textContent = translations['Bonjour ! 👋'];
                }
                
                const invitationText = document.querySelector('#invitation-bubble p:nth-of-type(2)');
                if (invitationText && translations['Besoin d\'aide pour votre transport vers l\'aéroport ? Je suis là pour vous accompagner !']) {
                    invitationText.textContent = translations['Besoin d\'aide pour votre transport vers l\'aéroport ? Je suis là pour vous accompagner !'];
                }
                
                const clickText = document.querySelector('#invitation-bubble .text-red-600');
                if (clickText && translations['Cliquez pour discuter']) {
                    clickText.innerHTML = `<i class="fas fa-comment-dots mr-2"></i>${translations['Cliquez pour discuter']}<i class="fas fa-arrow-right ml-2 animate-bounce"></i>`;
                }
                
                // Mettre à jour le greeting message
                responses.greeting.response = lang === 'en' ? 
                    `Hello! I'm Mami, your virtual assistant.\nChoose a question below to get detailed information!` :
                    `Bonjour ! Je suis Mami, votre assistante virtuelle.\nChoisissez une question ci-dessous pour obtenir des informations détaillées !`;
                
                // Mettre à jour les réponses du chatbot selon la langue
                if (lang === 'en') {
                    updateChatResponsesToEnglish();
                } else {
                    updateChatResponsesToFrench();
                }
            }
            
            // Fonction pour mettre à jour les réponses en anglais
            function updateChatResponsesToEnglish() {
                responses.reservation.response = `I can help you book your transfer! Here are the different options:

1. 📱 Online booking:
   - Fill out our online form on this page
   - Choose your trip and schedule
   - Get immediate confirmation

2. ☎️ By phone:
   - Call us at +221 77 705 67 67
   - Available 24/7

3. 💬 Via WhatsApp:
   - Contact us at +221 77 705 69 69
   - Fast and personalized service`;

                responses.info.response = `Here's essential information about our services:

🚗 We specialize in transport to AIBD airport
✈️ Service available 24/7
🏆 Leader in airport transfers in Senegal
👨‍✈️ Professional and experienced drivers
🛡️ Maintained and insured vehicles

What would you like to know more specifically?`;

                responses.tarifs.response = `Here are our different transport packages:

🌟 AIBD VIP Transfer (45,000 FCFA)
- Premium private shuttle
- Wifi and premium comfort
- Luggage included (2 per person)

✨ AIBD Transfer (32,500 FCFA)
- Comfortable private service
- Up to 3 people
- Available 24/7

👑 PREM/Meet & Greet Transfer (65,000 FCFA)
- Complete concierge service
- Personalized welcome
- High-end vehicle`;

                responses.contact.response = `You can easily reach us:

📞 Phone: +221 77 705 67 67
📱 WhatsApp: +221 77 705 69 69
📧 Email: 221cproservices@gmail.com

📍 Address: Sacré-Cœur, Dakar, Senegal

Our team is available for:
- Bookings
- Information
- Personalized quotes
- Customer service`;
            }
            
            // Fonction pour mettre à jour les réponses en français
            function updateChatResponsesToFrench() {
                responses.reservation.response = `Je peux vous aider à réserver votre transfert ! Voici les différentes options :

1. 📱 Réservation en ligne :
   - Remplissez notre formulaire en ligne sur cette page
   - Choisissez votre trajet et vos horaires
   - Recevez une confirmation immédiate

2. ☎️ Par téléphone :
   - Appelez-nous au +221 77 705 67 67
   - Disponible 24h/24 et 7j/7

3. 💬 Via WhatsApp :
   - Contactez-nous au +221 77 705 69 69
   - Service rapide et personnalisé`;

                responses.info.response = `Voici les informations essentielles sur nos services :

🚗 Nous sommes spécialisés dans le transport vers l'aéroport AIBD
✈️ Service disponible 24h/24 et 7j/7
🏆 Leader du transfert aéroportuaire au Sénégal
👨‍✈️ Chauffeurs professionnels et expérimentés
🛡️ Véhicules entretenus et assurés

Que souhaitez-vous savoir de plus spécifique ?`;

                responses.tarifs.response = `Voici nos différentes formules de transport :

🌟 Transfert AIBD VIP (45 000 FCFA)
- Navette privée haut de gamme
- Wifi et confort premium
- Bagages inclus (2 par personne)

✨ Transfert AIBD (32 500 FCFA)
- Service privé confortable
- Jusqu'à 3 personnes
- Disponible 24h/24

👑 Transfert PREM/Meet & Greet (65 000 FCFA)
- Service conciergerie complet
- Accueil personnalisé
- Véhicule haut de gamme`;

                responses.contact.response = `Vous pouvez nous joindre facilement :

📞 Téléphone : +221 77 705 67 67
📱 WhatsApp : +221 77 705 69 69
📧 Email : 221cproservices@gmail.com

📍 Adresse : Sacré-Cœur, Dakar, Sénégal

Notre équipe est disponible pour :
- Réservations
- Informations
- Devis personnalisés
- Service client`;
            }
        });
    </script>
</body>
</html>