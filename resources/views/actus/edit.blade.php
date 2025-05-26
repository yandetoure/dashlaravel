@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-4 flex flex-col md:flex-row gap-4">
  <!-- Formulaire -->
  <div class="w-full md:w-1/2 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold mb-4">Modifier l'actualité</h2>
    <form action="{{ route('actus.update', $actu->id) }}" method="POST" enctype="multipart/form-data" id="actuForm" class="space-y-4">
      @csrf
      @method('PUT')

      <!-- Titre -->
      <div>
        <label class="block mb-2 font-medium" for="title">Titre</label>
        <input type="text" id="title" name="title" value="{{ $actu->title }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div>

      <!-- Catégorie -->
      <div>
        <label class="block mb-2 font-medium" for="category">Catégorie</label>
        <select id="category" name="category" class="w-full border border-gray-300 rounded px-3 py-2" required>
          @foreach($categories as $category)
            <option value="{{ $category }}" {{ $actu->category === $category ? 'selected' : '' }}>
              {{ $category }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Lien externe -->
      <div>
        <label class="block mb-2 font-medium" for="external_link">Lien externe (optionnel)</label>
        <input type="url" id="external_link" name="external_link" value="{{ $actu->external_link }}" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="https://...">
      </div>

      <!-- Contenu -->
      <div>
        <label class="block mb-2 font-medium" for="content">Contenu</label>
        <textarea id="content" name="content" rows="4" class="w-full border border-gray-300 rounded px-3 py-2" required>{{ $actu->content }}</textarea>
      </div>

      <!-- Image actuelle -->
      @if($actu->image)
        <div class="mb-4">
          <label class="block mb-2 font-medium">Image actuelle</label>
          <img src="{{ asset('storage/' . $actu->image) }}" alt="{{ $actu->title }}" class="w-48 h-auto rounded">
        </div>
      @endif

      <!-- Nouvelle image -->
      <div>
        <label class="block mb-2 font-medium" for="image">Nouvelle image (optionnel)</label>
        <input type="file" id="image" name="image" accept="image/*" class="w-full border border-gray-300 rounded px-3 py-2">
      </div>

      <!-- Boutons -->
      <div class="mt-4 flex items-center justify-between">
        <div>
          <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Mettre à jour</button>
          <a href="{{ route('actus.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">Annuler</a>
        </div>
        <!-- Bouton de suppression -->
        <form action="{{ route('actus.destroy', $actu->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-red-600 px-4 py-2 rounded">Supprimer</button>
        </form>
      </div>
    </form>
  </div>

  <!-- Zone d'aperçu -->
  <div class="w-full md:w-1/2 bg-gray-100 p-6 rounded shadow" id="previewContainer">
    <h3 class="text-xl font-semibold mb-4">Aperçu en temps réel</h3>
    <div class="bg-white rounded-lg overflow-hidden">
      <div class="p-4">
        <span id="previewCategory" class="inline-block bg-red-100 text-red-600 px-2 py-1 rounded text-sm mb-2"></span>
        <h4 class="text-lg font-semibold mb-2" id="previewTitle">{{ $actu->title }}</h4>
        <p class="text-gray-700 mb-2" id="previewContent">{{ $actu->content }}</p>
        <a href="#" id="previewLink" class="text-blue-600 hover:underline text-sm {{ $actu->external_link ? '' : 'hidden' }}">Lien externe</a>
      </div>
      <div id="previewImageContainer" class="{{ $actu->image ? '' : 'hidden' }}">
        <img src="{{ $actu->image ? asset('storage/' . $actu->image) : '' }}" alt="" id="previewImage" class="w-full h-48 object-cover">
      </div>
    </div>
  </div>
</div>

<script>
// Prévisualisation en temps réel
const titleInput = document.getElementById('title');
const categorySelect = document.getElementById('category');
const contentInput = document.getElementById('content');
const externalLinkInput = document.getElementById('external_link');
const imageInput = document.getElementById('image');

const previewTitle = document.getElementById('previewTitle');
const previewCategory = document.getElementById('previewCategory');
const previewContent = document.getElementById('previewContent');
const previewLink = document.getElementById('previewLink');
const previewImage = document.getElementById('previewImage');
const previewImageContainer = document.getElementById('previewImageContainer');

titleInput.addEventListener('input', updatePreview);
categorySelect.addEventListener('change', updatePreview);
contentInput.addEventListener('input', updatePreview);
externalLinkInput.addEventListener('input', updatePreview);

function updatePreview() {
  previewTitle.textContent = titleInput.value;
  previewCategory.textContent = categorySelect.value;
  previewContent.textContent = contentInput.value;
  
  if (externalLinkInput.value) {
    previewLink.href = externalLinkInput.value;
    previewLink.textContent = 'Voir plus';
    previewLink.classList.remove('hidden');
  } else {
    previewLink.classList.add('hidden');
  }
}

// Pour l'image, chargement instantané
imageInput.addEventListener('change', () => {
  const file = imageInput.files[0];
  if (file) {
    const url = URL.createObjectURL(file);
    previewImage.src = url;
    previewImageContainer.classList.remove('hidden');
  }
});

// Initialisation de l'aperçu
updatePreview();
</script>
@endsection 