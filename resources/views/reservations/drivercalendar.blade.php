<?php declare(strict_types=1); ?>
<?php use Carbon\Carbon; ?>

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Calendrier des Réservations</h1>

    <div class="mb-4">
        <form method="GET" action="{{ route('reservations.calendar') }}">
            <select name="month" class="form-control d-inline w-auto">
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                        {{ Carbon::create()->month($i)->format('F') }}
                    </option>
                @endfor
            </select>
            <select name="year" class="form-control d-inline w-auto ml-2">
                @for ($i = Carbon::now()->year - 5; $i <= Carbon::now()->year + 5; $i++)
                    <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>
            <button type="submit" class="btn btn-primary ml-2">Voir</button>
        </form>
    </div>

    <div class="calendar">
        @for ($day = 1; $day <= Carbon::create($year, $month, 1)->daysInMonth; $day++)
            <div class="calendar-day">
                <div class="calendar-date">{{ $day }}</div>
                
                @if ($reservationsByDay->has("$year-$month-$day"))
                    @foreach ($reservationsByDay["$year-$month-$day"] as $reservation)
                        <div class="reservation">
                            <strong>{{ $reservation->client->first_name }} {{ $reservation->client->last_name }}</strong><br>
                            Heure : {{ $reservation->heure_ramassage }}<br>
                            Statut : {{ $reservation->status }}
                        </div>
                    @endforeach
                @else
                    <div class="no-reservation">Aucune réservation</div>
                @endif
            </div>
        @endfor
    </div>
</div>
@endsection
