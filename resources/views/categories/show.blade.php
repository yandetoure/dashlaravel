@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-4">
                <a href="{{ route('categories.index') }}" 
                   class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
                <div class="w-4 h-4 rounded-full" style="background-color: {{ $category->color }}"></div>
            </div>
            
            @if($category->description)
                <p class="text-gray-600 mb-4">{{ $category->description }}</p>
            @endif

            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                </span>
                <span class="text-gray-500">
                    {{ $category->actus->count() }} actualité(s)
                </span>
                <a href="{{ route('categories.edit', $category) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier la catégorie
                </a>
            </div>
        </div>

        <!-- Actualités de cette catégorie -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Actualités dans cette catégorie</h3>
            </div>
            
            @if($category->actus->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($category->actus as $actu)
                        <div class="p-6 hover:bg-gray-50">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h4 class="text-lg font-medium text-gray-900">{{ $actu->title }}</h4>
                                        @if($actu->image)
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-image mr-1"></i>
                                                Image
                                            </span>
                                        @endif
                                        @if($actu->external_link)
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-external-link-alt mr-1"></i>
                                                Lien externe
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-gray-600 mb-3">{{ Str::limit($actu->content, 200) }}</p>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span>
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $actu->created_at->format('d/m/Y à H:i') }}
                                        </span>
                                        <span>
                                            <i class="fas fa-clock mr-1"></i>
                                            Modifié {{ $actu->updated_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <a href="{{ route('actus.show', $actu) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('actus.edit', $actu) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune actualité</h3>
                    <p class="mt-1 text-sm text-gray-500">Cette catégorie ne contient encore aucune actualité.</p>
                    <div class="mt-6">
                        <a href="{{ route('actus.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>
                            Créer une actualité
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 