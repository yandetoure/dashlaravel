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
    <h2>Gestion des Réservations</h2>

    @if ($reservations->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Chauffeur</th>
                    <th>Voyage</th>
                    <th>Date</th>
                    <th>Heure Ramassage</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->id }}</td>
                        <td>{{ $reservation->client->first_name }} {{ $reservation->client->last_name }}</td>
                        <td>{{ $reservation->chauffeur->first_name }} {{ $reservation->chauffeur->last_name }}</td>
                        <td>{{ $reservation->trip->name }}</td>
                        <td>{{ $reservation->date }}</td>
                        <td>{{ $reservation->heure_ramassage }}</td>
                        <td>
                            <span class="badge 
                                @if($reservation->status == 'confirmed') 
                                    badge-success 
                                @elseif($reservation->status == 'pending') 
                                    badge-warning 
                                @else 
                                    badge-danger 
                                @endif">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('reservations.confirm', $reservation) }}" class="btn btn-success btn-sm">Confirmer</a>
                            <a href="{{ route('reservations.cancel', $reservation) }}" class="btn btn-danger btn-sm">Annuler</a>
                            <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $reservations->links() }}
    @else
        <p>Aucune réservation trouvée.</p>
    @endif
</div>

</body>
</html>
@endsection
