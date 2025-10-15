@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Courses</title>
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
        .course-card:hover {
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
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- En-tête -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                <span class="inline-block mr-2">⭐</span> Évaluer la Course
            </h1>
            <p class="mt-2 text-gray-600">Donnez votre avis sur le trajet effectué</p>
            @if(session('success'))
                <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Informations de la course -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Détails de la course</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Client</p>
                    <p class="font-medium">
                        @if($course->reservation && $course->reservation->client)
                            {{ $course->reservation->client->first_name }} {{ $course->reservation->client->last_name }}
                        @else
                            {{ $course->reservation->first_name ?? 'N/A' }} {{ $course->reservation->last_name ?? 'N/A' }}
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Chauffeur</p>
                    <p class="font-medium">
                        @if($course->reservation && $course->reservation->carDriver && $course->reservation->carDriver->chauffeur)
                            {{ $course->reservation->carDriver->chauffeur->first_name }} {{ $course->reservation->carDriver->chauffeur->last_name }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Date</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($course->reservation->date)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Heure</p>
                    <p class="font-medium">{{ $course->reservation->heure_ramassage }}</p>
                </div>
                @if($course->debut_course && $course->fin_course)
                <div>
                    <p class="text-sm text-gray-500">Durée</p>
                    <p class="font-medium">{{ $course->duree_course }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Message informatif -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Votre avis compte !</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>Votre évaluation nous aide à améliorer notre service. Si vous êtes satisfait ou déçu, vous pouvez ajouter des commentaires détaillés.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire de notation -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <form action="{{ route('courses.noter', $course->id) }}" method="POST">
                @csrf
                
                <!-- Note générale -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Comment évaluez-vous cette course ?
                    </label>
                    <div class="space-y-3">
                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                            <input type="radio" name="note" value="satisfait" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300" required>
                            <span class="ml-3 flex items-center">
                                <span class="text-green-600 mr-2">😊</span>
                                <span class="font-medium text-green-700">Satisfait</span>
                                <span class="ml-2 text-sm text-gray-500">Service excellent</span>
                            </span>
                        </label>
                        
                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                            <input type="radio" name="note" value="neutre" class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-300" required>
                            <span class="ml-3 flex items-center">
                                <span class="text-gray-600 mr-2">😐</span>
                                <span class="font-medium text-gray-700">Neutre</span>
                                <span class="ml-2 text-sm text-gray-500">Service correct</span>
                            </span>
                        </label>
                        
                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                            <input type="radio" name="note" value="decu" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300" required>
                            <span class="ml-3 flex items-center">
                                <span class="text-red-600 mr-2">😞</span>
                                <span class="font-medium text-red-700">Déçu</span>
                                <span class="ml-2 text-sm text-gray-500">Service décevant</span>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Commentaires positifs -->
                <div class="mb-6" id="commentaire-positif" style="display: none;">
                    <label for="commentaire_positif" class="block text-sm font-medium text-gray-700 mb-2">
                        Que s'est-il bien passé ? <span class="text-green-600">✨</span>
                    </label>
                    <textarea 
                        name="commentaire_positif" 
                        id="commentaire_positif" 
                        rows="3" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                        placeholder="Décrivez ce qui vous a plu dans ce trajet..."
                    ></textarea>
                </div>

                <!-- Commentaires négatifs -->
                <div class="mb-6" id="commentaire-negatif" style="display: none;">
                    <label for="commentaire_negatif" class="block text-sm font-medium text-gray-700 mb-2">
                        Que pourrait-on améliorer ? <span class="text-red-600">🔧</span>
                    </label>
                    <textarea 
                        name="commentaire_negatif" 
                        id="commentaire_negatif" 
                        rows="3" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm"
                        placeholder="Décrivez ce qui pourrait être amélioré..."
                    ></textarea>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <a href="{{ route('courses.show', $course->id) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        Retour aux détails
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Enregistrer l'évaluation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const radioButtons = document.querySelectorAll('input[name="note"]');
    const commentairePositif = document.getElementById('commentaire-positif');
    const commentaireNegatif = document.getElementById('commentaire-negatif');
    const textareaPositif = document.getElementById('commentaire_positif');
    const textareaNegatif = document.getElementById('commentaire_negatif');

    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            // Masquer tous les commentaires
            commentairePositif.style.display = 'none';
            commentaireNegatif.style.display = 'none';
            
            // Vider les textareas
            textareaPositif.value = '';
            textareaNegatif.value = '';
            
            // Afficher les commentaires selon la sélection
            if (this.value === 'satisfait') {
                commentairePositif.style.display = 'block';
                textareaPositif.required = false; // Optionnel pour satisfait
            } else if (this.value === 'decu') {
                commentaireNegatif.style.display = 'block';
                textareaNegatif.required = true; // Requis pour déçu
            }
        });
    });
});
</script>
@endsection
