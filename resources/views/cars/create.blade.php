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
<div class="container mt-5 d-flex justify-content-center">
    <div class="card shadow-lg p-4 rounded-4" style="max-width: 600px; width: 100%;">
        <h2 class="text-center mb-4 fw-bold text-primary">🚗 Ajouter une voiture</h2>

        <!-- Affichage des erreurs -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('cars.store') }}" method="POST" class="mt-3">
            @csrf

            <div class="row mb-4">
                <!-- Marque -->
                <div class="col-md-6">
                    <label for="marque" class="form-label fw-semibold">Marque</label>
                    <input type="text" name="marque" id="marque" class="form-control rounded-3 p-3 shadow-sm" 
                           placeholder="Ex: Toyota, BMW..." value="{{ old('marque') }}" required>
                </div>

                <!-- Modèle -->
                <div class="col-md-6">
                    <label for="model" class="form-label fw-semibold">Modèle</label>
                    <input type="text" name="model" id="model" class="form-control rounded-3 p-3 shadow-sm" 
                           placeholder="Ex: Corolla, X5..." value="{{ old('model') }}" required>
                </div>
            </div>

            <div class="row mb-4">
                <!-- Couleur -->
                <div class="col-md-6">
                    <label for="color" class="form-label fw-semibold">Couleur</label>
                    <input type="text" name="color" id="color" class="form-control rounded-3 p-3 shadow-sm" 
                           placeholder="Ex: Rouge..." value="{{ old('color') }}" required>
                </div>

                <!-- Année -->
                <div class="col-md-6">
                    <label for="year" class="form-label fw-semibold">Année</label>
                    <input type="number" name="year" id="year" class="form-control rounded-3 p-3 shadow-sm" 
                           placeholder="Ex: 2023" value="{{ old('year') }}" required>
                </div>
            </div>

            <div class="mb-4">
                <!-- Matricule -->
                <label for="matricule" class="form-label fw-semibold">Matricule</label>
                <input type="text" name="matricule" id="matricule" class="form-control rounded-3 p-3 shadow-sm" 
                       placeholder="Ex: AB-123-CD" value="{{ old('matricule') }}" required>
            </div>

            <!-- Bouton -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg fw-bold px-4 py-2 mt-3 rounded-pill">
                    ➕ Ajouter la voiture
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
@endsection
