<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Mes Réservations</h2>

    @if ($reservations->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Chauffeur</th>
                    <th>Voyage</th>
                    <th>Date</th>
                    <th>Heure Ramassage</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->id }}</td>
                        <td>{{ $reservation->chauffeur->first_name }} {{ $reservation->chauffeur->last_name }}</td>
                        <td>{{ $reservation->trip->name }}</td>
                        <td>{{ $reservation->date }}</td>
                        <td>{{ $reservation->heure_ramassage }}</td>
                        <td>
                            <span class="badge 
                                @if($reservation->status == 'confirmed') 
                                    badge-success 
                                @elseif($reservation->status == 'pending') 
                                    badge-warning 
                                @else 
                                    badge-danger 
                                @endif">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $reservations->links() }}
    @else
        <p>Aucune réservation trouvée.</p>
    @endif
</div>
@endsection
