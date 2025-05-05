@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Carte du formulaire -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- En-tête du formulaire -->
            <div class="bg-blue-600 py-4 px-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-white text-center">Ajouter un utilisateur</h2>
            </div>
            
            <!-- Corps du formulaire -->
            <div class="p-6">
                <!-- Message de succès -->
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                
                <!-- Messages d'erreur -->
                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                        <ul class="list-disc ml-4">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('admin.create.account') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Type d'utilisateur -->
                    <div class="mb-6">
                        <label for="user_type" class="block text-sm font-medium text-gray-700 mb-1">Type d'utilisateur</label>
                        <select id="user_type" name="role" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            <option value="client">Client</option>
                            <option value="agent">Agent</option>
                            <option value="entreprise">Entreprise</option>
                            <option value="chauffeur">Chauffeur</option>
                            <option value="admin">Admin</option>
                            <option value="super-admin">Super-Admin</option>
                        </select>
                    </div>
                    
                    <!-- Nom et prénom -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div id="first_name_field">
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                            <input type="text" id="first_name" name="first_name" placeholder="Entrez le prénom" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        
                        <div id="last_name_field">
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                            <input type="text" id="last_name" name="last_name" placeholder="Entrez le nom de famille" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>
                    
                    <!-- Nom de l'entreprise (caché par défaut) -->
                    <div id="company_name_field" class="mb-6 hidden">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom de l'entreprise</label>
                        <input type="text" id="name" name="name" placeholder="Entrez le nom de l'entreprise" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    
                    <!-- Adresse et téléphone -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                            <input type="text" id="address" name="address" placeholder="Adresse complète" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Numéro de téléphone</label>
                            <input type="text" id="phone_number" name="phone_number" placeholder="+33 X XX XX XX XX" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                    </div>
                    
                    <!-- Email et photo de profil -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="email" name="email" placeholder="exemple@domaine.com" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        
                        <div>
                            <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-1">Photo de profil</label>
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>
                    
                    <!-- Bouton de soumission -->
                    <div>
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-md shadow-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Créer l'utilisateur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('user_type').addEventListener('change', function () {
        let userType = this.value;
        let firstNameField = document.getElementById('first_name_field');
        let lastNameField = document.getElementById('last_name_field');
        let companyNameField = document.getElementById('company_name_field');

        if (userType === 'entreprise') {
            companyNameField.classList.remove('hidden');
            firstNameField.classList.add('hidden');
            lastNameField.classList.add('hidden');
        } else {
            companyNameField.classList.add('hidden');
            firstNameField.classList.remove('hidden');
            lastNameField.classList.remove('hidden');
        }
    });
});
</script>
@endsection