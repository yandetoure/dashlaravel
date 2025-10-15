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
    <div class="max-w-4xl mx-auto">
        <!-- En-tête -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <span class="inline-block mr-2">🚗</span> Détails de la Course
                </h1>
                <p class="mt-2 text-gray-600">Informations complètes sur le trajet</p>
            </div>
            <div class="flex space-x-3">
                @if($course->statut === 'terminee' && !$course->note)
                    <a href="{{ route('courses.notation', $course->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition-colors">
                        ⭐ Noter la course
                    </a>
                @endif
                <a href="{{ route('courses.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                    ← Retour à la liste
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informations principales -->
            <div class="lg:col-span-2">
                <!-- Statut et informations de base -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Informations générales</h2>
                        @php
                            $statutColors = [
                                'en_attente' => 'bg-yellow-100 text-yellow-800',
                                'en_cours' => 'bg-blue-100 text-blue-800',
                                'terminee' => 'bg-green-100 text-green-800',
                                'annulee' => 'bg-red-100 text-red-800'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statutColors[$course->statut] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $course->statut_francais }}
                        </span>
                    </div>
                    
                    @if($course->debut_course && $course->fin_course)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Timing</h3>
                            <div class="space-y-2 text-sm">
                                <div>
                                    <span class="text-gray-500">Début:</span>
                                    <span class="ml-2 font-medium">{{ $course->debut_course->format('d/m/Y H:i') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Fin:</span>
                                    <span class="ml-2 font-medium">{{ $course->fin_course->format('d/m/Y H:i') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Durée:</span>
                                    <span class="ml-2 font-medium text-blue-600">{{ $course->duree_course }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Informations de la réservation -->
                @if($course->reservation)
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Détails de la réservation</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Client</h3>
                            <div class="space-y-2 text-sm">
                                @if($course->reservation->client)
                                    <div><span class="text-gray-500">Nom:</span> <span class="ml-2 font-medium">{{ $course->reservation->client->first_name }} {{ $course->reservation->client->last_name }}</span></div>
                                    <div><span class="text-gray-500">Email:</span> <span class="ml-2">{{ $course->reservation->client->email }}</span></div>
                                    @if($course->reservation->client->phone_number)
                                        <div><span class="text-gray-500">Téléphone:</span> <span class="ml-2">{{ $course->reservation->client->phone_number }}</span></div>
                                    @endif
                                @else
                                    <div><span class="text-gray-500">Nom:</span> <span class="ml-2 font-medium">{{ $course->reservation->first_name }} {{ $course->reservation->last_name }}</span></div>
                                    <div><span class="text-gray-500">Email:</span> <span class="ml-2">{{ $course->reservation->email }}</span></div>
                                    @if($course->reservation->phone_number)
                                        <div><span class="text-gray-500">Téléphone:</span> <span class="ml-2">{{ $course->reservation->phone_number }}</span></div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Trajet</h3>
                            <div class="space-y-2 text-sm">
                                <div><span class="text-gray-500">Date:</span> <span class="ml-2 font-medium">{{ \Carbon\Carbon::parse($course->reservation->date)->format('d/m/Y') }}</span></div>
                                <div><span class="text-gray-500">Heure ramassage:</span> <span class="ml-2">{{ $course->reservation->heure_ramassage }}</span></div>
                                <div><span class="text-gray-500">Adresse:</span> <span class="ml-2">{{ $course->reservation->adresse_rammassage }}</span></div>
                                @if($course->reservation->heure_vol)
                                    <div><span class="text-gray-500">Heure vol:</span> <span class="ml-2">{{ $course->reservation->heure_vol }}</span></div>
                                @endif
                                @if($course->reservation->numero_vol)
                                    <div><span class="text-gray-500">Numéro vol:</span> <span class="ml-2">{{ $course->reservation->numero_vol }}</span></div>
                                @endif
                                <div><span class="text-gray-500">Personnes:</span> <span class="ml-2">{{ $course->reservation->nb_personnes }}</span></div>
                                <div><span class="text-gray-500">Valises:</span> <span class="ml-2">{{ $course->reservation->nb_valises }}</span></div>
                                <div><span class="text-gray-500">Tarif:</span> <span class="ml-2 font-medium text-green-600">{{ number_format($course->reservation->tarif, 0, ',', ' ') }} FCFA</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Chauffeur -->
                @if($course->reservation && $course->reservation->carDriver && $course->reservation->carDriver->chauffeur)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Chauffeur assigné</h3>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gray-200 rounded-full mx-auto mb-3 flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h4 class="font-medium text-gray-900">{{ $course->reservation->carDriver->chauffeur->first_name }} {{ $course->reservation->carDriver->chauffeur->last_name }}</h4>
                        @if($course->reservation->carDriver->chauffeur->phone_number)
                            <p class="text-sm text-gray-500 mt-1">{{ $course->reservation->carDriver->chauffeur->phone_number }}</p>
                        @endif
                        @if($course->reservation->carDriver->chauffeur->email)
                            <p class="text-sm text-gray-500">{{ $course->reservation->carDriver->chauffeur->email }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Évaluation -->
                @if($course->note)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Évaluation</h3>
                    @php
                        $noteColors = [
                            'satisfait' => 'text-green-600',
                            'neutre' => 'text-gray-600',
                            'decu' => 'text-red-600'
                        ];
                        $noteIcons = [
                            'satisfait' => '😊',
                            'neutre' => '😐',
                            'decu' => '😞'
                        ];
                    @endphp
                    <div class="text-center">
                        <div class="text-4xl mb-2">{{ $noteIcons[$course->note] ?? '⭐' }}</div>
                        <p class="font-medium {{ $noteColors[$course->note] ?? 'text-gray-600' }}">{{ $course->note_francais }}</p>
                    </div>
                    
                    @if($course->commentaire_positif)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-green-700 mb-2">Points positifs ✨</h4>
                        <p class="text-sm text-gray-600 bg-green-50 p-3 rounded-lg">{{ $course->commentaire_positif }}</p>
                    </div>
                    @endif
                    
                    @if($course->commentaire_negatif)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-red-700 mb-2">Points d'amélioration 🔧</h4>
                        <p class="text-sm text-gray-600 bg-red-50 p-3 rounded-lg">{{ $course->commentaire_negatif }}</p>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Actions rapides -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        @if($course->statut === 'en_attente')
                            <form action="{{ route('courses.demarrer', $course->id) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                    🚀 Démarrer la course
                                </button>
                            </form>
                        @endif

                        @if($course->statut === 'en_cours')
                            <form action="{{ route('courses.terminer', $course->id) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                    ✅ Terminer la course
                                </button>
                            </form>
                        @endif

                        @if(in_array($course->statut, ['en_attente', 'en_cours']))
                            <form action="{{ route('courses.annuler', $course->id) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" onclick="return confirm('Voulez-vous vraiment annuler cette course ?')" class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                    ❌ Annuler la course
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('courses.edit', $course->id) }}" class="block w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors text-center">
                            ✏️ Modifier
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
