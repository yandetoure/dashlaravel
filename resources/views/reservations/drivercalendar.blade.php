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
    <h1>Calendrier des Réservations</h1>

    <div class="mb-4">
        <form method="GET" action="{{ route('reservations.calendar') }}">
            <select name="month" class="form-control d-inline w-auto">
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                        {{ Carbon::create()->month($i)->format('F') }}
                    </option>
                @endfor
            </select>
            <select name="year" class="form-control d-inline w-auto ml-2">
                @for ($i = Carbon::now()->year - 5; $i <= Carbon::now()->year + 5; $i++)
                    <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>
            <button type="submit" class="btn btn-primary ml-2">Voir</button>
        </form>
    </div>

    <div class="calendar">
        @for ($day = 1; $day <= Carbon::create($year, $month, 1)->daysInMonth; $day++)
            <div class="calendar-day">
                <div class="calendar-date">{{ $day }}</div>
                
                @if ($reservationsByDay->has("$year-$month-$day"))
                    @foreach ($reservationsByDay["$year-$month-$day"] as $reservation)
                        <div class="reservation">
                            <strong>{{ $reservation->client->first_name }} {{ $reservation->client->last_name }}</strong><br>
                            Heure : {{ $reservation->heure_ramassage }}<br>
                            Statut : {{ $reservation->status }}
                        </div>
                    @endforeach
                @else
                    <div class="no-reservation">Aucune réservation</div>
                @endif
            </div>
        @endfor
    </div>
</div>

</body>
</html>
@endsection
