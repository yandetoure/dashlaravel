<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier la Réservation</h1>

    <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="client_id">Client</label>
            <select name="client_id" id="client_id" class="form-control">
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ $reservation->client_id == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Repeat form fields as in create.blade.php -->
        <!-- ... -->

        <button type="submit" class="btn btn-success mt-3">Mettre à jour</button>
    </form>
</div>
@endsection
