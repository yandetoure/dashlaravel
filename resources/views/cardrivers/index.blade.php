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
            <h1 class="text-center mb-4">🚗 Liste des Voitures & Chauffeurs</h1>

            <!-- Message de succès -->
            @if(session('success'))
                <div class="alert alert-success text-center fw-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Bouton d'ajout -->
            <div class="text-center mb-4">
                <a href="{{ route('cardrivers.create') }}" class="btn btn-primary fw-bold shadow-sm px-4">
                    ➕ Assigner un Chauffeur
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped shadow-sm rounded table-spacing">
                    <thead class="table-dark text-center">
                        <tr>
                            <th class="text-nowrap">Voiture</th>
                            <th class="text-nowrap">Matricule</th>
                            <th class="text-nowrap">Chauffeurs</th>
                            <th class="text-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        @foreach($cars as $car)
                            <tr>
                                <td class="px-4 fw-bold">{{ $car->marque }} {{ $car->model }}</td>
                                <td class="px-4">{{ $car->matricule }}</td>
                                <td class="px-4">
                                    @if($car->drivers->isEmpty())
                                        <span class="text-danger">Aucun chauffeur</span>
                                    @else
                                        @foreach($car->drivers as $driver)
                                            <span class="badge bg-success p-2 me-1">
                                                {{ $driver->first_name }} {{ $driver->last_name }}
                                            </span>
                                        @endforeach
                                    @endif
                                </td>
                                <td class="px-4">
                                    @foreach($car->drivers as $driver)
                                        <form action="{{ route('car_drivers.destroy', ['car_id' => $car->id, 'user_id' => $driver->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm shadow-sm">
                                                ❌ Retirer {{ $driver->first_name }}
                                            </button>
                                        </form>
                                    @endforeach
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
