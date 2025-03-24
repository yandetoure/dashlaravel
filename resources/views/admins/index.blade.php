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
            <h1 class="text-center mb-4">📋 Liste des Admins</h1>

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
                        @foreach($admins as $admin)
                            <tr>
                                <td class="px-4">{{ $admin->id }}</td>
                                <td class="px-4">{{ $admin->first_name }}</td>
                                <td class="px-4">{{ $admin->last_name }}</td>
                                <td class="px-4">{{ $admin->email }}</td>
                                <td class="px-4">{{ $admin->address }}</td>
                                <td class="px-4">{{ $admin->phone_number }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $admins->links() }}
            </div>
        </div>
    </div>
@endsection
