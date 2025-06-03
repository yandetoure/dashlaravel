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
<div class="max-w-6xl mx-auto p-4 flex flex-col md:flex-row gap-4">

    <!-- Contenu principal -->
    <main class="flex-1 p-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Image -->
    @if ($actu->image)
                    <div class="relative h-96">
                        <img src="{{ asset('storage/' . $actu->image) }}" 
                             alt="{{ $actu->title }}" 
                             class="w-full h-full object-cover">
                        <div class="absolute top-4 right-4">
                            <span class="bg-blue-500 text-white px-4 py-2 rounded-full text-sm font-semibold">
                                {{ $actu->category === 'Infos utiles' ? 'infos utiles' : $actu->category }}
                            </span>
                        </div>
                    </div>
    @endif

                <!-- Contenu -->
                <div class="p-8">
                    <div class="flex justify-between items-start mb-6">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $actu->title }}</h1>
                        <div class="text-sm text-gray-500">
                            Publié le {{ $actu->created_at->format('d/m/Y à H:i') }}
                        </div>
                    </div>

                    <!-- Contenu principal -->
                    <div class="prose max-w-none mb-8">
        {!! nl2br(e($actu->content)) !!}
    </div>

                    <!-- Lien externe si présent -->
                    @if($actu->external_link)
                        <div class="mt-6 border-t pt-6">
                            <a href="{{ $actu->external_link }}" 
                               target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                                <span>Cliquez ici</span>
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                    @endif

                    <!-- Métadonnées supplémentaires -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <div>
                                Dernière modification : {{ $actu->updated_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('actus.index') }}" 
                           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                            Retour à la liste
                        </a>

                        @if(auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('agent')))
                            <a href="{{ route('actus.edit', $actu->id) }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                Modifier
                            </a>

                            <form action="{{ route('actus.destroy', $actu->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?')"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                                    Supprimer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
    </div>
    </main>
</div>
@endsection
