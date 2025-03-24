<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="container mt-5 d-flex justify-content-center">
    <div class="card shadow-lg p-4 rounded-4" style="max-width: 600px; width: 100%;">
        <h2 class="text-center mb-4 fw-bold text-primary">🚗 Ajouter une voiture</h2>

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

        <form action="{{ route('cars.store') }}" method="POST" class="mt-3">
            @csrf

            <!-- Marque -->
            <div class="mb-4">
                <label for="marque" class="form-label fw-semibold">Marque</label>
                <input type="text" name="marque" id="marque" class="form-control rounded-3 p-3 shadow-sm" 
                       placeholder="Ex: Toyota, BMW..." value="{{ old('marque') }}" required>
            </div>

            <!-- Modèle -->
            <div class="mb-4">
                <label for="model" class="form-label fw-semibold">Modèle</label>
                <input type="text" name="model" id="model" class="form-control rounded-3 p-3 shadow-sm" 
                       placeholder="Ex: Corolla, X5..." value="{{ old('model') }}" required>
            </div>

            <!-- Modèle -->
            <div class="mb-4">
                <label for="color" class="form-label fw-semibold">Couleur</label>
                <input type="text" name="color" id="color" class="form-control rounded-3 p-3 shadow-sm" 
                       placeholder="Ex: Rouge..." value="{{ old('color') }}" required>
            </div>

            <!-- Année -->
            <div class="mb-4">
                <label for="year" class="form-label fw-semibold">Année</label>
                <input type="number" name="year" id="year" class="form-control rounded-3 p-3 shadow-sm" 
                       placeholder="Ex: 2023" value="{{ old('year') }}" required>
            </div>

            <!-- Matricule -->
            <div class="mb-4">
                <label for="matricule" class="form-label fw-semibold">Matricule</label>
                <input type="text" name="matricule" id="matricule" class="form-control rounded-3 p-3 shadow-sm" 
                       placeholder="Ex: AB-123-CD" value="{{ old('matricule') }}" required>
            </div>

            <!-- Bouton -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg fw-bold px-4 py-2 mt-3 rounded-pill">
                    ➕ Ajouter la voiture
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
