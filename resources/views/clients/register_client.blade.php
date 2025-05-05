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
        <h2 class="text-center mb-4 fw-bold text-primary">👮 Ajouter un agent</h2>

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

        <form method="POST" action="{{ route('register.client') }}" enctype="multipart/form-data" class="mt-3">
            @csrf

            <!-- Prénom -->
            <div class="mb-4">
                <label for="first_name" class="form-label fw-semibold">Prénom</label>
                <input type="text" name="first_name" id="first_name" class="form-control rounded-3 p-3 shadow-sm" placeholder="Ex: Jean" required>
            </div>

            <!-- Nom -->
            <div class="mb-4">
                <label for="last_name" class="form-label fw-semibold">Nom</label>
                <input type="text" name="last_name" id="last_name" class="form-control rounded-3 p-3 shadow-sm" placeholder="Ex: Dupont" required>
            </div>

            <!-- Adresse -->
            <div class="mb-4">
                <label for="address" class="form-label fw-semibold">Adresse</label>
                <input type="text" name="address" id="address" class="form-control rounded-3 p-3 shadow-sm" placeholder="Ex: 123 Rue Principale" required>
            </div>

            <!-- Téléphone -->
            <div class="mb-4">
                <label for="phone_number" class="form-label fw-semibold">Téléphone</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control rounded-3 p-3 shadow-sm" placeholder="Ex: 771234567" pattern="[0-9]{9}" required>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input type="email" name="email" id="email" class="form-control rounded-3 p-3 shadow-sm" placeholder="Ex: agent@example.com" required>
            </div>

            <!-- Photo de profil -->
            <div class="mb-4">
                <label for="profile_photo" class="form-label fw-semibold">Photo de profil (optionnel)</label>
                <input type="file" name="profile_photo" id="profile_photo" class="form-control rounded-3 p-3 shadow-sm" accept="image/*">
            </div>

            <!-- Bouton -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg fw-bold px-4 py-2 mt-3 rounded-pill">
                    ✨ Créer Agent
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
@endsection
