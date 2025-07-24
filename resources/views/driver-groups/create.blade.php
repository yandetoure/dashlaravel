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
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Créer un Nouveau Groupe de Chauffeurs</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('driver-groups.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="group_name" class="form-label">Nom du Groupe</label>
                            <input type="text" class="form-control @error('group_name') is-invalid @enderror" 
                                   id="group_name" name="group_name" value="{{ old('group_name') }}" required>
                            @error('group_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="driver_1_id" class="form-label">Chauffeur 1</label>
                                    <select class="form-select @error('driver_1_id') is-invalid @enderror" 
                                            id="driver_1_id" name="driver_1_id" required>
                                        <option value="">Sélectionner un chauffeur</option>
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}" {{ old('driver_1_id') == $driver->id ? 'selected' : '' }}>
                                                {{ $driver->first_name }} {{ $driver->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('driver_1_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="driver_2_id" class="form-label">Chauffeur 2</label>
                                    <select class="form-select @error('driver_2_id') is-invalid @enderror" 
                                            id="driver_2_id" name="driver_2_id" required>
                                        <option value="">Sélectionner un chauffeur</option>
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}" {{ old('driver_2_id') == $driver->id ? 'selected' : '' }}>
                                                {{ $driver->first_name }} {{ $driver->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('driver_2_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="driver_3_id" class="form-label">Chauffeur 3</label>
                                    <select class="form-select @error('driver_3_id') is-invalid @enderror" 
                                            id="driver_3_id" name="driver_3_id" required>
                                        <option value="">Sélectionner un chauffeur</option>
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}" {{ old('driver_3_id') == $driver->id ? 'selected' : '' }}>
                                                {{ $driver->first_name }} {{ $driver->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('driver_3_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="driver_4_id" class="form-label">Chauffeur 4</label>
                                    <select class="form-select @error('driver_4_id') is-invalid @enderror" 
                                            id="driver_4_id" name="driver_4_id" required>
                                        <option value="">Sélectionner un chauffeur</option>
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}" {{ old('driver_4_id') == $driver->id ? 'selected' : '' }}>
                                                {{ $driver->first_name }} {{ $driver->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('driver_4_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Système de Rotation</h6>
                            <p class="mb-0">
                                <strong>Jour 1:</strong> Chauffeur 1 et 2 en repos<br>
                                <strong>Jour 2:</strong> Chauffeur 2 et 3 en repos<br>
                                <strong>Jour 3:</strong> Chauffeur 3 et 4 en repos<br>
                                <strong>Jour 4:</strong> Chauffeur 4 et 1 en repos
                            </p>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('driver-groups.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Créer le Groupe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Empêcher la sélection du même chauffeur plusieurs fois
document.addEventListener('DOMContentLoaded', function() {
    const selects = ['driver_1_id', 'driver_2_id', 'driver_3_id', 'driver_4_id'];
    
    selects.forEach(function(selectId) {
        const select = document.getElementById(selectId);
        select.addEventListener('change', function() {
            const selectedValue = this.value;
            const otherSelects = selects.filter(id => id !== selectId);
            
            otherSelects.forEach(function(otherSelectId) {
                const otherSelect = document.getElementById(otherSelectId);
                Array.from(otherSelect.options).forEach(function(option) {
                    if (option.value === selectedValue && selectedValue !== '') {
                        option.disabled = true;
                    } else {
                        option.disabled = false;
                    }
                });
            });
        });
    });
});
</script>
@endsection 