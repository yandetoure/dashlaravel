<?php declare(strict_types=1); ?>
<!-- resources/views/drivers/index.blade.php -->

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
            <h1 class="text-center mb-4">📋 Liste des Chauffeurs</h1>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped shadow-sm rounded table-spacing">
                    <thead class="table-dark text-center">
                        <tr>
                            <th class="text-nowrap">ID</th>
                            <th class="text-nowrap">Prénom</th>
                            <th class="text-nowrap">Nom</th>
                            <th class="text-nowrap">Email</th>
                            <th class="text-nowrap">Adresse</th>
                            <th class="text-nowrap">Numéro</th>
                            <th class="text-nowrap">Jour de Repos</th> <!-- Nouveau champ -->
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        @foreach($drivers as $driver)
                            @php
                                // Tableau de correspondance des jours en anglais et en français
                                $daysInFrench = [
                                    'Monday' => 'Lundi',
                                    'Tuesday' => 'Mardi',
                                    'Wednesday' => 'Mercredi',
                                    'Thursday' => 'Jeudi',
                                    'Friday' => 'Vendredi',
                                    'Saturday' => 'Samedi',
                                    'Sunday' => 'Dimanche'
                                ];

                                // Si un jour de repos est assigné, le convertir en français
                                $dayOffInFrench = isset($driver->day_off) ? $daysInFrench[$driver->day_off] ?? 'Non précisé' : 'Non précisé';
                            @endphp

                            <tr>
                                <td class="px-4">{{ $driver->id }}</td>
                                <td class="px-4">{{ $driver->first_name }}</td>
                                <td class="px-4">{{ $driver->last_name }}</td>
                                <td class="px-4">{{ $driver->email }}</td>
                                <td class="px-4">{{ $driver->address }}</td>
                                <td class="px-4">{{ $driver->phone_number }}</td>
                                <td class="px-4">{{ $dayOffInFrench }}</td> <!-- Affichage du jour de repos en français -->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $drivers->links() }}
            </div>
        </div>
    </div>
@endsection
