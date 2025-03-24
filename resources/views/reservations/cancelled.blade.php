<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Réservations Annulées</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->id }}</td>
                    <td>{{ $reservation->client->name }}</td>
                    <td>{{ $reservation->date }}</td>
                    <td><span class="badge bg-danger">Annulée</span></td>
                    <td>
                        <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-info btn-sm">Voir</a>
                        <form action="{{ route('reservations.restore', $reservation->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success btn-sm">Restaurer</button>
                        </form>
                        <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer Définitivement</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
