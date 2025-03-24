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
        <h1 class="text-center mb-4">🚗 Liste des voitures</h1>

        <!-- Bouton pour ajouter une nouvelle voiture -->
        <div class="text-end mb-4">
            <a href="{{ route('cars.create') }}" class="btn btn-primary">➕ Ajouter une voiture</a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped shadow-sm rounded table-spacing">
                <thead class="table-dark text-center">
                    <tr>
                        <th class="text-nowrap">Marque</th>
                        <th class="text-nowrap">Modèle</th>
                        <th class="text-nowrap">Année</th>
                        <th class="text-nowrap">Matricule</th>
                        <th class="text-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center align-middle">
                    @foreach ($cars as $car)
                        <tr>
                            <td class="px-4">{{ $car->marque }}</td>
                            <td class="px-4">{{ $car->model }}</td>
                            <td class="px-4">{{ $car->year }}</td>
                            <td class="px-4">{{ $car->matricule }}</td>
                            <td class="px-4">
                                <!-- Boutons bien espacés -->
                                <div class="gap-2 d-flex justify-content-center">
                                    <a href="{{ route('cars.show', $car->id) }}" class="btn btn-info btn-sm">👁 Voir</a>
                                    <a href="{{ route('cars.edit', $car->id) }}" class="btn btn-warning btn-sm">✏ Modifier</a>

                                    <form action="{{ route('cars.destroy', $car->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette voiture ?')">🗑 Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $cars->links() }}
        </div>
    </div>
</div>
@endsection
