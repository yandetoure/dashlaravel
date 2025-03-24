<?php declare(strict_types=1); ?>
<!-- resources/views/clients/index.blade.php -->

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
            <h1 class="text-center mb-4">📋 Liste des Clients</h1>

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
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        @foreach($agents as $agent)
                            <tr>
                                <td class="px-4">{{ $agent->id }}</td>
                                <td class="px-4">{{ $agent->first_name }}</td>
                                <td class="px-4">{{ $agent->last_name }}</td>
                                <td class="px-4">{{ $agent->email }}</td>
                                <td class="px-4">{{ $agent->address }}</td>
                                <td class="px-4">{{ $agent->phone_number }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $agents->links() }}
            </div>
        </div>
    </div>
@endsection
