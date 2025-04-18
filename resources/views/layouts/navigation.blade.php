<?php declare(strict_types=1); ?>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<style>
 .custom-profile-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #FFD700;
    transition: transform 0.3s ease-in-out;
}

.nav-link {
    font-weight: 600;
    color: white;
    transition: all 0.3s ease-in-out;
    padding: 12px 16px;
    border-radius: 6px;
}

.nav-link:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.notification-icon {
    cursor: pointer;
    color: #FFD700;
    transition: transform 0.3s ease-in-out;
}

.notification-icon:hover {
    transform: scale(1.2);
}

/* Effet du menu utilisateur */
.nav-container button {
    transition: all 0.3s ease-in-out;
}

.nav-container button:hover {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
}

/* Menu mobile */
@media screen and (max-width: 768px) {
    .nav-container {
        padding: 10px 15px;
    }
}

.nav-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    background-color:rgb(255, 255, 255);
    /* background: linear-gradient(to right, #2C3E50, #4CA1AF); */
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    padding: 15px 20px;
    border-radius: 0 0 8px 8px;
}

</style>

<nav x-data="{ open: false }" class="nav-container border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo / Rôle -->
            <div class="flex items-center">
                <a href="/" class="flex items-center space-x-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 100px; width: auto;" class="me-2">
                </a>
            </div>

            <!-- Navigation Links -->
            @php
            $role = Auth::user()->getRoleNames()->first();
            $dashboardRoute = match($role) {
                'admin' => route('dashboard.admin'),
                'superadmin', 'super-admin' => route('dashboard.superadmin'),
                'client' => route('dashboard.client'),
                'entreprise' => route('dashboard.entreprise'),
                'agent' => route('dashboard.agent'),
                'chauffeur' => route('dashboard.chauffeur'),
                default => '#',
                };
            @endphp
            <div class="hidden sm:flex space-x-6">
            <a href="{{ $dashboardRoute }}" class="nav-link">
                    Dashboard
                </a>
            </div>

            <!-- Notifications + Menu utilisateur -->
            <div class="hidden sm:flex items-center space-x-6">
                <!-- Icône de notification -->
                <div class="notification-icon-not">
                    <!-- <span class="material-icons">notifications</span> -->
                </div>

                <!-- Menu utilisateur -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center space-x-2 bg-white text-gray-600 hover:text-gray-800">
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}" class="custom-profile-img">
                            <span>
                                {{ Auth::user()->role === 'entreprise' || Auth::user()->role === 'client' ? Auth::user()->name : Auth::user()->last_name }}
                            </span>
                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profil
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                Déconnexion
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Menu mobile -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded-md text-gray-500 hover:text-gray-700 transition duration-150">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu responsive -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="nav-link block px-4 py-2">
                Dashboard
            </a>
        </div>
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="text-gray-800 font-medium">
                    {{ Auth::user()->role === 'client' ? Auth::user()->name : Auth::user()->first_name . ' ' . Auth::user()->last_name }}
                </div>
                <div class="text-gray-500 text-sm">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="nav-link block px-4 py-2">
                    Profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link block w-full text-left px-4 py-2">
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
