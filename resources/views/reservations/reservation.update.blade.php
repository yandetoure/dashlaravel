<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')

<form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
    @csrf
    @method('PUT') <!-- Important pour spécifier que c'est une requête PUT -->

    <div class="mb-3">
        <label for="date" class="form-label">Date</label>
        <input type="date" class="form-control" id="date" name="date" value="{{ \Carbon\Carbon::parse($reservation->date)->format('Y-m-d') }}" required>
    </div>

    <div class="mb-3">
        <label for="heure_ramassage" class="form-label">Heure Ramassage</label>
        <input type="time" class="form-control" id="heure_ramassage" name="heure_ramassage" value="{{ \Carbon\Carbon::parse($reservation->heure_ramassage)->format('H:i') }}" required>
    </div>

    <div class="mb-3">
        <label for="heure_vol" class="form-label">Heure Vol</label>
        <input type="time" class="form-control" id="heure_vol" name="heure_vol" value="{{ \Carbon\Carbon::parse($reservation->heure_vol)->format('H:i') }}">
    </div>

    
    <button type="submit" class="btn btn-primary">Sauvegarder les modifications</button>
</form>

@endsection