<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire d'inscription</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .form-label {
            font-size: 0.9rem;
        }

        .form-control {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }

        .logo-mobile {
            display: block;
            margin: 0 auto 20px;
            max-width: 120px;
        }

        h2 {
            font-size: 1.5rem;
        }

        button.btn-lg {
            font-size: 1rem;
            padding: 0.6rem 1.5rem;
        }

        .is-invalid {
            border-color: red;
        }

        .invalid-feedback {
            display: block;
            font-size: 0.8rem;
            color: red;
        }
    </style>
</head>
<body>
<div class="container mt-5 d-flex justify-content-center">
    <div class="card shadow-lg p-4 rounded-4 w-100" style="max-width: 800px;">
        
        <!-- Logo centré -->
        <img src="{{ asset('images/logo.png') }}" alt="Logo Mobile" class="logo-mobile">

        <h2 class="text-center mb-4 fw-bold text-primary">Inscription</h2>

        <!-- Affichage des erreurs de Laravel -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="mt-3" id="registrationForm" novalidate>
            @csrf

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="first_name" class="form-label">Prénom</label>
                    <input type="text" name="first_name" id="first_name" class="form-control rounded-3 shadow-sm @error('first_name') is-invalid @enderror" 
                           placeholder="Ex: Jean" required value="{{ old('first_name') }}">
                    <div class="invalid-feedback @if(!$errors->has('first_name')) d-none @endif">
                        @error('first_name')
                            {{ $message }}
                        @else
                            Le prénom doit contenir au moins 3 caractères.
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="last_name" class="form-label">Nom</label>
                    <input type="text" name="last_name" id="last_name" class="form-control rounded-3 shadow-sm @error('last_name') is-invalid @enderror" 
                           placeholder="Ex: Dupont" required value="{{ old('last_name') }}">
                    <div class="invalid-feedback @if(!$errors->has('last_name')) d-none @endif">
                        @error('last_name')
                            {{ $message }}
                        @else
                            Le nom est requis.
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="address" class="form-label">Adresse</label>
                    <input type="text" name="address" id="address" class="form-control rounded-3 shadow-sm @error('address') is-invalid @enderror" 
                           placeholder="Ex: 123 Rue Principale" required value="{{ old('address') }}">
                    <div class="invalid-feedback @if(!$errors->has('address')) d-none @endif">
                        @error('address')
                            {{ $message }}
                        @else
                            L'adresse est requise.
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="phone_number" class="form-label">Téléphone</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control rounded-3 shadow-sm @error('phone_number') is-invalid @enderror" 
                           placeholder="Ex: 77XXXXXXX" required value="{{ old('phone_number') }}">
                    <div class="invalid-feedback @if(!$errors->has('phone_number')) d-none @endif">
                        @error('phone_number')
                            {{ $message }}
                        @else
                            Le numéro de téléphone doit commencer par 75, 76, 77 ou 78 et être composé de 9 chiffres.
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control rounded-3 shadow-sm @error('email') is-invalid @enderror" 
                           placeholder="Ex: agent@example.com" required value="{{ old('email') }}">
                    <div class="invalid-feedback @if(!$errors->has('email')) d-none @endif">
                        @error('email')
                            {{ $message }}
                        @else
                            Veuillez entrer une adresse email valide.
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="profile_photo" class="form-label">Photo de profil (optionnel)</label>
                    <input type="file" name="profile_photo" id="profile_photo" class="form-control rounded-3 shadow-sm @error('profile_photo') is-invalid @enderror" accept="image/*">
                    @error('profile_photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 col-md-6">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control rounded-3 shadow-sm @error('password') is-invalid @enderror" 
                           placeholder="Mot de passe" required>
                    <div class="invalid-feedback @if(!$errors->has('password')) d-none @endif">
                        @error('password')
                            {{ $message }}
                        @else
                            Le mot de passe doit contenir au moins 6 caractères.
                        @enderror
                    </div>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="password_confirmation" class="form-label">Confirmer mot de passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-3 shadow-sm" 
                           placeholder="Confirmer" required>
                    <div class="invalid-feedback d-none">Les mots de passe ne correspondent pas.</div>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-pill">
                    S'inscrire
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
    
    // Objets pour suivre l'état de chaque champ
    const fieldTouched = {};
    
    // Au chargement initial, supprimer toutes les classes is-invalid des champs
    // sauf si nous sommes revenus d'une soumission avec des erreurs Laravel
    const formFields = form.querySelectorAll('input');
    const hasLaravelErrors = document.querySelector('.alert-danger') !== null;
    
    if (!hasLaravelErrors) {
        formFields.forEach(field => {
            if (!field.classList.contains('@error(' + field.name + ') is-invalid @enderror')) {
                field.classList.remove('is-invalid');
            }
        });
    }
    
    // Fonction pour valider un champ
    function validateField(field) {
        let isValid = true;
        
        // Si le champ n'a pas encore été touché, ne pas valider
        if (!fieldTouched[field.id] && field.value.trim() === '') {
            field.classList.remove('is-invalid');
            return true;
        }
        
        // Validation du prénom
        if (field.id === 'first_name') {
            if (field.value.trim().length < 3 && field.value.trim() !== '') {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        }
        
        // Validation du nom
        if (field.id === 'last_name') {
            if (field.value.trim() === '' && fieldTouched[field.id]) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        }
        
        // Validation de l'adresse
        if (field.id === 'address') {
            if (field.value.trim() === '' && fieldTouched[field.id]) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        }
        
        // Validation du téléphone
        if (field.id === 'phone_number') {
            const phoneRegex = /^(77|76|78|75)[0-9]{7}$/;
            if (field.value.trim() !== '' && !phoneRegex.test(field.value)) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        }
        
        // Validation de l'email
        if (field.id === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (field.value.trim() !== '' && !emailRegex.test(field.value)) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        }
        
        // Validation du mot de passe
        if (field.id === 'password') {
            if (field.value.trim() !== '' && field.value.trim().length < 6) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
                
                // Vérifier aussi la confirmation du mot de passe si elle existe déjà
                const confirmPassword = document.getElementById('password_confirmation');
                if (confirmPassword.value.trim() !== '') {
                    validateField(confirmPassword);
                }
            }
        }
        
        // Validation de la confirmation du mot de passe
        if (field.id === 'password_confirmation') {
            const password = document.getElementById('password');
            if (field.value.trim() !== '' && field.value !== password.value) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        }
        
        return isValid;
    }
    
    // Surveiller les événements sur tous les champs
    const formFields = form.querySelectorAll('input');
    formFields.forEach(field => {
        // Marquer le champ comme touché lorsque l'utilisateur commence à saisir
        field.addEventListener('input', function() {
            fieldTouched[this.id] = true;
            validateField(this);
        });
        
        // Valider lors de la perte de focus
        field.addEventListener('blur', function() {
            fieldTouched[this.id] = true;
            validateField(this);
        });
    });
    
    // Validation du formulaire lors de la soumission
    form.addEventListener('submit', function(e) {
        let formValid = true;
        
        // Marquer tous les champs comme touchés
        formFields.forEach(field => {
            fieldTouched[field.id] = true;
        });
        
        // Valider tous les champs
        formFields.forEach(field => {
            if (!validateField(field)) {
                formValid = false;
            }
        });
        
        // Vérifier spécifiquement que les mots de passe correspondent
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        if (password.value !== confirmPassword.value && password.value.trim() !== '') {
            password.classList.add('is-invalid');
            confirmPassword.classList.add('is-invalid');
            formValid = false;
        }
        
        // Empêcher l'envoi du formulaire si non valide
        if (!formValid) {
            e.preventDefault();
        }
    });
});
</script>
</body>
</html>