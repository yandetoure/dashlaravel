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
  <!-- Formulaire -->
  <div class="w-full md:w-1/2 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold mb-4">Ajouter une nouvelle actualité</h2>

    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
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
    @endif

    <form action="{{ route('actus.store') }}" method="POST" enctype="multipart/form-data" id="actuForm" class="space-y-4">
      @csrf
      <!-- Titre -->
      <div>
        <label class="block mb-2 font-medium" for="title">Titre</label>
        <input type="text" id="title" name="title" value="{{ old('title') }}" 
               class="w-full border @error('title') border-red-500 @enderror border-gray-300 rounded px-3 py-2" required>
        @error('title')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Catégorie -->
      <div>
        <label class="block mb-2 font-medium" for="category_id">Catégorie</label>
        <select id="category_id" name="category_id" 
                class="w-full border @error('category_id') border-red-500 @enderror border-gray-300 rounded px-3 py-2" required>
            <option value="">Sélectionnez une catégorie</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}
                        data-color="{{ $category->color }}">
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Contenu -->
      <div>
        <label class="block mb-2 font-medium" for="content">Contenu</label>
        <textarea id="content" name="content" rows="4" 
                  class="w-full border @error('content') border-red-500 @enderror border-gray-300 rounded px-3 py-2" 
                  required>{{ old('content') }}</textarea>
        @error('content')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Lien externe -->
      <div>
        <label class="block mb-2 font-medium" for="external_link">Lien externe (optionnel)</label>
        <input type="url" id="external_link" name="external_link" value="{{ old('external_link') }}"
               class="w-full border @error('external_link') border-red-500 @enderror border-gray-300 rounded px-3 py-2">
        @error('external_link')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Image -->
      <div>
        <label class="block mb-2 font-medium" for="image">Image (optionnel)</label>
        <input type="file" id="image" name="image" accept="image/*" 
               class="w-full border @error('image') border-red-500 @enderror border-gray-300 rounded px-3 py-2">
        <p class="text-sm text-gray-500 mt-1">Formats acceptés : JPEG, PNG, JPG, GIF (max 2Mo)</p>
        @error('image')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Boutons -->
      <div class="mt-6 flex space-x-4">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors duration-200">
            Enregistrer
        </button>
        <a href="{{ route('actus.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors duration-200">
            Annuler
        </a>
      </div>
    </form>
  </div>

  <!-- Zone d'aperçu -->
  <div class="w-full md:w-1/2 bg-gray-100 p-6 rounded shadow" id="previewContainer">
    <h3 class="text-xl font-semibold mb-4">Aperçu en temps réel</h3>
    <div class="bg-white rounded-lg overflow-hidden shadow-sm">
      <div id="previewImageContainer" class="relative">
      <img src="" alt="" id="previewImage" class="w-full h-48 object-cover hidden">
        <div id="previewCategory" class="absolute top-2 right-2 bg-blue-500 text-white px-3 py-1 rounded-full text-sm"></div>
      </div>
      <div class="p-4">
        <h4 class="text-lg font-semibold mb-2" id="previewTitle">Titre</h4>
        <p class="text-gray-700 mb-3" id="previewContent">Contenu</p>
        <a href="#" id="previewLink" class="text-blue-600 hover:underline text-sm hidden">Lien externe</a>
      </div>
    </div>
  </div>
</div>

<script>
// Prévisualisation en temps réel
const titleInput = document.getElementById('title');
const contentInput = document.getElementById('content');
const categoryInput = document.getElementById('category_id');
const externalLinkInput = document.getElementById('external_link');
const imageInput = document.getElementById('image');

const previewTitle = document.getElementById('previewTitle');
const previewContent = document.getElementById('previewContent');
const previewCategory = document.getElementById('previewCategory');
const previewImage = document.getElementById('previewImage');
const previewLink = document.getElementById('previewLink');

titleInput.addEventListener('input', () => {
  previewTitle.textContent = titleInput.value || 'Titre';
});

contentInput.addEventListener('input', () => {
  previewContent.textContent = contentInput.value || 'Contenu';
});

categoryInput.addEventListener('change', () => {
  const selectedOption = categoryInput.options[categoryInput.selectedIndex];
  previewCategory.textContent = selectedOption.text;
  previewCategory.classList.toggle('hidden', !selectedOption.value);
  previewCategory.style.backgroundColor = selectedOption.getAttribute('data-color');
});

externalLinkInput.addEventListener('input', () => {
  if (externalLinkInput.value) {
    previewLink.href = externalLinkInput.value;
    previewLink.textContent = 'Voir plus →';
    previewLink.classList.remove('hidden');
  } else {
    previewLink.classList.add('hidden');
  }
});

imageInput.addEventListener('change', () => {
  const file = imageInput.files[0];
  if (file) {
    const url = URL.createObjectURL(file);
    previewImage.src = url;
    previewImage.classList.remove('hidden');
  } else {
    previewImage.src = '';
    previewImage.classList.add('hidden');
  }
});
</script>
@endsection
