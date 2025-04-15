<?php declare(strict_types=1); ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


    
<div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 bg-light sidebar" style="width: 250px; height: 100vh;">
<div class="p-4 flex items-center space-x-2 border-b border-blue-700">
        <i class="fas fa-car text-2xl text-yellow-300"></i>
        <span class="logo-text text-xl font-bold">CPRO-VLC</span>
    </div>

    <!-- <div class="p-4 flex items-center space-x-4 border-b border-blue-700">
        <div class="w-10 h-10 rounded-full bg-blue-600 overflow-hidden">
        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Photo de profil" class="profile-img">
        </div>
        <div class="sidebar-text">
            <div class="font-medium">{{ Auth::user()->getRoleNames()->first() }}</div>
            <div class="text-xs text-blue-200"> {{ Auth::user()->role === 'entreprise' || Auth::user()->role === 'client' ? Auth::user()->name : Auth::user()->first_name . ' ' . Auth::user()->last_name }}
            </div>
        </div>
    </div> -->
<!-- 
    <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
        <span class="title">Tableau de bord</span>
    </a> -->

    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <details>
                <summary class="nav-link">
                    <span class="material-icons">assignment</span> Réservations
                    <span class="material-icons">expand_more</span>
                </summary>
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="{{ route('reservations.index') }}" class="nav-link">Liste des réservations</a></li>
                    <li class="nav-item"><a href="{{ route('reservations.create') }}" class="nav-link">Ajouter une réservation</a></li>
                    <li class="nav-item"><a href="{{ route('reservations.confirmed') }}" class="nav-link">Réservations Confirmées</a></li>
                    <li class="nav-item"><a href="{{ route('reservations.cancelled') }}" class="nav-link">Réservations Annulées</a></li>
                </ul>
            </details>
        </li>

        <li class="nav-item">
            <details>
                <summary class="nav-link">
                    <span class="material-icons">directions_car</span> Trajets
                    <span class="material-icons">expand_more</span>
                </summary>
                <ul class="nav flex-column">
                    <li class="nav-item">
                    <a href="{{ route('trips.index') }}" class="nav-link">Liste des trajets</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('trips.create') }}" class="nav-link">Ajouter un trajet</a>
                    </li>
                </ul>
            </details>
        </li>


        <li class="nav-item">
            <details>
                <summary class="nav-link">
                    <span class="material-icons">people</span> Super Admin
                    <span class="material-icons">expand_more</span>
                </summary>
                <ul class="nav flex-column">
                    <li class="nav-item">
                    <a href="{{ route('superadmins.index') }}" class="nav-link">Liste des Super Admins</a>
                    </li>
                    <li class="nav-item">
                    <a href="{{ route('admin.create.account.page') }}" class="nav-link">Ajouter un Super Admin</a>
                    </li>
                </ul>
            </details>
        </li>

        <li class="nav-item">
            <details>
                <summary class="nav-link">
                    <span class="material-icons">people</span> Admin
                    <span class="material-icons">expand_more</span>
                </summary>
                <ul class="nav flex-column">
                    <li class="nav-item">
                    <a href="{{ route('admins.index') }}" class="nav-link">Liste des admin</a>
                    </li>
                    <li class="nav-item">
                    <a href="{{ route('admin.create.account.page') }}" class="nav-link">Ajouter un admin</a>
                    </li>
                </ul>
            </details>
        </li>
        
        <li class="nav-item">
            <details>
                <summary class="nav-link">
                    <span class="material-icons">people</span> Agents
                    <span class="material-icons">expand_more</span>
                </summary>
                <ul class="nav flex-column">
                    <li class="nav-item">
                    <a href="{{ route('agents.index') }}" class="nav-link">Liste des agents</a>
                    </li>
                    <li class="nav-item">
                    <a href="{{ route('admin.create.account.page') }}" class="nav-link">Ajouter un Agent</a>
                    </li>
                </ul>
            </details>
        </li>

        <li class="nav-item">
            <details>
                <summary class="nav-link">
                    <span class="material-icons">directions_car</span> Chauffeurs
                    <span class="material-icons">expand_more</span>
                </summary>
                <ul class="nav flex-column">
                    <li class="nav-item">
                    <a href="{{ route('drivers.index') }}" class="nav-link">Liste des chauffeurs</a>
                    </li>
                    <li class="nav-item">
                    <a href="{{ route('admin.create.account.page') }}" class="nav-link">Ajouter un Chauffeur</a>
                    </li>
                    <li class="nav-item">
                    <a href="{{ route('admin.assign-day-off') }}" class="nav-link">Jour de repos</a>
                    </li>
                </ul>
            </details>
        </li>

        <li class="nav-item">
            <details>
                <summary class="nav-link">
                    <span class="material-icons">directions_car</span> Voiture assignées
                    <span class="material-icons">expand_more</span>
                </summary>
                <ul class="nav flex-column">
                    <li class="nav-item">
                    <a href="{{ route('cardrivers.index') }}" class="nav-link">Voitures & Chauffeurs</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('cardrivers.create') }}" class="nav-link">Ajouter un filiation</a>
                    </li>
                </ul>
            </details>
        </li>


        <li class="nav-item">
            <details>
                <summary class="nav-link">
                    <span class="material-icons">directions_car</span> Maintenance
                    <span class="material-icons">expand_more</span>
                </summary>
                <ul class="nav flex-column">
                    <li class="nav-item">
                    <a href="{{ route('maintenances.index') }}" class="nav-link">Voitures</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('maintenances.create') }}" class="nav-link">Créer</a>
                    </li>
                </ul>
            </details>
        </li>

        <li class="nav-item">
            <details>
                <summary class="nav-link">
                <span class="material-icons">person</span> Clients
                <span class="material-icons">expand_more</span>
                </summary>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('clients.index') }}" class="nav-link">Liste des Clients</a>
                    </li>
                    <li class="nav-item">
                    <a href="{{ route('admin.create.account.page') }}" class="nav-link">Ajouter un Client</a>
                    </li>
                </ul>
            </details>
        </li>

        <li class="nav-item">
            <details>
                <summary class="nav-link">
                <span class="material-icons">person</span> Voitures
                <span class="material-icons">expand_more</span>
                </summary>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('cars.index') }}" class="nav-link">Liste des Voiture</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('cars.create') }}" class="nav-link">Ajouter une voiture</a>
                    </li>
    </ul>

    <style>
        
            #sidebar {
        width: 250px;
        height: 100vh;
        background: linear-gradient(to bottom, #0a3d62, #0c2461);
        color: white;
        padding: 15px;
        position: fixed;
        top: 0;
        left: 0;
        font-weight: bold;
    }

    .title {
        font-size: 22px;
        text-align: center;
        margin-bottom: 15px;
        color: #f1f1f1;
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

    .nav-link.active {
        background: rgba(255, 255, 255, 0.3);
    }

    .material-icons {
        font-size: 20px;
        margin-right: 10px;
        color: white;
    }

    details {
        border: none;
    }

    summary {
        display: flex;
        align-items: center;
        cursor: pointer;
        list-style: none;
        padding: 10px;
        border-radius: 8px;
        transition: background 0.3s ease-in-out;
    }

    summary:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    details[open] summary {
        background: rgba(252, 249, 249, 0.99);
    }

    details ul {
        padding-left: 15px;
    }

    details ul .nav-link {
        font-size: 14px;
        padding-left: 30px;
        color: #f1f1f1;
    }

    details ul .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
    }
        .main-content {
            transition: all 0.3s;
        }
        .main-content-expanded {
            margin-left: 70px;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
        }
    </style>

</div>
