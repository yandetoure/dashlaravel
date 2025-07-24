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
    <h1>Réservations Annulées</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $reservation)
                <tr>
            <td>
                @if($reservation->client)
                    {{ $reservation->client->first_name }} {{ $reservation->client->last_name }}
                @else
                    {{ $reservation->first_name }} {{ $reservation->last_name }} (Prospect)
                @endif
            </td>
            <td>
                @if($reservation->carDriver && $reservation->carDriver->chauffeur)
                    {{ $reservation->carDriver->chauffeur->first_name }} {{ $reservation->carDriver->chauffeur->last_name }}
                @else
                    Non assigné
                @endif
            </td>
            <td>{{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($reservation->heure_ramassage)->format('H:i') }}</td>
            <td>{{ \Carbon\Carbon::parse($reservation->heure_vol)->format('H:i') }}</td>
                    <td><span class="badge bg-danger">Annulée</span></td>
                    <td>
                        <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-info btn-sm">Voir</a>
                        <!--  -->
                        <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer Définitivement</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

</body>
</html>
@endsection
