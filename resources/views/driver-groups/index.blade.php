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
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Gestion des Groupes de Chauffeurs</h4>
                    <div>
                        <a href="{{ route('driver-groups.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nouveau Groupe
                        </a>
                        <a href="{{ route('driver-groups.schedule') }}" class="btn btn-info">
                            <i class="fas fa-calendar"></i> Planning Global
                        </a>
                        <form action="{{ route('driver-groups.auto-assign') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('Créer automatiquement les groupes avec tous les chauffeurs ?')">
                                <i class="fas fa-magic"></i> Auto-Assign
                            </button>
                        </form>
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
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">{{ $group->group_name }}</h5>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('driver-groups.show', $group) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('driver-groups.edit', $group) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('driver-groups.destroy', $group) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce groupe ?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Chauffeurs du Groupe:</h6>
                                                    <ul class="list-unstyled">
                                                        <li><strong>1.</strong> {{ $group->driver1 ? $group->driver1->first_name . ' ' . $group->driver1->last_name : 'Non assigné' }}</li>
                                                        <li><strong>2.</strong> {{ $group->driver2 ? $group->driver2->first_name . ' ' . $group->driver2->last_name : 'Non assigné' }}</li>
                                                        <li><strong>3.</strong> {{ $group->driver3 ? $group->driver3->first_name . ' ' . $group->driver3->last_name : 'Non assigné' }}</li>
                                                        <li><strong>4.</strong> {{ $group->driver4 ? $group->driver4->first_name . ' ' . $group->driver4->last_name : 'Non assigné' }}</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Rotation Actuelle:</h6>
                                                    <p class="mb-2">Jour: {{ $group->current_rotation_day + 1 }}/4</p>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <form action="{{ route('driver-groups.reverse-rotation', $group) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-secondary">
                                                                <i class="fas fa-chevron-left"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('driver-groups.reset-rotation', $group) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-warning">
                                                                <i class="fas fa-redo"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('driver-groups.advance-rotation', $group) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-secondary">
                                                                <i class="fas fa-chevron-right"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <span class="badge {{ $group->is_active ? 'bg-success' : 'bg-secondary' }}">
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