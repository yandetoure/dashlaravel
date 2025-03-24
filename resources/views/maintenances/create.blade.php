<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">➕ Ajouter une Maintenance</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('maintenances.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="car_id" class="form-label fw-bold">🚗 Sélectionner une Voiture</label>
                <select class="form-select" id="car_id" name="car_id" required>
                    <option value="">-- Choisir une voiture --</option>
                    @foreach($cars as $car)
                        <option value="{{ $car->id }}">{{ $car->marque }} - {{ $car->model }} ({{ $car->matricule }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="jour" class="form-label fw-bold">📅 Date</label>
                <input type="date" class="form-control" id="jour" name="jour" required>
            </div>

            <div class="mb-3">
                <label for="heure" class="form-label fw-bold">⏰ Heure</label>
                <input type="time" class="form-control" id="heure" name="heure" required>
            </div>

            <div class="mb-3">
                <label for="motif" class="form-label fw-bold">🔍 Motif</label>
                <input type="text" class="form-control" id="motif" name="motif" required>
            </div>

            <div class="mb-3">
                <label for="diagnostique" class="form-label fw-bold">📝 Diagnostique</label>
                <textarea class="form-control" id="diagnostique" name="diagnostique" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label for="garagiste" class="form-label fw-bold">👨‍🔧 Garagiste</label>
                <input type="text" class="form-control" id="garagiste" name="garagiste" required>
            </div>

            <div class="mb-3">
                <label for="prix" class="form-label fw-bold">💰 Prix (FCFA)</label>
                <input type="number" step="0.01" class="form-control" id="prix" name="prix" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">💳 Statut de Paiement</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="statut" id="statut_non_paye" value="0" checked>
                    <label class="form-check-label" for="statut_non_paye">Non Payé</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="statut" id="statut_paye" value="1">
                    <label class="form-check-label" for="statut_paye">Payé</label>
                </div>
            </div>

            <div class="mb-3">
                <label for="note" class="form-label fw-bold">📝 Note (Optionnel)</label>
                <textarea class="form-control" id="note" name="note" rows="2"></textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success fw-bold shadow-sm px-4">
                    ✅ Ajouter la Maintenance
                </button>
                <a href="{{ route('maintenances.index') }}" class="btn btn-secondary fw-bold shadow-sm px-4">
                    🔙 Retour
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
