<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
    <div class="container mt-5">
    <style>
        .table-spacing td, .table-spacing th {
            padding-left: 25px;
            padding-right: 25px;
        }
    </style>

        <div class="card shadow-lg p-4">
            <h1 class="text-center mb-4">🗺️ Liste des Trajets</h1>

            <a href="{{ route('trips.create') }}" class="btn btn-success mb-3">Ajouter un Trajet</a>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped shadow-sm rounded table-spacing">
                    <thead class="table-dark text-center">
                        <tr>
                            <th class="text-nowrap">Départ</th>
                            <th class="text-nowrap">Arrivée</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        @foreach($trips as $trip)
                            <tr>
                                <td class="px-4">{{ $trip->departure }}</td>
                                <td class="px-4">{{ $trip->destination }}</td>
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
    </div>
@endsection
