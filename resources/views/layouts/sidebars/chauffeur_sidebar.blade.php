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
                    <li class="nav-item">
                        <a href="{{ route('reservations.chauffeur.mes') }}" class="nav-link">Liste des Réservations</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link">Ajouter une Réservation</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link">Réservations Confirmées</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link">Réservations Annulées</a>
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
                        <a href="" class="nav-link">Liste des Agents</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link">Ajouter un Agent</a>
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
                        <a href="" class="nav-link">Liste des Chauffeurs</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link">Ajouter un Chauffeur</a>
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
                        <a href="" class="nav-link">Liste des Clients</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link">Ajouter un Client</a>
                    </li>
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
            cursor: pointer; /* Change le curseur pour indiquer que c'est cliquable */
        }
        .nav-link:hover {
            background-color: rgba(6, 60, 113, 0.1); /* Ajoute un effet de survol */
        }
        .material-icons {
            color: rgb(6, 60, 113);
            font-size: 20px;
            margin-right: 20px;
        }
        details {
            padding-left: 20px; /* Indentation du sous-menu */
            border: none; /* Supprime les bordures par défaut */
        }
        summary {
            list-style: none; /* Supprime le style de liste par défaut */
            outline: none; /* Supprime le contour par défaut */
        }
        summary::-webkit-details-marker {
            display: none; /* Masque le marqueur par défaut */
        }
    </style>
</div>
