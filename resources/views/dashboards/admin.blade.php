<?php declare(strict_types=1); ?>
<!-- Exemple de la vue dashboards/admin.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Tableau de bord Admin</h1>

    <!-- Vérifie si les statistiques existent -->
    @if(isset($statistics))
        <ul>
            @foreach ($statistics as $role => $count)
                <li>{{ ucfirst($role) }} : {{ $count }}</li>
            @endforeach
        </ul>
    @else
        <p>Aucune donnée disponible.</p>
    @endif
@endsection
