<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier la voiture</h1>
    <form action="{{ route('cars.update', $car) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Marque</label>
            <input type="text" name="marque" class="form-control" value="{{ $car->marque }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Modèle</label>
            <input type="text" name="model" class="form-control" value="{{ $car->model }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Couleur</label>
            <input type="text" name="color" class="form-control" value="{{ $car->color }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Année</label>
            <input type="number" name="year" class="form-control" value="{{ $car->year }}" min="1900" max="{{ date('Y') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Matricule</label>
            <input type="text" name="matricule" class="form-control" value="{{ $car->matricule }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Modifier</button>
    </form>
</div>
@endsection
