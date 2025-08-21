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

<form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
    @csrf
    @method('PUT') <!-- Important pour spécifier que c'est une requête PUT -->

    <div class="mb-3">
        <label for="date" class="form-label">Date</label>
        <input type="date" class="form-control" id="date" name="date" value="{{ \Carbon\Carbon::parse($reservation->date)->format('Y-m-d') }}" required>
    </div>

    <div class="mb-3">
        <label for="heure_ramassage" class="form-label">Heure Ramassage</label>
                        <input type="time" step="300" class="form-control" id="heure_ramassage" name="heure_ramassage" value="{{ \Carbon\Carbon::parse($reservation->heure_ramassage)->format('H:i') }}" required>
    </div>

    <div class="mb-3">
        <label for="heure_vol" class="form-label">Heure Vol</label>
                        <input type="time" step="300" class="form-control" id="heure_vol" name="heure_vol" value="{{ \Carbon\Carbon::parse($reservation->heure_vol)->format('H:i') }}">
    </div>

    
    <button type="submit" class="btn btn-primary">Sauvegarder les modifications</button>
</form>

</body>
</html>
@endsection