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
            <h1 class="text-center mb-4">🔧 Liste des Maintenances</h1>

            @if(session('success'))
                <div class="alert alert-success text-center fw-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="text-center mb-4">
                <a href="{{ route('maintenances.create') }}" class="btn btn-primary fw-bold shadow-sm px-4">
                    ➕ Ajouter une Maintenance
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped shadow-sm rounded table-spacing">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Voiture</th>
                            <th>Jour</th>
                            <th>Heure</th>
                            <th>Motif</th>
                            <th>Garagiste</th>
                            <th>Prix</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        @foreach($maintenances as $maintenance)
                            <tr>
                                <td>{{ $maintenance->car->marque }} {{ $maintenance->car->model }}</td>
                                <td>{{ $maintenance->jour }}</td>
                                <td>{{ $maintenance->heure }}</td>
                                <td>{{ $maintenance->motif }}</td>
                                <td>{{ $maintenance->garagiste }}</td>
                                <td>{{ number_format(floatval($maintenance->prix)) }} FCFA</td>
                                <td>
                                    <span class="badge {{ $maintenance->statut ? 'bg-success' : 'bg-danger' }}">
                                        {{ $maintenance->statut ? 'Payé' : 'Non Payé' }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('maintenances.destroy', $maintenance->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            🗑 Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $maintenances->links() }}
            </div>
        </div>
    </div>
@endsection
