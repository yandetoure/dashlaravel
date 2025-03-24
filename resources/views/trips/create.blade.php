<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Créer un Trajet</h2>
    <form action="{{ route('trips.store') }}" method="POST" class="bg-light p-4 rounded shadow-sm">
        @csrf

        <div class="row mb-3">
            <!-- Départ -->
            <div class="col-md-6">
                <label for="departure" class="form-label">Départ</label>
                <input type="text" name="departure" class="form-control" placeholder="Entrez votre lieu de départ" required>
            </div>

            <!-- Arrivée -->
            <div class="col-md-6">
                <label for="destination" class="form-label">Arrivée</label>
                <input type="text" name="destination" class="form-control" placeholder="Entrez votre destination" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Créer</button>
    </form>
</div>
@endsection
