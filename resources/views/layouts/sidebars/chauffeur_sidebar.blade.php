<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Responsive</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
        }

        #sidebar {
            width: 250px;
            height: 100vh;
            background-color:rgb(255, 255, 255);
            margin-top: 100px;
        /* background: linear-gradient(to bottom, #0a3d62, #0c2461); */
            color:rgba(56, 55, 51, 0.78);
            position: fixed;
            top: 0;
            left: 0;
            font-weight: bold;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease-in-out;
            z-index: 1050;
            padding-bottom: 100px;
        }

        #sidebar.hidden {
            transform: translateX(-100%);
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

        .nav-link {
            display: flex;
            align-items: center;
            padding: 10px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s ease-in-out;
            color: rgb(104, 6, 6) !important;
            font-size: 13px;
        }

        .nav-link:hover {
            background-color: rgba(19, 19, 19, 0.23) !important;
        }

        .nav-link.active {
            background-color: #d6d6d6 !important;
            color: rgb(0, 0, 0) !important;
        }

        .material-icons {
            font-size: 20px !important;
            color: rgb(104, 6, 6);
            margin-right: 5px !important;
        }

        /* Menu burger */
        .menu-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1100;
            background-color: white;
            border: none;
            font-size: 28px;
            display: none;
        }

        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
            }

            #sidebar.show {
                transform: translateX(0);
            }

            .menu-toggle {
                display: block;
            }
        }

        h6{
            font-size: 14px;
        }

    </style>

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
            <li><a href="{{ route('dashboard.chauffeur') }}" class="nav-link {{ request()->routeIs('dashboard.superadmin') ? 'active' : '' }}"><span class="material-icons">assignment</span> Tableau de bord</a></li>
            <li><a href="{{ route('reservations.showCalendar') }}" class="nav-link {{ request()->routeIs('reservations.showCalendar') ? 'active' : '' }}"><span class="material-icons">directions_car</span> Calendier</a></li>
            <!-- Réservations -->
            <h6>Réservations</h6>
            <li><a href="{{ route('reservations.chauffeur.mes') }}" class="nav-link {{ request()->routeIs('reservations.client.mes') ? 'active' : '' }}"><span class="material-icons">assignment</span> Mes réservations</a></li>
            <li><a href="{{ route('reservations.clientcreate') }}" class="nav-link {{ request()->routeIs('reservations.create') ? 'active' : '' }}"><span class="material-icons">add</span> Ajouter une réservation</a></li>
            <!-- Trajets -->
            <li><a href="{{ route('trips.index') }}" class="nav-link {{ request()->routeIs('trips.index') ? 'active' : '' }}"><span class="material-icons">directions_car</span> Liste des trajets</a></li>
            <!-- Chauffeurs -->
            <li><a href="{{ route('drivers.index') }}" class="nav-link {{ request()->routeIs('drivers.index') ? 'active' : '' }}"><span class="material-icons">directions_car</span> Liste des chauffeurs</a></li>
        </ul>
    </div>
</div>

<!-- Bouton menu burger -->
<button class="menu-toggle" onclick="toggleSidebar()">
    <span class="material-icons">menu</span>
</button>


<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('show');
    }
</script>
