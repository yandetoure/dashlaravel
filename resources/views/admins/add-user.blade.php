<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<style>
    .container {
    max-width: 600px; /* Réduit la largeur du formulaire */
}

.card {
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.card-header {
    font-weight: bold;
    text-align: center;
}

.form-control {
    border-radius: 8px;
    padding: 10px;
}

.btn-success {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    font-size: 16px;
}

.alert {
    border-radius: 8px;
    padding: 10px;
}

</style>
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Ajouter un utilisateur</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.create.account') }}" method="POST"  enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="user_type" class="form-label">Type d'utilisateur</label>
                    <select class="form-control" name="role" id="user_type" required>
                        <option value="client">Client</option>
                        <option value="agent">Agent</option>
                        <option value="entreprise">Entreprise</option>
                        <option value="chauffeur">Chauffeur</option>
                        <option value="admin">Admin</option>
                        <option value="super-admin">Super-Admin</option>
                    </select>
                </div>

                <div class="mb-3" id="first_name_field">
                    <label for="first_name" class="form-label">Prénom</label>
                    <input type="text" class="form-control" name="first_name" id="first_name">
                </div>

                <div class="mb-3" id="last_name_field">
                    <label for="last_name" class="form-label">Nom</label>
                    <input type="text" class="form-control" name="last_name" id="last_name">
                </div>

                <div class="mb-3 d-none" id="company_name_field">
                    <label for="name" class="form-label">Nom de l'entreprise</label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Adresse</label>
                    <input type="text" class="form-control" name="address" id="address" required>
                </div>

                <div class="mb-3">
                    <label for="phone_number" class="form-label">Numéro de téléphone</label>
                    <input type="text" class="form-control" name="phone_number" id="phone_number" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>

                <div class="mb-3">
                    <label for="profile_photo" class="form-label">Photo de profil</label>
                    <input type="file" class="form-control" name="profile_photo" id="profile_photo" accept="image/*">
                </div>

                <button type="submit" class="btn btn-success">Créer l'utilisateur</button>
            </form>
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
            companyNameField.classList.remove('d-none');
            firstNameField.classList.add('d-none');
            lastNameField.classList.add('d-none');
        } else {
            companyNameField.classList.add('d-none');
            firstNameField.classList.remove('d-none');
            lastNameField.classList.remove('d-none');
        }
    });
});
</script>
@endsection
