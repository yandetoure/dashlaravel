<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                        dark: '#1F2937',
                    }
                }
            }
        }
    </script>
    <style>
        .reservation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .status-badge {
            top: -10px;
            right: -10px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Nos Actualités</h2>
        <a href="{{ route('actus.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg shadow-lg font-semibold transition duration-200">
            Ajouter une actualité
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($actus as $actu)
            <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition duration-300 relative overflow-hidden">
                @if($actu->image)
                    <img src="{{ asset('storage/' . $actu->image) }}" alt="{{ $actu->title }}" class="w-full h-56 object-cover transition-transform duration-300 hover:scale-105">
                @endif
                <div class="p-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $actu->title }}</h3>
                    <p class="text-gray-600 mb-4">{{ Str::limit($actu->content, 100) }}</p>
                    <a href="{{ route('actus.show', $actu->id) }}" class="text-indigo-600 hover:underline font-semibold text-sm absolute bottom-4 right-4">Voir détails</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
