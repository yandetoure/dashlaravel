@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex items-center space-x-3">
                <a href="{{ route('categories.index') }}" 
                   class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Modifier la catégorie</h1>
            </div>
            <p class="text-gray-600 mt-2">Modifiez les informations de la catégorie "{{ $category->name }}"</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('categories.update', $category) }}" method="POST" id="categoryForm">
                @csrf
                @method('PUT')

                <!-- Nom de la catégorie -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nom de la catégorie <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $category->name) }}"
                           class="form-input w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('name') border-red-500 @enderror"
                           placeholder="Ex: Actualités sportives"
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              class="form-textarea w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('description') border-red-500 @enderror"
                              placeholder="Description de la catégorie (optionnel)">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Couleur -->
                <div class="mb-6">
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                        Couleur d'affichage <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center space-x-4">
                        <input type="color" 
                               id="color" 
                               name="color" 
                               value="{{ old('color', $category->color) }}"
                               class="h-12 w-16 border border-gray-300 rounded-lg @error('color') border-red-500 @enderror">
                        <div class="flex-1">
                            <input type="text" 
                                   id="colorHex" 
                                   value="{{ old('color', $category->color) }}"
                                   class="form-input w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                   placeholder="#RRGGBB"
                                   pattern="^#[0-9A-Fa-f]{6}$"
                                   readonly>
                        </div>
                        <div class="text-sm text-gray-500">
                            <p>Cette couleur sera utilisée pour identifier la catégorie</p>
                        </div>
                    </div>
                    @error('color')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Couleurs prédéfinies -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Couleurs suggérées
                    </label>
                    <div class="grid grid-cols-8 gap-2">
                        @php
                            $predefinedColors = [
                                '#3B82F6', '#EF4444', '#10B981', '#F59E0B',
                                '#8B5CF6', '#F97316', '#06B6D4', '#84CC16',
                                '#EC4899', '#6366F1', '#14B8A6', '#F472B6'
                            ];
                        @endphp
                        @foreach($predefinedColors as $predefinedColor)
                            <button type="button" 
                                    class="w-8 h-8 rounded border-2 border-gray-300 hover:border-gray-500 transition-colors color-preset {{ $category->color === $predefinedColor ? 'border-gray-600' : '' }}"
                                    style="background-color: {{ $predefinedColor }}"
                                    data-color="{{ $predefinedColor }}"
                                    title="{{ $predefinedColor }}">
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Statut actif -->
                <div class="mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_active" 
                               name="is_active"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">
                            Catégorie active (les catégories inactives ne sont pas disponibles lors de la création d'actualités)
                        </label>
                    </div>
                </div>

                <!-- Information sur les actualités associées -->
                @if($category->actus()->count() > 0)
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            <span class="text-blue-700 font-medium">
                                Cette catégorie contient {{ $category->actus()->count() }} actualité(s).
                            </span>
                        </div>
                        <p class="text-blue-600 text-sm mt-1">
                            Les modifications apportées à cette catégorie s'appliqueront à toutes les actualités associées.
                        </p>
                    </div>
                @endif

                <!-- Prévisualisation -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Prévisualisation</h4>
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white" 
                         id="preview" 
                         style="background-color: {{ old('color', $category->color) }}">
                        <span id="previewText">{{ old('name', $category->name) }}</span>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('categories.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                            Mettre à jour
                        </button>
                    </div>

                    @if($category->actus()->count() === 0)
                        <form action="{{ route('categories.destroy', $category) }}" 
                              method="POST" 
                              class="inline-block"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ? Cette action est irréversible.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                                <i class="fas fa-trash mr-2"></i>
                                Supprimer
                            </button>
                        </form>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.getElementById('color');
    const colorHex = document.getElementById('colorHex');
    const nameInput = document.getElementById('name');
    const preview = document.getElementById('preview');
    const previewText = document.getElementById('previewText');
    const colorPresets = document.querySelectorAll('.color-preset');

    // Synchroniser les inputs de couleur
    colorInput.addEventListener('input', function() {
        colorHex.value = this.value;
        updatePreview();
        updatePresetBorders();
    });

    colorHex.addEventListener('input', function() {
        if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
            colorInput.value = this.value;
            updatePreview();
            updatePresetBorders();
        }
    });

    // Mettre à jour le texte de prévisualisation
    nameInput.addEventListener('input', function() {
        previewText.textContent = this.value || 'Nom de la catégorie';
    });

    // Gestion des couleurs prédéfinies
    colorPresets.forEach(function(preset) {
        preset.addEventListener('click', function() {
            const color = this.dataset.color;
            colorInput.value = color;
            colorHex.value = color;
            updatePreview();
            updatePresetBorders();
        });
    });

    function updatePreview() {
        preview.style.backgroundColor = colorInput.value;
    }

    function updatePresetBorders() {
        colorPresets.forEach(function(preset) {
            if (preset.dataset.color === colorInput.value.toUpperCase()) {
                preset.classList.add('border-gray-600');
                preset.classList.remove('border-gray-300');
            } else {
                preset.classList.remove('border-gray-600');
                preset.classList.add('border-gray-300');
            }
        });
    }

    // Initialiser les bordures
    updatePresetBorders();
});
</script>
@endsection 