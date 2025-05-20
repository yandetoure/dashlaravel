{{-- resources/views/actus/create.blade.php --}}

@extends('layouts.app') {{-- ou votre layout principal --}}

@section('content')
<div class="max-w-xl mx-auto p-4">
    <h2 class="text-2xl font-semibold mb-4">Ajouter une nouvelle actualité</h2>
    <form action="{{ route('actus.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf
        <!-- Titre -->
        <div>
            <label class="block mb-2 font-medium" for="title">Titre</label>
            <input type="text" name="title" id="title" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>
        <!-- Contenu -->
        <div>
            <label class="block mb-2 font-medium" for="content">Contenu</label>
            <textarea name="content" id="content" rows="4" class="w-full border border-gray-300 rounded px-3 py-2" required></textarea>
        </div>
        <!-- Image -->
        <div>
            <label class="block mb-2 font-medium" for="image">Image (optionnel)</label>
            <input type="file" name="image" id="image" class="w-full border border-gray-300 rounded px-3 py-2" accept="image/*">
        </div>
        <!-- Bouton -->
        <div class="mt-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Enregistrer</button>
        </div>
    </form>
</div>
@endsection
