<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="container">
    <!-- En-tête stylisé -->
    <div class="bg-light p-4 rounded shadow-sm mb-4">
        <h1 class="mb-3 text-primary text-center">🗺️ Liste des Trajets</h1>
        <div class="d-flex justify-content-between align-items-center">
            <div></div> <!-- Placeholder pour équilibrer l'alignement -->
            <a href="{{ route('trips.create') }}" class="btn btn-success fw-bold">
                + Ajouter un Trajet
            </a>
        </div>
    </div>

    <!-- Tableau des trajets -->
    <div class="table-responsive">
        <table class="table table-hover shadow-sm">
            <thead class="table-dark text-center">
                <tr>
                    <th>Départ</th>
                    <th>Arrivée</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="text-center align-middle">
                @foreach($trips as $trip)
                    <tr>
                        <td>{{ $trip->departure }}</td>
                        <td>{{ $trip->destination }}</td>
                        <td>
                            <a href="{{ route('trips.edit', $trip->id) }}" class="btn btn-sm btn-outline-info">Modifier</a>
                            <form action="{{ route('trips.destroy', $trip->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Voulez-vous vraiment supprimer ce trajet ?')">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $trips->links() }}
    </div>
</div>
@endsection
