<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Détails de la voiture</h1>
    <ul class="list-group">
        <li class="list-group-item"><strong>Marque :</strong> {{ $car->marque }}</li>
        <li class="list-group-item"><strong>Modèle :</strong> {{ $car->model }}</li>
        <li class="list-group-item"><strong>Couleur :</strong> {{ $car->color }}</li>
        <li class="list-group-item"><strong>Année :</strong> {{ $car->year }}</li>
        <li class="list-group-item"><strong>Matricule :</strong> {{ $car->matricule }}</li>
    </ul>
    <a href="{{ route('cars.index') }}" class="btn btn-secondary mt-3">Retour</a>
</div>
@endsection
