<?php declare(strict_types=1); ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 bg-light sidebar" style="width: 250px; height: 100vh;">
    <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
        <span class="title">Tableau de bord</span>
    </a>

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
                    <li class="nav-item"><a href="{{ route('trips.index') }}" class="nav-link">Liste des trajets</a></li>
                    <li class="nav-item"><a href="{{ route('trips.create') }}" class="nav-link">Ajouter un trajet</a></li>
                </ul>
            </details>
        </li>

        <li class="nav-item">
            <details>
                <summary class="nav-link">
                    <span class="material-icons">people</span> Chauffeurs
                    <span class="material-icons">expand_more</span>
                </summary>
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="{{ route('drivers.index') }}" class="nav-link">Liste des chauffeurs</a></li>
                    <li class="nav-item">
                    <a href="{{ route('register.driver.form') }}" class="nav-link">Ajouter un Chauffeur</a>
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
                    <li class="nav-item"><a href="{{ route('clients.index') }}" class="nav-link">Liste des Clients</a></li>
                    <li class="nav-item">
                    <a href="{{ route('register.client.form') }}" class="nav-link">Ajouter un Client</a>
                    </li>
                </ul>
            </details>
        </li>

        <li class="nav-item">
            <details>
                <summary class="nav-link">
                    <span class="material-icons">directions_car</span> Voitures
                    <span class="material-icons">expand_more</span>
                </summary>
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="{{ route('cars.index') }}" class="nav-link">Liste des Voitures</a></li>
                    <li class="nav-item"><a href="{{ route('cars.create') }}" class="nav-link">Ajouter une voiture</a></li>
                </ul>
            </details>
        </li>
    </ul>

    <style>
        #sidebar {
            text-align: center;
            font-weight: bold;
            color: rgb(6, 60, 113);
        }
        .title {
            font-size: 22px;
        }
        .nav-link {
            display: flex;
            align-items: center;
            padding: 10px;
            color: rgb(6, 60, 113);
            font-weight: bold;
            cursor: pointer;
        }
        .nav-link:hover {
            background-color: rgba(6, 60, 113, 0.1);
            border-radius: 10px;
        }
        .material-icons {
            color: rgb(6, 60, 113);
            font-size: 20px;
            margin-right: 20px;
        }
        details {
            padding-left: 20px;
            border: none;
        }
        summary {
            list-style: none;
            outline: none;
        }
        summary::-webkit-details-marker {
            display: none;
        }
    </style>
</div>
