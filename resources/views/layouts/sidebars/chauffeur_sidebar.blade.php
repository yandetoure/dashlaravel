<?php declare(strict_types=1); ?>

 <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> 

<div id="sidebar" class="sidebar">
    <!-- Header fixe -->
    <div class="sticky-header">
        <!-- Logo -->
        <div class="d-flex align-items-center mb-4 border-bottom pb-3">
            <i class="fas fa-car text-warning fs-4 me-2"></i>
            <span class="logo-text fs-5 fw-bold text-white">CPRO-VLC</span>
        </div>

        <!-- User Profile -->
        <div class="d-flex align-items-center mb-4 border-bottom pb-3 text-white">
            <div class="me-3">
                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Photo de profil" class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover;">
            </div>
            <div>
                <div class="fw-semibold">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                <small class="text-light text-opacity-75">
                    {{ Auth::user()->getRoleNames()->first() }}
                </small>
            </div>
        </div>
    </div>

    <!-- Partie scrollable -->
    <div class="sidebar-links">
        <ul class="nav nav-pills flex-column mb-auto">
            <!-- Réservations -->
            <li><a href="{{ route('dashboard.driver') }}" class="nav-link"><span class="material-icons">assignment</span> Tableau de bord</a></li>

            <li><a href="{{ route('reservations.chauffeur.mes') }}" class="nav-link"><span class="material-icons">assignment</span> Mes réservations</a></li>
            <li><a href="{{ route('reservations.create') }}" class="nav-link"><span class="material-icons">add</span> Ajouter une réservation</a></li>

            <!-- Trajets -->
            <li><a href="{{ route('trips.index') }}" class="nav-link"><span class="material-icons">directions_car</span> Liste des trajets</a></li>
            <li><a href="{{ route('trips.create') }}" class="nav-link"><span class="material-icons">add</span> Ajouter un trajet</a></li>

            <!-- Super Admin -->
            <li><a href="{{ route('superadmins.index') }}" class="nav-link"><span class="material-icons">people</span> Liste des Super Admins</a></li>
            <li><a href="{{ route('admin.create.account.page') }}" class="nav-link"><span class="material-icons">person_add</span> Ajouter un Super Admin</a></li>

            <!-- Admin -->
            <li><a href="{{ route('admins.index') }}" class="nav-link"><span class="material-icons">people</span> Liste des admin</a></li>
            <li><a href="{{ route('admin.create.account.page') }}" class="nav-link"><span class="material-icons">person_add</span> Ajouter un admin</a></li>

            <!-- Agents -->
            <li><a href="{{ route('agents.index') }}" class="nav-link"><span class="material-icons">people</span> Liste des agents</a></li>
            <li><a href="{{ route('register.agent.form') }}" class="nav-link"><span class="material-icons">person_add</span> Ajouter un Agent</a></li>

            <!-- Chauffeurs -->
            <li><a href="{{ route('drivers.index') }}" class="nav-link"><span class="material-icons">directions_car</span> Liste des chauffeurs</a></li>
            <li><a href="{{ route('admin.create.account.page') }}" class="nav-link"><span class="material-icons">person_add</span> Ajouter un Chauffeur</a></li>
            <li><a href="{{ route('admin.assign-day-off') }}" class="nav-link"><span class="material-icons">event_busy</span> Jour de repos</a></li>

            <!-- Voitures assignées -->
            <li><a href="{{ route('cardrivers.index') }}" class="nav-link"><span class="material-icons">directions_car</span> Voitures & Chauffeurs</a></li>
            <li><a href="{{ route('cardrivers.create') }}" class="nav-link"><span class="material-icons">add</span> Ajouter un filiation</a></li>

            <!-- Maintenance -->
            <li><a href="{{ route('maintenances.index') }}" class="nav-link"><span class="material-icons">build</span> Voitures (Maintenance)</a></li>
            <li><a href="{{ route('maintenances.create') }}" class="nav-link"><span class="material-icons">add</span> Créer maintenance</a></li>

            <!-- Clients -->
            <li><a href="{{ route('clients.index') }}" class="nav-link"><span class="material-icons">person</span> Liste des Clients</a></li>
            <li><a href="{{ route('admin.create.account.page') }}" class="nav-link"><span class="material-icons">person_add</span> Ajouter un Client</a></li>

            <!-- Voitures -->
            <li><a href="{{ route('cars.index') }}" class="nav-link"><span class="material-icons">directions_car</span> Liste des Voitures</a></li>
            <li><a href="{{ route('cars.create') }}" class="nav-link"><span class="material-icons">add</span> Ajouter une voiture</a></li>
        </ul>
    </div>
</div>

<style>
    #sidebar {
        width: 250px;
        height: 100vh;
        background: linear-gradient(to bottom, #0a3d62, #0c2461);
        color: white;
        position: fixed;
        top: 0;
        left: 0;
        font-weight: bold;
        display: flex;
        flex-direction: column;
    }

    .sticky-header {
        flex-shrink: 0;
        padding: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-links {
        flex-grow: 1;
        overflow-y: auto;
        padding: 15px;
    }

    .logo-text {
        color: white;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 10px;
        color: #f1f1f1;
        font-weight: bold;
        text-decoration: none;
        border-radius: 8px;
        transition: background 0.3s ease-in-out;
    }

    .nav-link:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .material-icons {
        font-size: 20px;
        margin-right: 10px;
        color: white;
    }
</style>
