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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Modifier l'actualité</h1>
                    <p class="mt-1 text-sm text-gray-600">Modifiez les informations de l'actualité et prévisualisez les changements</p>
                </div>
                <a href="{{ route('actus.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour aux actualités
                </a>
            </div>
        </div>

        <!-- Messages d'erreur -->
        @if ($errors->any())
        <div class="mb-8">
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Plusieurs erreurs ont été trouvées :</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Formulaire -->
            <div class="flex-1">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <form action="{{ route('actus.update', $actu->id) }}" method="POST" enctype="multipart/form-data" id="actuForm">
                        @csrf
                        @method('PUT')

                        <!-- Titre -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Titre <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $actu->title) }}"
                                   class="form-input w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                   required>
                        </div>

                        <!-- Catégorie -->
                        <div class="mb-6">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Catégorie <span class="text-red-500">*</span>
                            </label>
                            <select id="category" 
                                    name="category"
                                    class="form-select w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    required>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ old('category', $actu->category) == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Contenu -->
                        <div class="mb-6">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                Contenu <span class="text-red-500">*</span>
                            </label>
                            <textarea id="content" 
                                      name="content" 
                                      rows="6"
                                      class="form-textarea w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                      required>{{ old('content', $actu->content) }}</textarea>
                        </div>

                        <!-- Lien externe -->
                        <div class="mb-6">
                            <label for="external_link" class="block text-sm font-medium text-gray-700 mb-2">
                                Lien externe
                            </label>
                            <input type="url" 
                                   id="external_link" 
                                   name="external_link"
                                   value="{{ old('external_link', $actu->external_link) }}"
                                   class="form-input w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>

                        <!-- Image -->
                        <div class="mb-6">
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                Image
                            </label>
                            <div class="mt-2 flex items-center gap-4">
                                @if($actu->image)
                                    <div class="relative w-24 h-24 rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $actu->image) }}" 
                                             alt="Image actuelle" 
                                             class="w-full h-full object-cover">
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <input type="file" 
                                           id="image" 
                                           name="image"
                                           accept="image/*"
                                           class="form-input w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    <p class="mt-1 text-sm text-gray-500">
                                        Formats acceptés : JPEG, PNG, JPG, GIF (max 2Mo)
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t">
                            <button type="button" 
                                    onclick="window.history.back()"
                                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Annuler
                            </button>
                            <button type="submit"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Prévisualisation -->
            <div class="lg:w-1/3">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden sticky top-6">
                    <div class="p-4 bg-gray-50 border-b">
                        <h2 class="text-lg font-medium text-gray-900">Prévisualisation</h2>
                    </div>
                    <div class="p-6">
                        <div id="preview" class="prose max-w-none">
                            <!-- Image -->
                            <div id="previewImageContainer" class="relative mb-4 rounded-lg overflow-hidden">
                                <img id="previewImage" 
                                     src="{{ $actu->image ? asset('storage/' . $actu->image) : '' }}" 
                                     alt=""
                                     class="w-full h-48 object-cover">
                                <div class="absolute top-2 right-2">
                                    <span id="previewCategory" 
                                          class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-500 text-white">
                                        {{ $actu->category }}
                                    </span>
                                </div>
                            </div>

                            <!-- Contenu -->
                            <h3 id="previewTitle" class="text-xl font-bold text-gray-900 mb-2">
                                {{ $actu->title }}
                            </h3>
                            <p id="previewContent" class="text-gray-600 mb-4">
                                {{ $actu->content }}
                            </p>

                            <!-- Lien externe -->
                            <div id="previewLinkContainer" class="mt-4 {{ $actu->external_link ? '' : 'hidden' }}">
                                <a id="previewLink" 
                                   href="{{ $actu->external_link }}" 
                                   target="_blank"
                                   class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                    <span>Cliquez ici</span>
                                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('title');
    const contentInput = document.getElementById('content');
    const categoryInput = document.getElementById('category');
    const externalLinkInput = document.getElementById('external_link');
    const imageInput = document.getElementById('image');

    const previewTitle = document.getElementById('previewTitle');
    const previewContent = document.getElementById('previewContent');
    const previewCategory = document.getElementById('previewCategory');
    const previewImage = document.getElementById('previewImage');
    const previewLinkContainer = document.getElementById('previewLinkContainer');
    const previewLink = document.getElementById('previewLink');

    // Mise à jour du titre
    titleInput.addEventListener('input', function() {
        previewTitle.textContent = this.value || 'Titre de l\'actualité';
    });

    // Mise à jour du contenu
    contentInput.addEventListener('input', function() {
        previewContent.textContent = this.value || 'Contenu de l\'actualité';
    });

    // Mise à jour de la catégorie
    categoryInput.addEventListener('change', function() {
        previewCategory.textContent = this.value;
    });

    // Mise à jour du lien externe
    externalLinkInput.addEventListener('input', function() {
        if (this.value) {
            previewLinkContainer.classList.remove('hidden');
            previewLink.href = this.value;
        } else {
            previewLinkContainer.classList.add('hidden');
        }
    });

    // Mise à jour de l'image
    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endsection 