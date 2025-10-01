<?php declare(strict_types=1); ?>
<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-KZSXDK2G');</script>
        <!-- End Google Tag Manager -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dakar Transport - Services vers AIBD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
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

        /* Animation de défilement des partenaires */
        @keyframes scroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        .animate-scroll {
            animation: scroll 30s linear infinite;
        }

        .partners-scroll-container {
            position: relative;
            overflow: hidden;
        }

        .partners-scroll-track {
            display: flex;
            width: max-content;
        }

        .partners-group {
            display: flex;
            gap: 2rem;
        }

        .partner-item {
            flex-shrink: 0;
            width: 12rem;
        }

        @media (min-width: 768px) {
            .partner-item {
                width: 14rem;
            }
        }

        /* Pause au survol */
        .partners-scroll-container:hover .animate-scroll {
            animation-play-state: paused;
        }

        /* Espace au début et à la fin */
        .partners-scroll-container::before,
        .partners-scroll-container::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 4rem;
            z-index: 10;
            pointer-events: none;
        }

        .partners-scroll-container::before {
            left: 0;
            background: linear-gradient(to right, rgba(249, 250, 251, 1), rgba(249, 250, 251, 0));
        }

        .partners-scroll-container::after {
            right: 0;
            background: linear-gradient(to left, rgba(249, 250, 251, 1), rgba(249, 250, 251, 0));
        }
        }

        /* Styles pour les logos des partenaires */
        .partner-item {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
            width: 200px;
            height: 160px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .partner-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .partner-item img {
            display: block;
            margin: 0 auto;
            width: 160px;
            height: 120px;
            object-fit: contain;
        }

        /* Classe spécifique pour les logos des partenaires */
        .partner-logo {
            width: 150px !important;
            height: 80px !important;
            object-fit: contain;
            transition: all 0.3s ease;
        }

        /* Espacement entre les éléments partenaires */
        .partners-slide {
            gap: 2rem !important;
        }

        .partners-slide .partner-item {
            margin: 0 1rem;
        }

        /* Styles responsives pour les partenaires */
        @media (max-width: 1024px) {
            .partner-logo {
                width: 120px !important;
                height: 70px !important;
            }

            .partners-slide {
                gap: 1.5rem !important;
            }

            .partners-slide .partner-item {
                margin: 0 0.75rem;
            }

            .partner-item {
                width: 180px;
                height: 140px;
                padding: 1rem;
            }
        }

        @media (max-width: 768px) {
            .partner-logo {
                width: 100px !important;
                height: 60px !important;
            }

            .partners-slide {
                gap: 1rem !important;
                flex-wrap: wrap;
                justify-content: center;
            }

            .partners-slide .partner-item {
                margin: 0.5rem;
            }

            .partner-item {
                width: 150px;
                height: 120px;
                padding: 0.75rem;
            }
        }

        @media (max-width: 640px) {
            .partner-logo {
                width: 80px !important;
                height: 50px !important;
            }

            .partners-slide {
                gap: 0.75rem !important;
            }

            .partners-slide .partner-item {
                margin: 0.25rem;
            }

            .partner-item {
                width: 120px;
                height: 100px;
                padding: 0.5rem;
            }

            .partner-item p {
                font-size: 0.7rem;
                margin-top: 0.25rem;
            }
        }

        @media (max-width: 480px) {
            .partner-logo {
                width: 100px !important;
                height: 100px !important;
            }

            .partners-slide {
                gap: 0.5rem !important;
            }

            .partner-item {
                width: 100px;
                height: 220px;
                padding: 0.5rem;
                margin-bottom: 0.5rem;

            }

            .partner-item p {
                font-size: 0.65rem;
            }
        }

        .partner-item p {
            margin-top: 0.5rem;
            text-align: center;
            font-size: 0.75rem;
            font-weight: 500;
            color: #374151;
        }

        /* Styles pour le modal de réservation */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease-in-out;
        }

        .modal.active {
            display: flex;
            opacity: 1;
            visibility: visible;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: scale(0.7);
            transition: transform 0.3s ease-in-out;
            max-width: 90vw;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal.active .modal-content {
            transform: scale(1);
        }

        .modal-open {
            overflow: hidden;
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

        /* Styles pour le carousel des actualités */
        .actu-card {
            transition: all 0.3s ease;
        }

        .actu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            margin: auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .modal-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 12px 12px 0 0;
        }

        .modal-open {
            overflow: hidden;
        }

        /* Swiper Carousel Styles */
        .swiper-container {
            width: 100%;
            height: 100%;
            padding: 15px 0;
            overflow: hidden;
        }

        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: transparent;
            display: flex;
            justify-content: center;
            align-items: stretch;
            height: auto;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: #ef4444;
            background: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 18px;
        }

        .swiper-pagination-bullet {
            background: #ef4444;
        }

        .swiper-pagination-bullet-active {
            background: #ef4444;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .swiper-slide {
                padding: 0 10px;
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

        .navbar-scrolled .nav-link:hover {
            color: #970808cf !important;
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
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-KZSXDK2G');</script>
        <!-- End Google Tag Manager -->
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
                    <a href="#accueil" class="nav-link hover:text-red-600 transition">Accueil</a>
                    <a href="#actualites" class="nav-link hover:text-red-600 transition">Actualités</a>
                    <a href="#flotte" class="nav-link hover:text-red-600 transition">Flotte</a>
                    <a href="#tarifs" class="nav-link hover:text-red-600 transition">Tarifs</a>
                    <a href="#services" class="nav-link hover:text-red-600 transition">Services</a>
                    <a href="#reservation" class="nav-link hover:text-red-600 transition">Réservation</a>
                    <a href="#partenaires" class="nav-link hover:text-red-600 transition">Partenaires</a>
                    <a href="#contact" class="nav-link hover:text-red-600 transition">Contact</a>
                    @auth
                        <a href="{{ route('profile.edit') }}" class="nav-link hover:text-red-600 transition">Mon compte</a>
                    @else
                        <a href="#compte" class="nav-link hover:text-red-600 transition">Mon compte</a>
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
                <a href="#flotte" class="mobile-nav-link block text-gray-700 hover:text-red-600 hover:bg-red-50 px-3 py-2 rounded transition-colors">
                    <i class="fas fa-car mr-3"></i>Flotte
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
                            Vérifier la disponibilité
                        </button>
                    </form>

                    <div id="availability-result" class="mt-4 text-center font-semibold"></div>
                </div>
            </div>
        </div>
    </div>
</section>


    <!-- Section Infos en cards (avant les actualités) -->
    <section class="bg-white py-8" id="infos">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Infos utiles</h2>
                <p class="text-base text-gray-600">Les dernières informations importantes</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($infos as $info)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 cursor-pointer actu-card transform hover:scale-105 transition-all duration-300 w-full h-full flex flex-col info-card" data-info-id="{{ $info->id }}" data-info-title="{{ $info->title }}" data-info-content="{{ $info->content }}" data-info-category="{{ $info->category?->name ?? 'Non classé' }}" data-info-category-color="{{ $info->category?->color ?? '#3B82F6' }}" data-info-date="{{ $info->created_at->format('d/m/Y') }}" data-info-image="{{ $info->image ? asset('storage/' . $info->image) : '' }}" data-info-link="{{ $info->external_link }}">
                        @if($info->image)
                            <div class="relative h-40 flex-shrink-0">
                                <img src="{{ asset('storage/' . $info->image) }}"
                                     alt="{{ $info->title }}"
                                     class="w-full h-full object-cover">
                                <div class="absolute top-3 right-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white shadow-lg"
                                          style="background-color: {{ $info->category?->color ?? '#3B82F6' }}">
                                        {{ $info->category?->name ?? 'Non classé' }}
                                    </span>
                                </div>
                            </div>
                        @endif
                        <div class="p-4 flex-grow flex flex-col">
                            <h4 class="font-semibold text-gray-900 text-base mb-2 line-clamp-1">{{ $info->title }}</h4>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2 flex-grow">{{ Str::limit($info->content, 100) }}</p>
                            <div class="flex items-center justify-between mt-auto">
                                <span class="text-sm text-gray-500">{{ $info->created_at->format('d/m/Y') }}</span>
                            </div>
                            @if($info->external_link)
                                <div class="mt-4 text-center">
                                    <a href="{{ $info->external_link }}" target="_blank" onclick="event.stopPropagation();" class="text-blue-600 hover:text-blue-800 underline">
                                        Visiter le site
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    <!-- Main Content with Sidebar -->
            <!-- Actualités Carousel Section -->
    <section id="actualites" class="bg-white py-8">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Dernières actualités</h2>
                <p class="text-base text-gray-600">Restez informé de nos actualités</p>
            </div>

            <!-- Carousel Container -->
            <div class="relative">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        @foreach($actus->take(8) as $actu)
                            <div class="swiper-slide">
                                <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 cursor-pointer actu-card transform hover:scale-105 transition-all duration-300 w-full h-full flex flex-col"
                                     data-actu-id="{{ $actu->id }}"
                                     data-actu-title="{{ $actu->title }}"
                                     data-actu-content="{{ $actu->content }}"
                                     data-actu-category="{{ $actu->category?->name ?? 'Non classé' }}"
                                     data-actu-category-color="{{ $actu->category?->color ?? '#3B82F6' }}"
                                     data-actu-date="{{ $actu->created_at->format('d/m/Y') }}"
                                     data-actu-image="{{ $actu->image ? asset('storage/' . $actu->image) : '' }}"
                                     data-actu-link="{{ $actu->external_link }}">
                                    @if($actu->image)
                                        <div class="relative h-40 flex-shrink-0">
                                            <img src="{{ asset('storage/' . $actu->image) }}"
                                                 alt="{{ $actu->title }}"
                                                 class="w-full h-full object-cover">
                                            <div class="absolute top-3 right-3">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white shadow-lg"
                                                      style="background-color: {{ $actu->category?->color ?? '#3B82F6' }}">
                                                    {{ $actu->category?->name ?? 'Non classé' }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="p-4 flex-grow flex flex-col">
                                        <h4 class="font-semibold text-gray-900 text-base mb-2 line-clamp-1">{{ $actu->title }}</h4>
                                        <p class="text-gray-600 text-sm mb-3 line-clamp-2 flex-grow">{{ Str::limit($actu->content, 100) }}</p>
                                        <div class="flex items-center justify-between mt-auto">
                                            <span class="text-sm text-gray-500">{{ $actu->created_at->format('d/m/Y') }}</span>
                                            <span class="text-green-600 text-sm font-medium hover:text-green-800 transition-colors duration-200">Voir plus →</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Navigation arrows -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>

                <!-- Pagination dots -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

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
                        <span>Visiter le site</span>
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Flotte -->
    <section id="flotte" class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Notre Flotte de Véhicules</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Découvrez notre gamme complète de véhicules modernes et confortables pour tous vos besoins de transport</p>
            </div>

            <!-- Image principale avec overlay -->
            <div class="relative mb-16 rounded-2xl overflow-hidden shadow-2xl">
                <img src="{{ asset('images/flotte/banner.jpeg') }}" alt="Notre flotte de véhicules" class="w-full h-96 md:h-[500px] object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-red-900/80 to-transparent flex items-center">
                    <div class="text-white p-8 md:p-16">
                        <h3 class="text-3xl md:text-5xl font-bold mb-4">Une flotte moderne et diversifiée</h3>
                        <p class="text-xl md:text-2xl mb-6 max-w-2xl">Des véhicules robustes, spacieux et confortables, garants de votre sécurité</p>
                        <div class="flex flex-wrap gap-4">
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2">
                                <span class="text-white font-semibold">✓ Climatisation</span>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2">
                                <span class="text-white font-semibold">✓ GPS intégré</span>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2">
                                <span class="text-white font-semibold">✓ Entretien régulier</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grille des véhicules -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Véhicule 1 - Van de luxe -->
                <div class="group bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('images/flotte/business.jpeg') }}" alt="Van de luxe" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="p-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-2">Salon Business</h4>
                        <p class="text-gray-600 mb-4">Véhicule spacieux et élégant pour bien commencer votre voyage.</p>
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span><i class="fas fa-users mr-2"></i>Jusqu'à 4 passagers</span>
                            <span><i class="fas fa-suitcase mr-2"></i>Bagages inclus</span>
                        </div>
                        <div class="text-center">
                            <a href="#reservation" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-colors inline-block">
                                Réserver
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Véhicule 2 - Hyundai H1 -->
                <div class="group bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('images/flotte/hyundai-h-1-van-mod-image-1220w-500x265.jpg') }}" alt="Hyundai H1" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="p-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-2">Hyundai H1</h4>
                        <p class="text-gray-600 mb-4">Van moderne et confortable pour un voyage </p>
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span><i class="fas fa-users mr-2"></i>Jusqu'à 7 passagers</span>
                            <span><i class="fas fa-suitcase mr-2"></i>Espace bagages</span>
                        </div>
                        <div class="text-center">
                            <a href="#reservation" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-colors inline-block">
                                Réserver
                            </a>
                        </div>
                    </div>
                </div>

     <!-- Véhicule 5 - H1 Gallery -->
     <div class="group bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
        <div class="relative overflow-hidden">
            <img src="{{ asset('images/flotte/h1-tq-highlights-gallery-original-28-pc.jpg') }}" alt="H1 Gallery" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
        </div>
        <div class="p-6">
            <h4 class="text-xl font-bold text-gray-800 mb-2">H1 Familial</h4>
            <p class="text-gray-600 mb-4">Parfait pour les voyages en famille et les excursions</p>
            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                <span><i class="fas fa-users mr-2"></i>Jusqu'à 11 passagers</span>
                <span><i class="fas fa-suitcase mr-2"></i>Bagages Limités</span>
            </div>
            <div class="text-center">
                <a href="#reservation" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-colors inline-block">
                    Réserver
                </a>
            </div>
        </div>
    </div>        
</div>

            <!-- Call to action -->
            <div class="text-center mt-16">
                <div class="bg-gradient-to-r from-red-600 to-red-800 rounded-2xl p-8 md:p-12 text-white">
                    <h3 class="text-2xl md:text-3xl font-bold mb-4">Besoin d'un véhicule spécifique ?</h3>
                    <p class="text-lg mb-6">Notre équipe est là pour vous conseiller et vous proposer la solution la plus adaptée à vos besoins</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="#reservation" class="bg-white text-red-600 hover:bg-gray-100 font-bold py-3 px-8 rounded-lg transition duration-300 inline-block">
                            Réserver maintenant
                        </a>
                        <a href="#contact" class="border-2 border-white text-white hover:bg-white hover:text-red-600 font-bold py-3 px-8 rounded-lg transition duration-300 inline-block">
                            Nous contacter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Tarifs Section -->

    <section id="tarifs" class="bg-gray-50 pt-6">
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
                                <span class="text-sm text-red-200">/personne</span>
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



<!-- Suppléments & Informations importantes -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-bold text-red-600 mb-4">Informations & Suppléments</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Des frais additionnels peuvent s'appliquer selon vos besoins spécifiques&nbsp;: personnes supplémentaires, accompagnants, bagages ou arrêts additionnels.</p>
        </div>
        <div class="grid md:grid-cols-4 gap-8">
            <!-- Card 1 : Personne supplémentaire -->
            <div class="relative rounded-lg shadow-md overflow-hidden h-64 flex items-end">
                <img src="https://img.freepik.com/photos-gratuite/plein-coup-femme-marchant-bagages_23-2149338586.jpg?ga=GA1.1.1328141631.1747048938&semt=ais_hybrid&w=740" alt="Personne supplémentaire" class="absolute inset-0 w-full h-full object-cover opacity-60" />
                <div class="absolute inset-0 bg-black opacity-60"></div>
                <div class="relative z-10 p-6 w-full text-center">
                    <h3 class="text-lg font-bold text-white mb-2">Personne supplémentaire</h3>
                    <p class="text-white text-center">À partir de la 4<sup>e</sup> personne&nbsp;: <span class="font-bold text-yellow-300">5&nbsp;000 FCFA</span> / personne</p>
                </div>
            </div>
            <!-- Card 2 : Accompagnant -->
            <div class="relative rounded-lg shadow-md overflow-hidden h-64 flex items-end">
                <img src="https://img.freepik.com/photos-gratuite/groupe-cinq-joyeuses-voyageuses-afro-americaines-assises-dans-coffre-ouvert-voiture_627829-13439.jpg?ga=GA1.1.1328141631.1747048938&semt=ais_hybrid&w=740" alt="Accompagnant" class="absolute inset-0 w-full h-full object-cover opacity-60" />
                <div class="absolute inset-0 bg-black opacity-60"></div>
                <div class="relative z-10 p-6 w-full text-center">
                    <h3 class="text-lg font-bold text-white mb-2">Accompagnant</h3>
                    <p class="text-white text-center">Chaque accompagnement&nbsp;: <span class="font-bold text-yellow-300">15&nbsp;000 FCFA</span></p>
                </div>
            </div>
            <!-- Card 3 : Dépôt ou récupération supplémentaire -->
            <div class="relative rounded-lg shadow-md overflow-hidden h-64 flex items-end">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSAf2-kV97YkMybhLXOXtVtvJNuLP3kQCaxew&s" alt="Dépôt ou récupération supplémentaire" class="absolute inset-0 w-full h-full object-cover opacity-60" />
                <div class="absolute inset-0 bg-black opacity-60"></div>
                <div class="relative z-10 p-6 w-full text-center">
                    <h3 class="text-lg font-bold text-white mb-2">Dépôt ou récupération supplémentaire</h3>
                    <p class="text-white text-center">Par arrêt supplémentaire&nbsp;: <span class="font-bold text-yellow-300">5&nbsp;000 FCFA</span></p>
                </div>
            </div>
            <!-- Card 4 : Valise supplémentaire -->
            <div class="relative rounded-lg shadow-md overflow-hidden h-64 flex items-end">
                <img src="https://img.freepik.com/photos-gratuite/valet-vue-laterale-tenant-bagages_23-2149901447.jpg?ga=GA1.1.1328141631.1747048938&semt=ais_hybrid&w=740" alt="Valise supplémentaire" class="absolute inset-0 w-full h-full object-cover opacity-60" />
                <div class="absolute inset-0 bg-black opacity-50"></div>
                <div class="relative z-10 p-6 w-full text-center">
                    <h3 class="text-lg font-bold text-white mb-2">Valise supplémentaire</h3>
                    <p class="text-white text-center">Par valise au-delà du quota&nbsp;: <span class="font-bold text-yellow-300">5&nbsp;000 FCFA</span></p>
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

                    @if(isset($error))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                            <strong>Erreur :</strong>
                            <p>{{ $error }}</p>
                        </div>
                    @endif
                        <form id="reservation-form" action="{{ route('reservations.storeByProspect') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @csrf
                            <!-- 1. Nom complet -->
                            <div>
                                <label for="first_name" class="block text-gray-700 mb-2">Prénom</label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('first_name') border-red-500 @enderror" required>
                                @error('first_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-gray-700 mb-2">Nom</label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('last_name') border-red-500 @enderror" required>
                                @error('last_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- 2. Email -->
                            <div>
                                <label for="email" class="block text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('email') border-red-500 @enderror" required>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- 3. Téléphone -->
                            <div>
                                <label for="phone" class="block text-gray-700 mb-2">Téléphone</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('phone') border-red-500 @enderror" required>
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- 4. Point de départ -->
                            <div>
                                <label for="adresse_rammassage" class="block text-gray-700 mb-2">Point de départ</label>
                                <input type="text" name="adresse_rammassage" value="{{ old('adresse_rammassage') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('adresse_rammassage') border-red-500 @enderror" required>
                                @error('adresse_rammassage')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- 5. Point d'arrivée -->
                            <div>
                                <label for="nb_personnes" class="block mb-2">Nombre de passagers</label>
                                <input type="number" name="nb_personnes" id="nb_personnes" min="1" max="20" value="{{ old('nb_personnes') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('nb_personnes') border-red-500 @enderror" required>
                                @error('nb_personnes')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="date" class="block mb-2">Date</label>
                                <input type="date" name="date" value="{{ old('date') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('date') border-red-500 @enderror" required>
                                @error('date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- 7. Heure de ramassage -->
                            <div>
                                <label for="heure_ramassage" class="block mb-2">Heure de ramassage</label>
                                <input type="time" name="heure_ramassage" value="{{ old('heure_ramassage') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('heure_ramassage') border-red-500 @enderror" required>
                                @error('heure_ramassage')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- 8. Nombre de passagers -->
                            <div>
                                <label for="nb_valises" class="block mb-2">Nombre de valises</label>
                                <input type="number" name="nb_valises" id="nb_valises" min="1" max="20" value="{{ old('nb_valises') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('nb_valises') border-red-500 @enderror" required>
                                @error('nb_valises')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- 9. Type de service -->
                            <div>
                                <label for="trip_id_reservation" class="block text-sm font-medium text-gray-700 mb-1">Sens du trajet <span class="text-red-500">*</span></label>
                                <select id="trip_id_reservation" name="trip_id" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('trip_id') border-red-500 @enderror" required>
                                    <option value="">-- Sélectionner un trajet --</option>
                                    @foreach($trips as $trip)
                                        <option value="{{ $trip->id }}" {{ old('trip_id') == $trip->id ? 'selected' : '' }}>{{ $trip->departure }} - {{ $trip->destination }}</option>
                                    @endforeach
                                </select>
                                @error('trip_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
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

    <!-- Section Partenaires -->
    <section id="partenaires" class="py-20 bg-gradient-to-br from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">Ils nous ont fait confiance</h2>
            </div>

            <!-- Bande défilante des partenaires -->
            <div class="partners-scroll-container relative overflow-hidden">
                <div class="partners-scroll-track flex animate-scroll">
                    <!-- Premier groupe de partenaires -->
                    <div class="partners-group flex space-x-4 sm:space-x-6 md:space-x-8 lg:space-x-12 flex-shrink-0">
                        <!-- Partenaire 1 - CMA CGM -->
                        <div class="partner-item group bg-white rounded-xl p-3 sm:p-4 md:p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex-shrink-0 w-40 sm:w-44 md:w-48 lg:w-56">
                            <div class="text-center">
                                <div class="partner-logo-container mb-3 sm:mb-4 p-2 sm:p-3 bg-gray-50 rounded-lg group-hover:bg-red-50 transition-colors duration-300">
                                    <img src="{{ asset('images/partners/CMA_CGM_logo.svg (1).png') }}" 
                                         alt="CMA CGM" 
                                         class="h-10 sm:h-12 md:h-16 w-auto mx-auto object-contain transition-all duration-300">
                                </div>
                                <h4 class="text-xs sm:text-sm md:text-base font-semibold text-gray-800 mb-1">CMA CGM</h4>
                                <p class="text-xs text-gray-600">Transport maritime</p>
                            </div>
                        </div>

                        <!-- Partenaire 2 - Air France -->
                        <div class="partner-item group bg-white rounded-xl p-3 sm:p-4 md:p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex-shrink-0 w-40 sm:w-44 md:w-48 lg:w-56">
                            <div class="text-center">
                                <div class="partner-logo-container mb-3 sm:mb-4 p-2 sm:p-3 bg-gray-50 rounded-lg group-hover:bg-blue-50 transition-colors duration-300">
                                    <img src="{{ asset('images/partners/Air-France-Logo (1).png') }}" 
                                         alt="Air France" 
                                         class="h-10 sm:h-12 md:h-16 w-auto mx-auto object-contain transition-all duration-300">
                                </div>
                                <h4 class="text-xs sm:text-sm md:text-base font-semibold text-gray-800 mb-1">Air France</h4>
                                <p class="text-xs text-gray-600">Compagnie aérienne</p>
                            </div>
                        </div>

                        <!-- Partenaire 3 - Meet & Greet -->
                        <div class="partner-item group bg-white rounded-xl p-3 sm:p-4 md:p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex-shrink-0 w-40 sm:w-44 md:w-48 lg:w-56">
                            <div class="text-center">
                                <div class="partner-logo-container mb-3 sm:mb-4 p-2 sm:p-3 bg-gray-50 rounded-lg group-hover:bg-green-50 transition-colors duration-300">
                                    <img src="{{ asset('images/partners/MEET & GREET Logo.png') }}" 
                                         alt="Meet & Greet" 
                                         class="h-10 sm:h-12 md:h-16 w-auto mx-auto object-contain transition-all duration-300">
                                </div>
                                <h4 class="text-xs sm:text-sm md:text-base font-semibold text-gray-800 mb-1">Meet & Greet</h4>
                                <p class="text-xs text-gray-600">Service VIP</p>
                            </div>
                        </div>

                        <!-- Partenaire 4 - OBT -->
                        <div class="partner-item group bg-white rounded-xl p-3 sm:p-4 md:p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex-shrink-0 w-40 sm:w-44 md:w-48 lg:w-56">
                            <div class="text-center">
                                <div class="partner-logo-container mb-3 sm:mb-4 p-2 sm:p-3 bg-gray-50 rounded-lg group-hover:bg-purple-50 transition-colors duration-300">
                                    <img src="{{ asset('images/partners/logo_obt.jpeg') }}" 
                                         alt="OBT" 
                                         class="h-10 sm:h-12 md:h-16 w-auto mx-auto object-contain transition-all duration-300">
                                </div>
                                <h4 class="text-xs sm:text-sm md:text-base font-semibold text-gray-800 mb-1">OBT</h4>
                                <p class="text-xs text-gray-600">Organisation voyages</p>
                            </div>
                        </div>

                        <!-- Partenaire 5 - AIBD -->
                        <div class="partner-item group bg-white rounded-xl p-3 sm:p-4 md:p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex-shrink-0 w-40 sm:w-44 md:w-48 lg:w-56">
                            <div class="text-center">
                                <div class="partner-logo-container mb-3 sm:mb-4 p-2 sm:p-3 bg-gray-50 rounded-lg group-hover:bg-orange-50 transition-colors duration-300">
                                    <img src="{{ asset('images/partners/Aibd.png') }}" 
                                         alt="AIBD" 
                                         class="h-10 sm:h-12 md:h-16 w-auto mx-auto object-contain transition-all duration-300">
                                </div>
                                <h4 class="text-xs sm:text-sm md:text-base font-semibold text-gray-800 mb-1">AIBD</h4>
                                <p class="text-xs text-gray-600">Aéroport international</p>
                            </div>
                        </div>

                        <!-- Partenaire 6 - IPAR -->
                        <div class="partner-item group bg-white rounded-xl p-3 sm:p-4 md:p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex-shrink-0 w-40 sm:w-44 md:w-48 lg:w-56">
                            <div class="text-center">
                                <div class="partner-logo-container mb-3 sm:mb-4 p-2 sm:p-3 bg-gray-50 rounded-lg group-hover:bg-teal-50 transition-colors duration-300">
                                    <img src="{{ asset('images/partners/IPAR1.webp') }}" 
                                         alt="IPAR" 
                                         class="h-10 sm:h-12 md:h-16 w-auto mx-auto object-contain transition-all duration-300">
                                </div>
                                <h4 class="text-xs sm:text-sm md:text-base font-semibold text-gray-800 mb-1">IPAR</h4>
                                <p class="text-xs text-gray-600">Institut recherche</p>
                            </div>
                        </div>

                        <!-- Partenaire 7 - TSI -->
                        <div class="partner-item group bg-white rounded-xl p-3 sm:p-4 md:p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex-shrink-0 w-40 sm:w-44 md:w-48 lg:w-56">
                            <div class="text-center">
                                <div class="partner-logo-container mb-3 sm:mb-4 p-2 sm:p-3 bg-gray-50 rounded-lg group-hover:bg-indigo-50 transition-colors duration-300">
                                    <img src="{{ asset('images/partners/TSI.png') }}" 
                                         alt="TSI" 
                                         class="h-10 sm:h-12 md:h-16 w-auto mx-auto object-contain transition-all duration-300">
                                </div>
                                <h4 class="text-xs sm:text-sm md:text-base font-semibold text-gray-800 mb-1">TSI</h4>
                                <p class="text-xs text-gray-600">Technologies</p>
                            </div>
                        </div>

                        <!-- Partenaire 8 - SEN -->
                        <div class="partner-item group bg-white rounded-xl p-3 sm:p-4 md:p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex-shrink-0 w-40 sm:w-44 md:w-48 lg:w-56">
                            <div class="text-center">
                                <div class="partner-logo-container mb-3 sm:mb-4 p-2 sm:p-3 bg-gray-50 rounded-lg group-hover:bg-pink-50 transition-colors duration-300">
                                    <img src="{{ asset('images/partners/Sen.png') }}" 
                                         alt="SEN" 
                                         class="h-10 sm:h-12 md:h-16 w-auto mx-auto object-contain transition-all duration-300">
                                </div>
                                <h4 class="text-xs sm:text-sm md:text-base font-semibold text-gray-800 mb-1">SEN</h4>
                                <p class="text-xs text-gray-600">Services nationaux</p>
                            </div>
                        </div>

                        <!-- ESPACE entre SEN et CMA CGM -->
                        <div class="flex-shrink-0 w-8 sm:w-12 md:w-16 lg:w-20"></div>
                    </div>

                    <!-- Deuxième groupe (dupliqué pour l'effet de continuité) -->
                    <div class="partners-group flex space-x-4 sm:space-x-6 md:space-x-8 lg:space-x-12 flex-shrink-0">
                        <!-- Partenaire 1 - CMA CGM -->
                        <div class="partner-item group bg-white rounded-xl p-3 sm:p-4 md:p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex-shrink-0 w-40 sm:w-44 md:w-48 lg:w-56">
                            <div class="text-center">
                                <div class="partner-logo-container mb-3 sm:mb-4 p-2 sm:p-3 bg-gray-50 rounded-lg group-hover:bg-red-50 transition-colors duration-300">
                                    <img src="{{ asset('images/partners/CMA_CGM_logo.svg (1).png') }}" 
                                         alt="CMA CGM" 
                                         class="h-10 sm:h-12 md:h-16 w-auto mx-auto object-contain transition-all duration-300">
                                </div>
                                <h4 class="text-xs sm:text-sm md:text-base font-semibold text-gray-800 mb-1">CMA CGM</h4>
                                <p class="text-xs text-gray-600">Transport maritime</p>
                            </div>
                        </div>

                        <!-- Partenaire 2 - Air France -->
                        <div class="partner-item group bg-white rounded-xl p-3 sm:p-4 md:p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex-shrink-0 w-40 sm:w-44 md:w-48 lg:w-56">
                            <div class="text-center">
                                <div class="partner-logo-container mb-3 sm:mb-4 p-2 sm:p-3 bg-gray-50 rounded-lg group-hover:bg-blue-50 transition-colors duration-300">
                                    <img src="{{ asset('images/partners/Air-France-Logo (1).png') }}" 
                                         alt="Air France" 
                                         class="h-10 sm:h-12 md:h-16 w-auto mx-auto object-contain transition-all duration-300">
                                </div>
                                <h4 class="text-xs sm:text-sm md:text-base font-semibold text-gray-800 mb-1">Air France</h4>
                                <p class="text-xs text-gray-600">Compagnie aérienne</p>
                            </div>
                        </div>

                        <!-- Partenaire 3 - Meet & Greet -->
                        <div class="partner-item group bg-white rounded-xl p-3 sm:p-4 md:p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex-shrink-0 w-40 sm:w-44 md:w-48 lg:w-56">
                            <div class="text-center">
                                <div class="partner-logo-container mb-3 sm:mb-4 p-2 sm:p-3 bg-gray-50 rounded-lg group-hover:bg-green-50 transition-colors duration-300">
                                    <img src="{{ asset('images/partners/MEET & GREET Logo.png') }}" 
                                         alt="Meet & Greet" 
                                         class="h-10 sm:h-12 md:h-16 w-auto mx-auto object-contain transition-all duration-300">
                                </div>
                                <h4 class="text-xs sm:text-sm md:text-base font-semibold text-gray-800 mb-1">Meet & Greet</h4>
                                <p class="text-xs text-gray-600">Service VIP</p>
                            </div>
                        </div>

                        <!-- Partenaire 4 - OBT -->
                        <div class="partner-item group bg-white rounded-xl p-3 sm:p-4 md:p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex-shrink-0 w-40 sm:w-44 md:w-48 lg:w-56">
                            <div class="text-center">
                                <div class="partner-logo-container mb-3 sm:mb-4 p-2 sm:p-3 bg-gray-50 rounded-lg group-hover:bg-purple-50 transition-colors duration-300">
                                    <img src="{{ asset('images/partners/logo_obt.jpeg') }}" 
                                         alt="OBT" 
                                         class="h-10 sm:h-12 md:h-16 w-auto mx-auto object-contain transition-all duration-300">
                                </div>
                                <h4 class="text-xs sm:text-sm md:text-base font-semibold text-gray-800 mb-1">OBT</h4>
                                <p class="text-xs text-gray-600">Organisation voyages</p>
                            </div>
                        </div>

                        <!-- Partenaire 5 - AIBD -->
                        <div class="partner-item group bg-white rounded-xl p-3 sm:p-4 md:p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex-shrink-0 w-40 sm:w-44 md:w-48 lg:w-56">
                            <div class="text-center">
                                <div class="partner-logo-container mb-3 sm:mb-4 p-2 sm:p-3 bg-gray-50 rounded-lg group-hover:bg-orange-50 transition-colors duration-300">
                                    <img src="{{ asset('images/partners/Aibd.png') }}" 
                                         alt="AIBD" 
                                         class="h-10 sm:h-12 md:h-16 w-auto mx-auto object-contain transition-all duration-300">
                                </div>
                                <h4 class="text-xs sm:text-sm md:text-base font-semibold text-gray-800 mb-1">AIBD</h4>
                                <p class="text-xs text-gray-600">Aéroport international</p>
                            </div>
                        </div>

                        <!-- Partenaire 6 - IPAR -->
                        <div class="partner-item group bg-white rounded-xl p-3 sm:p-4 md:p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex-shrink-0 w-40 sm:w-44 md:w-48 lg:w-56">
                            <div class="text-center">
                                <div class="partner-logo-container mb-3 sm:mb-4 p-2 sm:p-3 bg-gray-50 rounded-lg group-hover:bg-teal-50 transition-colors duration-300">
                                    <img src="{{ asset('images/partners/IPAR1.webp') }}" 
                                         alt="IPAR" 
                                         class="h-10 sm:h-12 md:h-16 w-auto mx-auto object-contain transition-all duration-300">
                                </div>
                                <h4 class="text-xs sm:text-sm md:text-base font-semibold text-gray-800 mb-1">IPAR</h4>
                                <p class="text-xs text-gray-600">Institut recherche</p>
                            </div>
                        </div>

                        <!-- Partenaire 7 - TSI -->
                        <div class="partner-item group bg-white rounded-xl p-3 sm:p-4 md:p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex-shrink-0 w-40 sm:w-44 md:w-48 lg:w-56">
                            <div class="text-center">
                                <div class="partner-logo-container mb-3 sm:mb-4 p-2 sm:p-3 bg-gray-50 rounded-lg group-hover:bg-indigo-50 transition-colors duration-300">
                                    <img src="{{ asset('images/partners/TSI.png') }}" 
                                         alt="TSI" 
                                         class="h-10 sm:h-12 md:h-16 w-auto mx-auto object-contain transition-all duration-300">
                                </div>
                                <h4 class="text-xs sm:text-sm md:text-base font-semibold text-gray-800 mb-1">TSI</h4>
                                <p class="text-xs text-gray-600">Technologies</p>
                            </div>
                        </div>

                        <!-- Partenaire 8 - SEN -->
                        <div class="partner-item group bg-white rounded-xl p-3 sm:p-4 md:p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex-shrink-0 w-40 sm:w-44 md:w-48 lg:w-56">
                            <div class="text-center">
                                <div class="partner-logo-container mb-3 sm:mb-4 p-2 sm:p-3 bg-gray-50 rounded-lg group-hover:bg-pink-50 transition-colors duration-300">
                                    <img src="{{ asset('images/partners/Sen.png') }}" 
                                         alt="SEN" 
                                         class="h-10 sm:h-12 md:h-16 w-auto mx-auto object-contain transition-all duration-300">
                                </div>
                                <h4 class="text-xs sm:text-sm md:text-base font-semibold text-gray-800 mb-1">SEN</h4>
                                <p class="text-xs text-gray-600">Services nationaux</p>
                            </div>
                        </div>

                        <!-- ESPACE entre SEN et CMA CGM -->
                        <div class="flex-shrink-0 w-8 sm:w-12 md:w-16 lg:w-20"></div>
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

              // Initialisation du Swiper Carousel
        const swiper = new Swiper('.swiper-container', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 15,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 15,
                },
                1280: {
                    slidesPerView: 4,
                    spaceBetween: 15,
                },
                1536: {
                    slidesPerView: 4,
                    spaceBetween: 15,
                },
            },
        });

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

                modalCategory.textContent = data.actuCategory;
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
        function closeInvitation(event) {
            if (event) {
                event.stopPropagation(); // Empêche la propagation de l'événement
            }
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

        // Gestion du formulaire de réservation sans AJAX
        document.getElementById('reservation-form').addEventListener('submit', function(e) {
            // Le formulaire sera soumis normalement, pas de preventDefault()
            const submitBtn = document.getElementById('submit-btn');
            const submitText = submitBtn.querySelector('.submit-text');
            const submitSpinner = submitBtn.querySelector('.submit-spinner');

            // Désactiver le bouton et afficher le spinner
            submitBtn.disabled = true;
            submitText.textContent = 'Réservation en cours...';
            submitSpinner.classList.remove('hidden');
        });
    </script>

    <!-- Chat Box -->
    <div id="chat-container" class="fixed bottom-4 right-4 z-50">
        <!-- Message d'invitation (affiché temporairement) -->
        <div id="invitation-bubble" class="absolute bottom-20 right-0 bg-gradient-to-r from-white to-gray-50 rounded-xl shadow-2xl p-6 w-80 transform scale-0 transition-all duration-500 border border-gray-100 backdrop-blur-sm">
            <!-- Bouton de fermeture -->
            <button id="close-invitation" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 transition-colors" onclick="closeInvitation(event)">
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
                        <h4 class="text-base font-bold text-gray-800 mr-2">Mami</h4>
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
                <button id="custom-question-btn" class="mt-2 w-full bg-red-100 hover:bg-red-400 text-red-700 p-2 rounded-md text-xs text-center transition-colors flex items-center justify-center">
                    <i class="fas fa-edit mr-2 text-xs"></i>
                    Poser ma propre question
                </button>

                <button id="custom-question-btn" class="mt-2 w-full bg-green-500 hover:bg-green-400 text-red-700 p-2 rounded-md text-xs text-center transition-colors flex items-center justify-center">
                    <a href="https://wa.me/221777056969" class="whatsapp-float" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        Discuter sur whatsapp
                    </a>
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

✨ Transfert AIBD (32 500 FCFA)
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

    <!-- Modal pour les détails de l'info -->
    <div id="infoModal" class="modal">
        <div class="modal-content max-h-[90vh] overflow-y-auto">
            <div class="relative">
                <img id="infoModalImage" src="" alt="" class="modal-image hidden">
                <button class="absolute top-4 right-4 text-white bg-gray-800 bg-opacity-50 rounded-full p-2 hover:bg-opacity-75" onclick="closeInfoModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <span id="infoModalCategory" class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800"></span>
                    <span id="infoModalDate" class="text-sm text-gray-500"></span>
                </div>
                <h3 id="infoModalTitle" class="text-2xl font-bold text-gray-900 mb-4"></h3>
                <div id="infoModalContent" class="prose max-w-none text-gray-600 mb-6"></div>
                <div id="infoModalLinkContainer" class="hidden mt-4 pt-4 border-t border-gray-200">
                    <a id="infoModalLink" href="#" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                        <span>Visiter le site</span>
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 002 2v10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour les détails de la réservation -->
    <div id="reservationModal" class="modal">
        <div class="modal-content max-w-2xl">
            <div class="relative">
                <button class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors" onclick="closeReservationModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div class="text-center mb-6">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Réservation confirmée !</h3>
                    <p class="text-gray-600">Votre demande a été enregistrée avec succès</p>
                </div>

                @if(isset($reservation) && isset($trip))
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-gray-900 mb-3">Détails de votre réservation</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-700">Nom complet :</span>
                            <p class="text-gray-900">{{ $reservation->first_name }} {{ $reservation->last_name }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Email :</span>
                            <p class="text-gray-900">{{ $reservation->email }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Téléphone :</span>
                            <p class="text-gray-900">{{ $reservation->phone_number }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Date :</span>
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Heure :</span>
                            <p class="text-gray-900">{{ $reservation->heure_ramassage }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Trajet :</span>
                            <p class="text-gray-900">{{ $trip->departure }} → {{ $trip->destination }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Passagers :</span>
                            <p class="text-gray-900">{{ $reservation->nb_personnes }} personne(s)</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Valises :</span>
                            <p class="text-gray-900">{{ $reservation->nb_valises }} valise(s)</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Adresse de ramassage :</span>
                            <p class="text-gray-900">{{ $reservation->adresse_rammassage }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Tarif estimé :</span>
                            <p class="text-gray-900 font-semibold text-red-600">{{ number_format($reservation->tarif, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>Important :</strong> Votre réservation est actuellement en attente de confirmation par un agent.
                                Vous recevrez un email de confirmation une fois validée.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-gray-900 mb-3">Besoin d'aide ?</h4>
                    <div class="space-y-3">
                        <a href="tel:+221777056767" class="flex items-center text-red-600 hover:text-red-700 transition-colors">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>Contactez le service client : +221 77 705 67 67</span>
                        </a>
                        <a href="/register" class="flex items-center text-blue-600 hover:text-blue-700 transition-colors">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Créer un compte client pour suivre vos réservations</span>
                        </a>
                    </div>
                </div>
                @endif

                <div class="flex justify-center">
                    <button onclick="closeReservationModal()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Gestion du modal des infos
        const infoModal = document.getElementById('infoModal');
        const infoModalImage = document.getElementById('infoModalImage');
        const infoModalCategory = document.getElementById('infoModalCategory');
        const infoModalDate = document.getElementById('infoModalDate');
        const infoModalTitle = document.getElementById('infoModalTitle');
        const infoModalContent = document.getElementById('infoModalContent');
        const infoModalLink = document.getElementById('infoModalLink');
        const infoModalLinkContainer = document.getElementById('infoModalLinkContainer');

        function closeInfoModal() {
            infoModal.classList.remove('active');
            document.body.classList.remove('modal-open');
        }

        // Gestion du modal de réservation
        const reservationModal = document.getElementById('reservationModal');

        function closeReservationModal() {
            reservationModal.classList.remove('active');
            document.body.classList.remove('modal-open');
        }

        // Afficher automatiquement le modal de réservation si showReservationModal est true
        @if(isset($showReservationModal) && $showReservationModal)
            document.addEventListener('DOMContentLoaded', function() {
                reservationModal.classList.add('active');
                document.body.classList.add('modal-open');
            });
        @endif

        document.querySelectorAll('.info-card').forEach(card => {
            card.addEventListener('click', () => {
                const data = card.dataset;
                // Image
                if (data.infoImage) {
                    infoModalImage.src = data.infoImage;
                    infoModalImage.classList.remove('hidden');
                } else {
                    infoModalImage.classList.add('hidden');
                }
                // Catégorie
                infoModalCategory.textContent = data.infoCategory;
                infoModalCategory.style.backgroundColor = data.infoCategoryColor;
                // Date
                infoModalDate.textContent = data.infoDate;
                // Titre
                infoModalTitle.textContent = data.infoTitle;
                // Contenu
                infoModalContent.innerHTML = data.infoContent.replace(/\n/g, '<br>');
                // Lien externe
                if (data.infoLink) {
                    infoModalLink.href = data.infoLink;
                    infoModalLinkContainer.classList.remove('hidden');
                } else {
                    infoModalLinkContainer.classList.add('hidden');
                }
                infoModal.classList.add('active');
                document.body.classList.add('modal-open');
            });
        });

        // Carrousel automatique des partenaires
        document.addEventListener("DOMContentLoaded", function () {
    const track = document.querySelector(".partners-track");
    if (!track) return;

    // Dupliquer le contenu pour créer l'effet infini
    track.innerHTML += track.innerHTML;

    let position = 0;
    const speed = 1; // vitesse de défilement (px/frame)

    function animate() {
        position -= speed;

        // Quand la moitié du contenu est sortie, on reset
        if (Math.abs(position) >= track.scrollWidth / 2) {
            position = 0;
        }

        track.style.transform = `translateX(${position}px)`;
        requestAnimationFrame(animate);
    }

    animate();
});

    </script>

</body>
</html>
