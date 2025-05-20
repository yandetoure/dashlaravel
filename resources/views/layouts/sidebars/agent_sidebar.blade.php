<div id="sidebar" class="sidebar" role="navigation">
    <!-- Header fixe -->
    <div class="sticky-header">

        <!-- User Profile -->
        <div class="d-flex align-items-center border-bottom pb-3">
            <div class="me-3">
                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Photo de profil" class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover;">
            </div>
            <div>
                <div class="fw-semibold">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                <small class="text-opacity-75">
                    {{ Auth::user()->getRoleNames()->first() }}
                </small>
            </div>
        </div>
    </div>

    <!-- Partie scrollable -->
    <div class="sidebar-links">
        <ul class="nav nav-pills flex-column mb-auto">
            <!-- Example of dynamically visible links based on user role -->
            <li><a href="{{ route('dashboard.agent') }}" class="nav-link" aria-label="Go to dashboard"><span class="material-icons">assignment</span> Tableau de bord</a></li>
            <li><a href="{{ route('reservations.showCalendar') }}" class="nav-link {{ request()->routeIs('reservations.showCalendar') ? 'active' : '' }}"><span class="material-icons">directions_car</span> Calendier</a></li>

            <h6>Réservations</h6> 
            <li><a href="{{ route('superadmins.index') }}" class="nav-link" aria-label="View Super Admins"><span class="material-icons">people</span> Liste des Super Admins</a></li>
            <li><a href="{{ route('trips.index') }}" class="nav-link" aria-label="Manage trips"><span class="material-icons">directions_car</span> Trajets</a></li>

            <h6>Utilisateurs</h6>
            <li><a href="{{ route('admin.create.account.page') }}" class="nav-link {{ request()->routeIs('admin.create.account.page') ? 'active' : '' }}"><span class="material-icons">person_add</span> Ajouter un utilisateur</a></li>
            <li><a href="{{ route('superadmins.index') }}" class="nav-link {{ request()->routeIs('superadmins.index') ? 'active' : '' }}"><span class="material-icons">people</span> Listes des Utilisateurs</a></li>
            <li><a href="{{ route('clients.index') }}" class="nav-link" aria-label="Manage clients"><span class="material-icons">person</span> Liste des Clients</a></li>
            <li><a href="{{ route('drivers.index') }}" class="nav-link {{ request()->routeIs('drivers.index') ? 'active' : '' }}"><span class="material-icons">directions_car</span> Liste des chauffeurs</a></li>

            <h6>Factures</h6>
            <li><a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.index') ? 'active' : '' }}"><span class="material-icons">person_add</span> Factures</a></li>
            <h6>Voitures</h6>
            <li><a href="{{ route('cardrivers.index') }}" class="nav-link {{ request()->routeIs('cardrivers.index') ? 'active' : '' }}"><span class="material-icons">directions_car</span> Voitures & Chauffeurs</a></li>
            <li><a href="{{ route('maintenances.index') }}" class="nav-link {{ request()->routeIs('maintenances.index') ? 'active' : '' }}"><span class="material-icons">build</span> Maintenance</a></li>
            <li><a href="{{ route('cars.index') }}" class="nav-link {{ request()->routeIs('cars.index') ? 'active' : '' }}"><span class="material-icons">directions_car</span> Voitures</a></li>
            </ul>
    </div>
</div>

<style>
    #sidebar {
        width: 250px;
        height: 100vh;
        background-color: rgb(255, 255, 255);
        margin-top: 100px;
        color: rgba(56, 55, 51, 0.78);
        position: fixed;
        left: 0;
        font-weight: bold;
        display: flex;
        flex-direction: column;
        margin-bottom: 10px;
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

    .material-icons {
        font-size: 20px !important;
        color: rgb(104, 6, 6);
        margin-right: 5px !important;
    }

    .nav-link.active {
        background-color: #d6d6d6 !important;
        color: rgb(0, 0, 0) !important;
    }

    @media (max-width: 768px) {
        #sidebar {
            display: none;
        }
    }
        h6{
        font-size: 14px;
        }
</style>
