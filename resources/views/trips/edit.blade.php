<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4">Modifier le trajet</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('trips.update', $trip->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="departure" class="form-label">Lieu de départ</label>
                <input type="text" class="form-control" id="departure" name="departure" 
                    value="{{ old('departure', $trip->departure) }}" required>
            </div>

            <div class="mb-3">
                <label for="destination" class="form-label">Destination</label>
                <input type="text" class="form-control" id="destination" name="destination" 
                    value="{{ old('destination', $trip->destination) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="{{ route('trips.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
@endsection
