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
        <div class="card shadow-lg p-4 mx-auto" style="max-width: 500px;">
            <h2 class="text-center mb-4">🚗 Assigner un Chauffeur</h2>

            @if(session('error'))
                <div class="alert alert-danger mb-3">
                    {{ session('error') }}
                </div>
            @endif


            <form action="{{ route('cardrivers.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="car_id" class="form-label fw-bold">🚘 Voiture</label>
                    <select name="car_id" id="car_id" class="form-select shadow-sm">
                        <option selected disabled>-- Sélectionner une voiture --</option>
                        @foreach($cars as $car)
                            <option value="{{ $car->id }}">
                                {{ $car->marque }} - {{ $car->matricule }}
                                @if($car->assigned_driver)
                                    🔴 Assignée à: {{ $car->assigned_driver->first_name }} {{ $car->assigned_driver->last_name }}
                                @else
                                    🟢 Disponible
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="chauffeur_id" class="form-label fw-bold">👨‍✈️ Chauffeur</label>
                    <select name="chauffeur_id" id="chauffeur_id" class="form-select shadow-sm">
                        <option selected disabled>-- Sélectionner un chauffeur --</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}">
                                {{ $driver->first_name }} {{ $driver->last_name }}
                                @if($driver->assigned_car)
                                    🔴 Assigné à: {{ $driver->assigned_car->marque }} - {{ $driver->assigned_car->matricule }}
                                @else
                                    🟢 Disponible
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm">
                    ✅ Assigner
                </button>
            </form>
        </div>
    </div>

</body>
</html>
@endsection
