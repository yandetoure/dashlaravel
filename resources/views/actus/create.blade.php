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
<div class="max-w-6xl mx-auto p-4 flex flex-col md:flex-row gap-4">
  <!-- Formulaire -->
  <div class="w-full md:w-1/2 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold mb-4">Ajouter une nouvelle actualité</h2>
    <form action="{{ route('actus.store') }}" method="POST" enctype="multipart/form-data" id="actuForm" class="space-y-4">
      @csrf
      <!-- Titre -->
      <div>
        <label class="block mb-2 font-medium" for="title">Titre</label>
        <input type="text" id="title" name="title" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div>
      <!-- Contenu -->
      <div>
        <label class="block mb-2 font-medium" for="content">Contenu</label>
        <textarea id="content" name="content" rows="4" class="w-full border border-gray-300 rounded px-3 py-2" required></textarea>
      </div>
      <!-- Image -->
      <div>
        <label class="block mb-2 font-medium" for="image">Image (optionnel)</label>
        <input type="file" id="image" name="image" accept="image/*" class="w-full border border-gray-300 rounded px-3 py-2">
      </div>
      <!-- Bouton -->
      <div class="mt-4">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Enregistrer</button>
      </div>
    </form>
  </div>

  <!-- Zone d'aperçu -->
  <div class="w-full md:w-1/2 bg-gray-100 p-6 rounded shadow" id="previewContainer">
    <h3 class="text-xl font-semibold mb-4">Aperçu en temps réel</h3>
    <div class="bg-white rounded-lg overflow-hidden mb-4" id="previewImageContainer">
      <img src="" alt="" id="previewImage" class="w-full h-48 object-cover hidden">
    </div>
    <h4 class="text-lg font-semibold mb-2" id="previewTitle">Titre</h4>
    <p class="text-gray-700" id="previewContent">Contenu</p>
  </div>
</div>

<script>
// Prévisualisation en temps réel
const titleInput = document.getElementById('title');
const contentInput = document.getElementById('content');
const previewTitle = document.getElementById('previewTitle');
const previewContent = document.getElementById('previewContent');

const imageInput = document.getElementById('image');
const previewImage = document.getElementById('previewImage');
const previewImageContainer = document.getElementById('previewImageContainer');

titleInput.addEventListener('input', () => {
  previewTitle.textContent = titleInput.value || 'Titre';
});

contentInput.addEventListener('input', () => {
  previewContent.textContent = contentInput.value || 'Contenu';
});

// Pour l’image, chargement instantané
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
