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
    <h1>Modifier la voiture</h1>
    <form action="{{ route('cars.update', $car) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Marque</label>
            <input type="text" name="marque" class="form-control" value="{{ $car->marque }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Modèle</label>
            <input type="text" name="model" class="form-control" value="{{ $car->model }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Couleur</label>
            <input type="text" name="color" class="form-control" value="{{ $car->color }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Année</label>
            <input type="number" name="year" class="form-control" value="{{ $car->year }}" min="1900" max="{{ date('Y') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Matricule</label>
            <input type="text" name="matricule" class="form-control" value="{{ $car->matricule }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Modifier</button>
    </form>
</div>

</body>
</html>
@endsection
