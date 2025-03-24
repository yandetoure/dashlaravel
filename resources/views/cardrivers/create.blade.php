<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg p-4 mx-auto" style="max-width: 500px;">
            <h2 class="text-center mb-4">🚗 Assigner un Chauffeur</h2>

            <form action="{{ route('cardrivers.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="car_id" class="form-label fw-bold">🚘 Voiture</label>
                    <select name="car_id" id="car_id" class="form-select shadow-sm">
                        <option selected disabled>-- Sélectionner une voiture --</option>
                        @foreach($cars as $car)
                            <option value="{{ $car->id }}">{{ $car->marque }} - {{ $car->matricule }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="chauffeur_id" class="form-label fw-bold">👨‍✈️ Chauffeur</label>
                    <select name="chauffeur_id" id="chauffeur_id" class="form-select shadow-sm">
                        <option selected disabled>-- Sélectionner un chauffeur --</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}">{{ $driver->first_name }} {{ $driver->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm">
                    ✅ Assigner
                </button>
            </form>
        </div>
    </div>
@endsection
