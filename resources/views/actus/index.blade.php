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


    <!-- Messages de notification -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Filtres et recherche -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex items-center justify-between w-full">
                <div class="flex-1 min-w-0 relative rounded-lg shadow-sm">
                    <input type="text" 
                           id="search" 
                           class="form-input block w-full pl-10 sm:text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="Rechercher une actualité...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                @if(auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('agent')))
                    <a href="{{ route('actus.create') }}" class="ml-4 inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Ajouter une actualité
                    </a>
                @endif
                </div>
            <div class="flex gap-4">
                <select class="form-select rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Toutes les catégories</option>
                    <option value="Actualités">Actualités</option>
                    <option value="Infos">Infos</option>
                    <option value="Cultures">Cultures</option>
                    <option value="Rendez-vous">Rendez-vous</option>
                </select>
                <select class="form-select rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="recent">Plus récent</option>
                    <option value="ancien">Plus ancien</option>
                </select>
            </div>
        </div>

        <!-- Grille d'actualités -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($actus as $actu)
                <article class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    @if($actu->image)
                        <div class="relative h-48">
                            <img src="{{ asset('storage/' . $actu->image) }}" 
                                 alt="{{ $actu->title }}" 
                                 class="w-full h-full object-cover">
                            <div class="absolute top-2 right-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-500 text-white">
                                    {{ $actu->category }}
                                </span>
                            </div>
                        </div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-500">
                                {{ $actu->created_at->format('d/m/Y') }}
                            </span>
                            @if($actu->external_link)
                                <a href="{{ $actu->external_link }}" 
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            {{ Str::limit($actu->title, 60) }}
                        </h3>
                        <p class="text-gray-600 mb-4">
                            {{ Str::limit($actu->content, 120) }}
                        </p>
                        <div class="flex items-center justify-between mt-4 pt-4 border-t">
                            <a href="{{ route('actus.show', $actu->id) }}" 
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                Lire la suite
                            </a>
                            @if(auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('agent')))
                                <div class="flex space-x-2">
                                    <a href="{{ route('actus.edit', $actu->id) }}" 
                                       class="p-2 text-gray-600 hover:text-blue-600 transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('actus.destroy', $actu->id) }}" 
                                          method="POST" 
                                          class="inline-block"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 text-gray-600 hover:text-red-600 transition-colors duration-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
        @endforeach
        </div>

        <!-- Pagination si nécessaire -->
        {{-- @if($actus->hasPages())
            <div class="mt-8">
                {{ $actus->links() }}
            </div>
        @endif --}}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const articles = document.querySelectorAll('article');

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();

        articles.forEach(article => {
            const title = article.querySelector('h3').textContent.toLowerCase();
            const content = article.querySelector('p').textContent.toLowerCase();

            if (title.includes(searchTerm) || content.includes(searchTerm)) {
                article.style.display = '';
            } else {
                article.style.display = 'none';
            }
        });
    });
});
</script>
@endsection
