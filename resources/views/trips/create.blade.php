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
<div class="container mt-5">
    <h2 class="text-center mb-4">Créer un Trajet</h2>
    <form action="{{ route('trips.store') }}" method="POST" class="bg-light p-4 rounded shadow-sm">
        @csrf

        <div class="row mb-3">
            <!-- Départ -->
            <div class="col-md-6">
                <label for="departure" class="form-label">Départ</label>
                <input type="text" name="departure" class="form-control" placeholder="Entrez votre lieu de départ" required>
            </div>

            <!-- Arrivée -->
            <div class="col-md-6">
                <label for="destination" class="form-label">Arrivée</label>
                <input type="text" name="destination" class="form-control" placeholder="Entrez votre destination" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Créer</button>
    </form>
</div>

</body>
</html>
@endsection
