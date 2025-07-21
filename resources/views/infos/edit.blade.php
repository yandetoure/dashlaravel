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
    <form action="{{ route('infos.update', $info->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="title" class="block font-medium mb-2">Titre</label>
            <input type="text" name="title" id="title" value="{{ old('title', $info->title) }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label for="image" class="block font-medium mb-2">Image (chemin ou URL)</label>
            <input type="text" name="image" id="image" value="{{ old('image', $info->image) }}" class="w-full border border-gray-300 rounded px-3 py-2">
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