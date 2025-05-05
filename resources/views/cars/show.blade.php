<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                        dark: '#1F2937',
                    }
                }
            }
        }
    </script>
    <style>
        .reservation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .status-badge {
            top: -10px;
            right: -10px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="container">
    <h1>Détails de la voiture</h1>
    <ul class="list-group">
        <li class="list-group-item"><strong>Marque :</strong> {{ $car->marque }}</li>
        <li class="list-group-item"><strong>Modèle :</strong> {{ $car->model }}</li>
        <li class="list-group-item"><strong>Couleur :</strong> {{ $car->color }}</li>
        <li class="list-group-item"><strong>Année :</strong> {{ $car->year }}</li>
        <li class="list-group-item"><strong>Matricule :</strong> {{ $car->matricule }}</li>
    </ul>
    <a href="{{ route('cars.index') }}" class="btn btn-secondary mt-3">Retour</a>
</div>

</body>
</html>
@endsection
