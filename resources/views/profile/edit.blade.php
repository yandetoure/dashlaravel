<?php declare(strict_types=1); ?>

@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9fafb;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 2rem;
        }
        .section-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 1rem;
            position: relative;
            padding-bottom: 0.5rem;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background: #3b82f6;
            border-radius: 3px;
        }
        .card {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .profile-img {
            width: 160px;
            height: 160px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .profile-img:hover {
            transform: scale(1.05);
        }
        .input-field {
            transition: all 0.3s ease;
        }
        .input-field:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        .file-input {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
        .file-input input[type="file"] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }
        /* Section layout */
        .profile-section {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem; /* espace entre les deux blocs */
            align-items: flex-start;
        }

        .profile-photo {
            text-align: center;
            margin-bottom: 2rem;
            flex-shrink: 0; /* empêche de rapetisser */
        }

        .profile-form {
            flex: 1;
            min-width: 300px; /* empêche d’être trop petit sur petit écran */
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        @media (min-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
        .btn {
            background-color: #3b82f6;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease;
            border: none;
        }
        .btn:hover {
            background-color: #2563eb;
        }

        .load{
            background-color: #2563eb;
            color:rgb(239, 241, 244);
            font-weight: bold;
        }
        .reservation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .status-badge {
            top: -10px;
            right: -10px;
        }
    </style>
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
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container">
        <!-- Header -->
        <h2 class="section-title"><i class="fas fa-user-circle text-blue-600 mr-2"></i> Mon Profil</h2>
        <p class="text-gray-600 mb-8">Gérez vos informations personnelles et vos paramètres de compte</p>

        <div class="card">
            <div class="profile-section">
                <!-- Photo (gauche) -->
                <div class="profile-photo">
                    <div class="relative group w-fit mx-auto md:mx-0 mb-4">
                        @if (auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Photo de profil" class="profile-img">
                        @else
                            <div class="w-40 h-40 rounded-full bg-blue-100 flex items-center justify-center text-gray-500 text-4xl">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black bg-opacity-20 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-white font-medium"><i class="fas fa-camera mr-1"></i> Modifier</span>
                        </div>
                    </div>

                    <label for="profile_photo" class="file-input mt-4">
                        <span class="load bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg cursor-pointer transition-colors duration-300 inline-flex items-center">
                            <i class="fas fa-upload mr-2"></i> Changer la photo
                        </span>
                        <input id="profile_photo" name="profile_photo" type="file" class="hidden"/>
                    </label>
                    <p class="text-xs text-gray-500 mt-2">JPEG, PNG - 2MB max</p>
                    <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
                </div>

                <!-- Formulaire (droite) -->
                <div class="profile-form">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="form-grid">
                        @csrf
                        @method('PATCH')

                        <!-- Prénom -->
                        <div>
                            <label for="first_name" class="block mb-1 font-medium"><i class="fas fa-user text-blue-500 mr-1"></i> Prénom</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', auth()->user()->first_name) }}" class="input-field w-full border p-3 rounded-md border-gray-300" required>
                            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                        </div>

                        <!-- Nom -->
                        <div>
                            <label for="last_name" class="block mb-1 font-medium"><i class="fas fa-user text-blue-500 mr-1"></i> Nom</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', auth()->user()->last_name) }}" class="input-field w-full border p-3 rounded-md border-gray-300" required>
                            <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                        </div>

                        <!-- Adresse -->
                        <div class="col-span-2">
                            <label for="address" class="block mb-1 font-medium"><i class="fas fa-map-marker-alt text-blue-500 mr-1"></i> Adresse</label>
                            <input type="text" name="address" id="address" value="{{ old('address', auth()->user()->address) }}" class="input-field w-full border p-3 rounded-md border-gray-300" required>
                            <x-input-error class="mt-2" :messages="$errors->get('address')" />
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label for="phone_number" class="block mb-1 font-medium"><i class="fas fa-phone text-blue-500 mr-1"></i> Téléphone</label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', auth()->user()->phone_number) }}" class="input-field w-full border p-3 rounded-md border-gray-300" required>
                            <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block mb-1 font-medium"><i class="fas fa-envelope text-blue-500 mr-1"></i> Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" class="input-field w-full border p-3 rounded-md border-gray-300" required>
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <!-- Submit -->
                        <div class="col-span-2 mt-4 flex items-center gap-4">
                            <button type="submit" class="btn"><i class="fas fa-save mr-2"></i> Sauvegarder</button>
                            @if (session('status') === 'profile-updated')
                                <div class="text-green-600 text-sm flex items-center gap-2">
                                    <i class="fas fa-check-circle"></i> Modifications enregistrées.
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Password -->
        <div class="card">
            <h3 class="section-title"><i class="fas fa-lock text-blue-500 mr-2"></i> Changer le mot de passe</h3>
            @include('profile.partials.update-password-form')
        </div>

        <!-- Delete -->
        <div class="card">
            <h3 class="section-title"><i class="fas fa-exclamation-triangle text-red-500 mr-2"></i> Supprimer mon compte</h3>
            @include('profile.partials.delete-user-form')
        </div>
    </div>

    <script>
        document.getElementById('profile_photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const img = document.querySelector('.profile-img');
                    if (img) {
                        img.src = event.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
@endsection
