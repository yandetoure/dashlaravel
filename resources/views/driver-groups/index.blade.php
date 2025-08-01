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
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card {
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .border-danger {
            border-color: #dc3545 !important;
        }
        .border-success {
            border-color: #198754 !important;
        }
        .btn-group .btn {
            border-radius: 0.375rem !important;
            margin: 0 2px;
        }
        .btn-group .btn:first-child {
            border-top-left-radius: 0.375rem !important;
            border-bottom-left-radius: 0.375rem !important;
        }
        .btn-group .btn:last-child {
            border-top-right-radius: 0.375rem !important;
            border-bottom-right-radius: 0.375rem !important;
        }
        .btn-xs {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            line-height: 1.2;
        }
        .fa-xs {
            font-size: 0.75em;
        }
        .card-body {
            padding: 0.75rem;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-gradient-primary text-white py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-users-cog me-1"></i>Gestion des Groupes de Chauffeurs
                            </h5>
                            <small class="opacity-75">Planification et rotation des équipes</small>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('driver-groups.create') }}" class="btn btn-light btn-sm" title="Créer un nouveau groupe">
                                <i class="fas fa-plus me-1"></i> Nouveau Groupe
                            </a>
                            <a href="{{ route('driver-groups.schedule') }}" class="btn btn-info btn-sm" title="Voir le planning global">
                                <i class="fas fa-calendar-alt me-1"></i> Planning Global
                            </a>
                            <form action="{{ route('driver-groups.auto-assign') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Créer automatiquement les groupes avec tous les chauffeurs ?')" title="Créer automatiquement les groupes">
                                    <i class="fas fa-magic me-1"></i> Auto-Assign
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($groups->count() > 0)
                        <div class="row">
                            @foreach($groups as $group)
                                <div class="col-lg-6 col-md-12 mb-4">
                                    <div class="card h-100 shadow-sm border-0">
                                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2">
                                            <h6 class="mb-0 fw-bold">
                                                <i class="fas fa-users me-1"></i>{{ $group->group_name }}
                                            </h6>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('driver-groups.show', $group) }}" class="btn btn-xs btn-light" title="Voir le planning">
                                                    <i class="fas fa-calendar-alt fa-xs"></i>
                                                </a>
                                                <a href="{{ route('driver-groups.edit', $group) }}" class="btn btn-xs btn-warning" title="Modifier">
                                                    <i class="fas fa-edit fa-xs"></i>
                                                </a>
                                                <form action="{{ route('driver-groups.destroy', $group) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce groupe ?')" title="Supprimer">
                                                        <i class="fas fa-trash fa-xs"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-2">
                                                <div class="col-12">
                                                    <h6 class="text-muted mb-2 small">
                                                        <i class="fas fa-user-friends me-1"></i>Chauffeurs du Groupe
                                                    </h6>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="d-flex align-items-center mb-1">
                                                                <span class="badge bg-secondary me-1 small">1</span>
                                                                <span class="small">{{ $group->driver1 ? $group->driver1->first_name . ' ' . $group->driver1->last_name : 'Non assigné' }}</span>
                                                            </div>
                                                            <div class="d-flex align-items-center mb-1">
                                                                <span class="badge bg-secondary me-1 small">2</span>
                                                                <span class="small">{{ $group->driver2 ? $group->driver2->first_name . ' ' . $group->driver2->last_name : 'Non assigné' }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="d-flex align-items-center mb-1">
                                                                <span class="badge bg-secondary me-1 small">3</span>
                                                                <span class="small">{{ $group->driver3 ? $group->driver3->first_name . ' ' . $group->driver3->last_name : 'Non assigné' }}</span>
                                                            </div>
                                                            <div class="d-flex align-items-center mb-1">
                                                                <span class="badge bg-secondary me-1 small">4</span>
                                                                <span class="small">{{ $group->driver4 ? $group->driver4->first_name . ' ' . $group->driver4->last_name : 'Non assigné' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-12">
                                                    <h6 class="text-muted mb-2 small">
                                                        <i class="fas fa-sync-alt me-1"></i>Rotation Actuelle
                                                    </h6>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge bg-info me-2 small">Jour {{ $group->current_rotation_day_display + 1 }}/4</span>
                                                        </div>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <form action="{{ route('driver-groups.reverse-rotation', $group) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-outline-primary btn-xs" title="Jour précédent">
                                                                    <i class="fas fa-chevron-left fa-xs"></i>
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('driver-groups.reset-rotation', $group) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-outline-warning btn-xs" title="Réinitialiser">
                                                                    <i class="fas fa-redo fa-xs"></i>
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('driver-groups.advance-rotation', $group) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-outline-primary btn-xs" title="Jour suivant">
                                                                    <i class="fas fa-chevron-right fa-xs"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                            
                                            <!-- Section pour les chauffeurs en repos et au travail -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card border-danger border-1">
                                                        <div class="card-header bg-danger text-white py-1">
                                                            <h6 class="mb-0 small">
                                                                <i class="fas fa-bed me-1"></i>Chauffeurs en Repos
                                                            </h6>
                                                        </div>
                                                        <div class="card-body p-2">
                                                            @if(count($group->today_rest_drivers) > 0)
                                                                                                                                    @foreach($group->today_rest_drivers as $driverId)
                                                                        @php
                                                                            $driver = collect([$group->driver1, $group->driver2, $group->driver3, $group->driver4])->firstWhere('id', $driverId);
                                                                            $dayOfRest = $group->rest_driver_details[$driverId] ?? 1;
                                                                            $isInMaintenance = in_array($driverId, $group->maintenance_drivers);
                                                                        @endphp
                                                                        @if($driver)
                                                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                                                <div class="d-flex align-items-center">
                                                                                    <i class="fas fa-user-circle {{ $isInMaintenance ? 'text-warning' : 'text-danger' }} me-1 fa-xs"></i>
                                                                                    <span class="fw-bold small">{{ $driver->first_name }} {{ $driver->last_name }}</span>
                                                                                </div>
                                                                                @if($isInMaintenance)
                                                                                    <span class="badge bg-warning text-dark small">
                                                                                        <i class="fas fa-tools me-1 fa-xs"></i>Maintenance
                                                                                    </span>
                                                                                @else
                                                                                    <span class="badge bg-warning text-dark small">
                                                                                        {{ $dayOfRest == 1 ? '1er jour' : '2ème jour' }}
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                            @else
                                                                <p class="text-muted mb-0 text-center small">
                                                                    <i class="fas fa-info-circle me-1 fa-xs"></i>Aucun chauffeur en repos
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card border-success border-1">
                                                        <div class="card-header bg-success text-white py-1">
                                                            <h6 class="mb-0 small">
                                                                <i class="fas fa-car me-1"></i>Chauffeurs au Travail
                                                            </h6>
                                                        </div>
                                                        <div class="card-body p-2">
                                                            @if(count($group->today_available_drivers) > 0)
                                                                @foreach($group->today_available_drivers as $driverId)
                                                                    @php
                                                                        $driver = collect([$group->driver1, $group->driver2, $group->driver3, $group->driver4])->firstWhere('id', $driverId);
                                                                    @endphp
                                                                    @if($driver)
                                                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                                                            <div class="d-flex align-items-center">
                                                                                <i class="fas fa-user-circle text-success me-1 fa-xs"></i>
                                                                                <span class="fw-bold small">{{ $driver->first_name }} {{ $driver->last_name }}</span>
                                                                            </div>
                                                                            <span class="badge bg-success small">En service</span>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                <p class="text-muted mb-0 text-center small">
                                                                    <i class="fas fa-info-circle me-1 fa-xs"></i>Aucun chauffeur disponible
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Section pour les voitures en maintenance -->
                                            @if(count($group->maintenance_cars) > 0)
                                                <div class="row mt-2">
                                                    <div class="col-12">
                                                        <div class="card border-warning border-1">
                                                            <div class="card-header bg-warning text-dark py-1">
                                                                <h6 class="mb-0 small">
                                                                    <i class="fas fa-tools me-1"></i>Voitures en Maintenance
                                                                </h6>
                                                            </div>
                                                            <div class="card-body p-2">
                                                                @foreach($group->maintenance_cars as $maintenanceData)
                                                                    @php
                                                                        $car = $maintenanceData['car'];
                                                                        $maintenance = $maintenanceData['maintenance'];
                                                                        $carDrivers = $car->drivers;
                                                                    @endphp
                                                                    <div class="mb-2">
                                                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                                                            <div class="d-flex align-items-center">
                                                                                <i class="fas fa-car text-warning me-1 fa-xs"></i>
                                                                                <span class="fw-bold small">{{ $car->marque }} {{ $car->model }} ({{ $car->matricule }})</span>
                                                                            </div>
                                                                            <span class="badge bg-warning text-dark small">{{ $maintenance->statut == 1 ? 'En cours' : 'Terminé' }}</span>
                                                                        </div>
                                                                        @if($carDrivers->count() > 0)
                                                                            <div class="ms-3">
                                                                                <small class="text-muted">Chauffeurs affectés:</small>
                                                                                @foreach($carDrivers as $driver)
                                                                                    <span class="badge bg-secondary small me-1">{{ $driver->first_name }} {{ $driver->last_name }}</span>
                                                                                @endforeach
                                                                            </div>
                                                                        @endif
                                                                        @if($maintenance->motif)
                                                                            <div class="ms-3 mt-1">
                                                                                <small class="text-muted">Motif: {{ $maintenance->motif }}</small>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="mt-2 text-center">
                                                <span class="badge {{ $group->is_active ? 'bg-success' : 'bg-secondary' }} px-2 py-1 small">
                                                    <i class="fas {{ $group->is_active ? 'fa-check-circle' : 'fa-pause-circle' }} me-1 fa-xs"></i>
                                                    {{ $group->is_active ? 'Actif' : 'Inactif' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun groupe de chauffeurs créé</h5>
                            <p class="text-muted">Créez votre premier groupe ou utilisez l'auto-assign pour créer automatiquement les groupes.</p>
                            <a href="{{ route('driver-groups.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Créer un Groupe
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 