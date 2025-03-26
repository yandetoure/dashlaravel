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
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="text-center">📋 Liste des Chauffeurs</h1>
                <!-- Formulaire pour assigner un jour de repos -->
                <form action="{{ route('admin.assign-day-off') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">Assigner un jour de repos</button>
                </form>
            </div>

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
                            <th class="text-nowrap">Jour de Repos</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        @foreach($drivers as $driver)       
                            <tr>
                                <td class="px-4">{{ $driver->id }}</td>
                                <td class="px-4">{{ $driver->first_name }}</td>
                                <td class="px-4">{{ $driver->last_name }}</td>
                                <td class="px-4">{{ $driver->email }}</td>
                                <td class="px-4">{{ $driver->address }}</td>
                                <td class="px-4">{{ $driver->phone_number }}</td>
                                <td class="px-4">{{ $driver->day_off ?? 'Non précisé' }}</td> 
                          </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $drivers->links() }}
            </div>
        </div>
    </div>
@endsection
