@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-semibold mb-4">Modifier l'info</h2>
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
            <ul class="list-disc pl-5 text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('infos.update', $info->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="title" class="block font-medium mb-2">Titre</label>
            <input type="text" name="title" id="title" value="{{ old('title', $info->title) }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label for="image" class="block font-medium mb-2">Image (optionnel)</label>
            <input type="file" name="image" id="image" accept="image/*" class="w-full border border-gray-300 rounded px-3 py-2">
            @if($info->image)
                <img src="{{ asset('storage/' . $info->image) }}" alt="Image actuelle" class="w-full h-32 object-cover mt-2 rounded">
            @endif
        </div>
        <div class="mb-4">
            <label for="category_id" class="block font-medium mb-2">Catégorie</label>
            <select id="category_id" name="category_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
                <option value="">Sélectionnez une catégorie</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $info->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="external_link" class="block font-medium mb-2">Lien externe (optionnel)</label>
            <input type="url" name="external_link" id="external_link" value="{{ old('external_link', $info->external_link) }}" class="w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label for="content" class="block font-medium mb-2">Contenu</label>
            <textarea name="content" id="content" rows="5" class="w-full border border-gray-300 rounded px-3 py-2" required>{{ old('content', $info->content) }}</textarea>
        </div>
        <div class="flex justify-end gap-4">
            <a href="{{ route('infos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Annuler</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Enregistrer</button>
        </div>
    </form>
</div>
@endsection 