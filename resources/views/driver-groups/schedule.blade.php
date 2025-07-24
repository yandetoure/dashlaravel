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
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Planning Global des Groupes de Chauffeurs</h4>
                    <a href="{{ route('driver-groups.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($groups->count() > 0)
                        @foreach($groups as $group)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ $group->group_name }}</h5>
                                    <small class="text-muted">
                                        Rotation actuelle: Jour {{ $group->current_rotation_day + 1 }}/4
                                    </small>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Jour</th>
                                                    <th>Date</th>
                                                    <th>Chauffeur 1</th>
                                                    <th>Chauffeur 2</th>
                                                    <th>Chauffeur 3</th>
                                                    <th>Chauffeur 4</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($weeklySchedules[$group->id] as $date => $schedule)
                                                    <tr>
                                                        <td>
                                                            <strong>{{ $schedule['day_name'] }}</strong>
                                                        </td>
                                                        <td>{{ $schedule['date']->format('d/m/Y') }}</td>
                                                        <td class="{{ in_array($group->driver_1_id, $schedule['rest_drivers']) ? 'table-danger' : 'table-success' }}">
                                                            @if($group->driver1)
                                                                {{ $group->driver1->first_name }} {{ $group->driver1->last_name }}
                                                                @if(in_array($group->driver_1_id, $schedule['rest_drivers']))
                                                                    <span class="badge bg-danger">Repos</span>
                                                                @else
                                                                    <span class="badge bg-success">Disponible</span>
                                                                @endif
                                                            @else
                                                                Non assigné
                                                            @endif
                                                        </td>
                                                        <td class="{{ in_array($group->driver_2_id, $schedule['rest_drivers']) ? 'table-danger' : 'table-success' }}">
                                                            @if($group->driver2)
                                                                {{ $group->driver2->first_name }} {{ $group->driver2->last_name }}
                                                                @if(in_array($group->driver_2_id, $schedule['rest_drivers']))
                                                                    <span class="badge bg-danger">Repos</span>
                                                                @else
                                                                    <span class="badge bg-success">Disponible</span>
                                                                @endif
                                                            @else
                                                                Non assigné
                                                            @endif
                                                        </td>
                                                        <td class="{{ in_array($group->driver_3_id, $schedule['rest_drivers']) ? 'table-danger' : 'table-success' }}">
                                                            @if($group->driver3)
                                                                {{ $group->driver3->first_name }} {{ $group->driver3->last_name }}
                                                                @if(in_array($group->driver_3_id, $schedule['rest_drivers']))
                                                                    <span class="badge bg-danger">Repos</span>
                                                                @else
                                                                    <span class="badge bg-success">Disponible</span>
                                                                @endif
                                                            @else
                                                                Non assigné
                                                            @endif
                                                        </td>
                                                        <td class="{{ in_array($group->driver_4_id, $schedule['rest_drivers']) ? 'table-danger' : 'table-success' }}">
                                                            @if($group->driver4)
                                                                {{ $group->driver4->first_name }} {{ $group->driver4->last_name }}
                                                                @if(in_array($group->driver_4_id, $schedule['rest_drivers']))
                                                                    <span class="badge bg-danger">Repos</span>
                                                                @else
                                                                    <span class="badge bg-success">Disponible</span>
                                                                @endif
                                                            @else
                                                                Non assigné
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <small class="text-muted">
                                                                {{ count($schedule['available_drivers']) }} disponible(s)
                                                            </small>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-3">
                                        <div class="btn-group" role="group">
                                            <form action="{{ route('driver-groups.reverse-rotation', $group) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-chevron-left"></i> Reculer
                                                </button>
                                            </form>
                                            <form action="{{ route('driver-groups.reset-rotation', $group) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-redo"></i> Reset
                                                </button>
                                            </form>
                                            <form action="{{ route('driver-groups.advance-rotation', $group) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-chevron-right"></i> Avancer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Résumé global -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Résumé Global</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Chauffeurs disponibles aujourd'hui:</h6>
                                        @php
                                            $today = \Carbon\Carbon::now()->format('Y-m-d');
                                            $availableToday = [];
                                            foreach($groups as $group) {
                                                $availableToday = array_merge($availableToday, $weeklySchedules[$group->id][$today]['available_drivers']);
                                            }
                                            $availableDrivers = \App\Models\User::whereIn('id', $availableToday)->get();
                                        @endphp
                                        <ul class="list-group">
                                            @foreach($availableDrivers as $driver)
                                                <li class="list-group-item list-group-item-success">
                                                    {{ $driver->first_name }} {{ $driver->last_name }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Chauffeurs en repos aujourd'hui:</h6>
                                        @php
                                            $restToday = [];
                                            foreach($groups as $group) {
                                                $restToday = array_merge($restToday, $weeklySchedules[$group->id][$today]['rest_drivers']);
                                            }
                                            $restDrivers = \App\Models\User::whereIn('id', $restToday)->get();
                                        @endphp
                                        <ul class="list-group">
                                            @foreach($restDrivers as $driver)
                                                <li class="list-group-item list-group-item-danger">
                                                    {{ $driver->first_name }} {{ $driver->last_name }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun groupe de chauffeurs actif</h5>
                            <p class="text-muted">Créez des groupes de chauffeurs pour voir le planning.</p>
                            <a href="{{ route('driver-groups.index') }}" class="btn btn-primary">
                                <i class="fas fa-users"></i> Gérer les Groupes
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 