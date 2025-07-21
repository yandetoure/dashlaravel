<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une actualité</title>
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
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6">
            <p class="text-green-700">{{ session('success') }}</p>
        </div>
    @endif
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Liste des infos</h1>
        <a href="{{ route('infos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Ajouter une info</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($infos as $info)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                @if($info->image)
                    <img src="{{ asset('storage/' . $info->image) }}" alt="{{ $info->title }}" class="w-full h-48 object-cover">
                @endif
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-2">{{ $info->title }}</h2>
                    <p class="text-gray-600 mb-4">{{ Str::limit($info->content, 120) }}</p>
                    <div class="flex justify-between items-center">
                        <a href="{{ route('infos.show', $info->id) }}" class="text-blue-600 hover:underline">Voir</a>
                        <div class="flex space-x-2">
                            <a href="{{ route('infos.edit', $info->id) }}" class="text-gray-600 hover:text-blue-600"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('infos.destroy', $info->id) }}" method="POST" onsubmit="return confirm('Supprimer cette info ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
