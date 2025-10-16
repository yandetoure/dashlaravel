<?php declare(strict_types=1); ?>

 <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> 

<div id="sidebar" class="sidebar">
    <!-- Header fixe -->
    <div class="sticky-header">

        <!-- User Profile -->
        <div class="d-flex align-items-center border-bottom pb-3">
            <div class="me-3">
                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Photo de profil" class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover;">
            </div>
            <div>
                <div class="fw-semibold">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                <small class=" text-opacity-75">
                    {{ Auth::user()->getRoleNames()->first() }}
                </small>
            </div>
        </div>
    </div>

    <!-- Partie scrollable -->
    <div class="sidebar-links">
        <ul class="nav nav-pills flex-column mb-auto">
                <h6>Dashboard</h6>
                <li><a href="{{ route('dashboard.superadmin') }}" class="nav-link {{ request()->routeIs('dashboard.superadmin') ? 'active' : '' }}"><span class="material-icons">assignment</span> Tableau de bord</a></li>
                <li><a href="{{ route('reservations.showCalendar') }}" class="nav-link {{ request()->routeIs('reservations.showCalendar') ? 'active' : '' }}"><span class="material-icons">directions_car</span> Calendier</a></li>
                <h6>Actualités</h6>
                <li><a href="{{ route('actus.index') }}" class="nav-link {{ request()->routeIs('actus.index') ? 'active' : '' }}"><span class="material-icons">article</span> Actualités</a></li>
                <li><a href="{{ route('infos.index') }}" class="nav-link {{ request()->routeIs('infos.*') ? 'active' : '' }}"><span class="material-icons">info</span> Infos</a></li>
                <li><a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"><span class="material-icons">category</span> Catégories</a></li>

                        <h6>Réservations</h6>
                        <li><a href="{{ route('reservations.index') }}" class="nav-link {{ request()->routeIs('reservations.index') ? 'active' : '' }}"><span class="material-icons">assignment</span> Liste des réservations</a></li>
                        <li><a href="{{ route('courses.index') }}" class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}"><span class="material-icons">local_taxi</span> Gestion des courses</a></li>
                        <li><a href="{{ route('trips.index') }}" class="nav-link {{ request()->routeIs('trips.index') ? 'active' : '' }}"><span class="material-icons">directions_car</span> Liste des trajets</a></li>
                        
                        <h6>Suivi</h6>
                        <li><a href="{{ route('admin.driver-location') }}" class="nav-link {{ request()->routeIs('admin.driver-location*') ? 'active' : '' }}"><span class="material-icons">my_location</span> Localisation des chauffeurs</a></li>

                <h6>Trafic</h6>
                <li><a href="{{ route('traffic.index') }}" class="nav-link {{ request()->routeIs('traffic.index') ? 'active' : '' }}"><span class="material-icons">traffic</span> Alertes Trafic</a></li>

                <h6>Utilisateurs</h6>
                <li><a href="{{ route('superadmins.index') }}" class="nav-link {{ request()->routeIs('superadmins.index') ? 'active' : '' }}"><span class="material-icons">people</span> Super Admins</a></li>
                {{-- <li><a href="{{ route('admin.create.account.page') }}" class="nav-link {{ request()->routeIs('admin.create.account.page') ? 'active' : '' }}"><span class="material-icons">person_add</span> Ajouter un utilisateur</a></li> --}}
                {{-- <li><a href="{{ route('admins.index') }}" class="nav-link {{ request()->routeIs('admins.index') ? 'active' : '' }}"><span class="material-icons">people</span> Liste des admins</a></li> --}}
                {{-- <li><a href="{{ route('agents.index') }}" class="nav-link {{ request()->routeIs('agents.index') ? 'active' : '' }}"><span class="material-icons">people</span> Liste des agents</a></li> --}}
                {{-- <li><a href="{{ route('drivers.index') }}" class="nav-link {{ request()->routeIs('drivers.index') ? 'active' : '' }}"><span class="material-icons">directions_car</span> Liste des chauffeurs</a></li> --}}
                <li><a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.index') ? 'active' : '' }}"><span class="material-icons">person</span> Clients</a></li>
                <li><a href="{{ route('driver-groups.index') }}" class="nav-link {{ request()->routeIs('driver-groups.index') ? 'active' : '' }}"><span class="material-icons">groups</span> Groupes de Chauffeurs</a></li>

                <h6>Voitures</h6>
                <li><a href="{{ route('cardrivers.index') }}" class="nav-link {{ request()->routeIs('cardrivers.index') ? 'active' : '' }}"><span class="material-icons">directions_car</span> Voitures & Chauffeurs</a></li>
                <li><a href="{{ route('maintenances.index') }}" class="nav-link {{ request()->routeIs('maintenances.index') ? 'active' : '' }}"><span class="material-icons">build</span> Maintenance</a></li>
                <li><a href="{{ route('cars.index') }}" class="nav-link {{ request()->routeIs('cars.index') ? 'active' : '' }}"><span class="material-icons">directions_car</span> Voitures</a></li>

                <h6>Paiements</h6>
                <li><a href="{{ route('payments.history') }}" class="nav-link {{ request()->routeIs('payments.history') ? 'active' : '' }}"><span class="material-icons">payment</span> Historique des Paiements</a></li>
                <li><a href="{{ route('admin.cashout') }}" class="nav-link {{ request()->routeIs('admin.cashout*') ? 'active' : '' }}"><span class="material-icons">account_balance_wallet</span> Gestion des Cashouts</a></li>
        </ul>
    </div>
</div>

<style>
    #sidebar {
        width: 250px;
        height: 100vh;
        background-color:rgb(255, 255, 255);
        margin-top: 100px;
        /* background: linear-gradient(to bottom, #0a3d62, #0c2461); */
        color:rgba(56, 55, 51, 0.78);
        position: fixed;
        left: 0;
        font-weight: bold;
        display: flex;
        flex-direction: column;
    }

    .sticky-header {
        flex-shrink: 0;
        padding: 18px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-links {
        flex-grow: 1;
        overflow-y: auto;
        padding: 15px;
    }

    .logo-text {
        color:rgb(104, 6, 6);
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 10px;
        font-weight: bold;
        text-decoration: none;
        border-radius: 8px;
        transition: background 0.3s ease-in-out;
        color:rgb(104, 6, 6) !important;
        font-size: 13px;
    }

    .nav-link:hover {
    background-color:rgba(19, 19, 19, 0.23) !important;    
}

    .material-icons {
        font-size: 20px !important;
        color:rgb(104, 6, 6);
        margin-right: 5px !important;  
    }
    .nav-link.active {
    background-color: #d6d6d6 !important;
    color: rgb(0, 0, 0) !important;
}

    h6{
    font-size: 14px;
    }
</style>
