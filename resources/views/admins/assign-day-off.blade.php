<?php declare(strict_types=1); ?>

@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

    <div class="container">
        <h1 class="text-center mb-4">Assignation automatique des jours de repos</h1>
        <a href="{{ route('admin.assign-day-off') }}" class="btn btn-success">Assigner un jour de repos</a>
    </div>
@endsection
