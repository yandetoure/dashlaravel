<?php declare(strict_types=1); ?>
<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex items-center">
                    <!-- Photo de profil -->
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-12 h-12 rounded-full mr-4">
                    
                    <!-- Informations de l'utilisateur -->
                    <div>
                        <p class="text-lg font-bold">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-gray-600">{{ __("You're logged in!") }}</p>
                        <p class="text-sm text-gray-500">Rôle : {{ Auth::user()->getRoleNames()->first() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
