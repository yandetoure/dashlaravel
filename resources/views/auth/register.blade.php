<?php declare(strict_types=1); ?>
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- Type d'utilisateur -->
        <div>
            <x-input-label for="user_type" :value="__('Inscription en tant que')" />
            <select id="user_type" name="user_type" class="block mt-1 w-full" required>
                <option value="client">Client</option>
                <option value="entreprise">Entreprise</option>
            </select>
            <x-input-error :messages="$errors->get('user_type')" class="mt-2" />
        </div>

        <!-- Pour les clients : Prénom et Nom -->
        <div id="client-fields">
            <div class="mt-4">
                <x-input-label for="first_name" :value="__('Prénom')" />
                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" autocomplete="given-name"/>
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="last_name" :value="__('Nom')" />
                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" autocomplete="family-name"/>
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>

        <!-- Pour les entreprises : Nom de l'entreprise -->
        <div id="entreprise-fields" class="hidden">
            <div class="mt-4">
                <x-input-label for="name" :value="__('Nom de l\'entreprise')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" autocomplete="organization"/>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
        </div>

        <!-- Adresse -->
        <div class="mt-4">
            <x-input-label for="address" :value="__('Adresse')" />
            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required />
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <!-- Numéro de téléphone -->
        <div class="mt-4">
            <x-input-label for="phone_number" :value="__('Numéro de téléphone')" />
            <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number')" required />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username"/>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Mot de passe -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password" name="password" required autocomplete="new-password"/>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirmation du mot de passe -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password" name="password_confirmation" required autocomplete="new-password"/>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Photo de profil -->
        <div class="mt-4">
            <x-input-label for="profile_photo" :value="__('Photo de profil')" />
            <input id="profile_photo" class="block mt-1 w-full" type="file" name="profile_photo" accept="image/*">
            <x-input-error :messages="$errors->get('profile_photo')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Déjà inscrit ?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('S\'inscrire') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Script pour afficher les champs en fonction du type d'utilisateur -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
    const userTypeSelect = document.getElementById("user_type");
    const clientFields = document.getElementById("client-fields");
    const entrepriseFields = document.getElementById("entreprise-fields");

    function toggleFields() {
        if (userTypeSelect.value === "client") {
            clientFields.classList.remove("hidden");
            entrepriseFields.classList.add("hidden");
        } else {
            clientFields.classList.add("hidden");
            entrepriseFields.classList.remove("hidden");
        }
    }

    userTypeSelect.addEventListener("change", toggleFields);
    toggleFields(); // Initialiser au chargement
});

    </script>
</x-guest-layout>
