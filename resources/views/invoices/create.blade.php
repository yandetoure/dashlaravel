<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('title', 'Créer une Nouvelle Facture')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête principal avec navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h4 mb-1 text-white">
                                <i class="fas fa-plus-circle me-2"></i>
                                Créer une Nouvelle Facture
                            </h1>
                            <p class="text-white-50 mb-0">Créez une facture avec une nouvelle réservation</p>
                        </div>
                        <div>
                            <a href="{{ route('invoices.index') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-white py-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}" class="text-decoration-none">
                                    <i class="fas fa-home me-1"></i> Tableau de bord
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('invoices.index') }}" class="text-decoration-none">
                                    <i class="fas fa-file-invoice me-1"></i> Factures
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <i class="fas fa-plus me-1"></i> Nouvelle facture
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages d'alerte -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Formulaire de création -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2 text-primary"></i>
                        Informations de la Réservation et Facture
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
                        @csrf
                        
                        <!-- Informations du client -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-semibold text-primary mb-3">
                                    <i class="fas fa-user me-1"></i>
                                    Informations du Client
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <label for="first_name" class="form-label fw-semibold">
                                    Prénom <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" 
                                       name="first_name" 
                                       value="{{ old('first_name') }}" 
                                       required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label fw-semibold">
                                    Nom <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('last_name') is-invalid @enderror" 
                                       id="last_name" 
                                       name="last_name" 
                                       value="{{ old('last_name') }}" 
                                       required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="email" class="form-label fw-semibold">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="phone_number" class="form-label fw-semibold">
                                    Téléphone <span class="text-danger">*</span>
                                </label>
                                <input type="tel" 
                                       class="form-control @error('phone_number') is-invalid @enderror" 
                                       id="phone_number" 
                                       name="phone_number" 
                                       value="{{ old('phone_number') }}" 
                                       required>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Informations du trajet -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-semibold text-primary mb-3">
                                    <i class="fas fa-route me-1"></i>
                                    Informations du Trajet
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <label for="trip_id" class="form-label fw-semibold">
                                    Trajet <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('trip_id') is-invalid @enderror" 
                                        id="trip_id" name="trip_id" required>
                                    <option value="">Sélectionnez un trajet...</option>
                                    @foreach($trips as $trip)
                                        <option value="{{ $trip->id }}" 
                                                data-price="{{ $trip->price ?? 0 }}"
                                                {{ old('trip_id') == $trip->id ? 'selected' : '' }}>
                                            {{ $trip->departure }} → {{ $trip->destination }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('trip_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="nb_personnes" class="form-label fw-semibold">
                                    Nombre de personnes <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('nb_personnes') is-invalid @enderror" 
                                       id="nb_personnes" 
                                       name="nb_personnes" 
                                       value="{{ old('nb_personnes', 1) }}" 
                                       min="1" 
                                       required>
                                @error('nb_personnes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="date" class="form-label fw-semibold">
                                    Date du voyage <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control @error('date') is-invalid @enderror" 
                                       id="date" 
                                       name="date" 
                                       value="{{ old('date') }}" 
                                       required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="heure_ramassage" class="form-label fw-semibold">
                                    Heure de ramassage <span class="text-danger">*</span>
                                </label>
                                <input type="time" 
                                       class="form-control @error('heure_ramassage') is-invalid @enderror" 
                                       id="heure_ramassage" 
                                       name="heure_ramassage" 
                                       value="{{ old('heure_ramassage') }}" 
                                       required>
                                @error('heure_ramassage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="adresse_ramassage" class="form-label fw-semibold">
                                    Adresse de ramassage <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('adresse_ramassage') is-invalid @enderror" 
                                       id="adresse_ramassage" 
                                       name="adresse_ramassage" 
                                       value="{{ old('adresse_ramassage') }}" 
                                       placeholder="Ex: Dakar, Sénégal"
                                       required>
                                @error('adresse_ramassage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="numero_vol" class="form-label fw-semibold">
                                    Numéro de vol <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('numero_vol') is-invalid @enderror" 
                                       id="numero_vol" 
                                       name="numero_vol" 
                                       value="{{ old('numero_vol') }}" 
                                       placeholder="Ex: AF123"
                                       required>
                                @error('numero_vol')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="nb_valises" class="form-label fw-semibold">
                                    Nombre de valises <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('nb_valises') is-invalid @enderror" 
                                       id="nb_valises" 
                                       name="nb_valises" 
                                       value="{{ old('nb_valises', 1) }}" 
                                       min="0" 
                                       required>
                                @error('nb_valises')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Informations de la facture -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-semibold text-primary mb-3">
                                    <i class="fas fa-file-invoice me-1"></i>
                                    Informations de la Facture
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <label for="amount" class="form-label fw-semibold">
                                    Montant (XOF) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" 
                                           name="amount" 
                                           value="{{ old('amount') }}" 
                                           min="0" 
                                           step="1" 
                                           required>
                                    <span class="input-group-text">XOF</span>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label fw-semibold">
                                    Statut de la facture <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="">Sélectionnez un statut...</option>
                                    <option value="en_attente" {{ old('status') == 'en_attente' ? 'selected' : '' }}>
                                        En attente de paiement
                                    </option>
                                    <option value="payée" {{ old('status') == 'payée' ? 'selected' : '' }}>
                                        Payée
                                    </option>
                                    <option value="offert" {{ old('status') == 'offert' ? 'selected' : '' }}>
                                        Gratuit/Offert
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 mt-3">
                                <label for="note" class="form-label fw-semibold">
                                    Note (optionnel)
                                </label>
                                <textarea class="form-control @error('note') is-invalid @enderror" 
                                          id="note" 
                                          name="note" 
                                          rows="3" 
                                          placeholder="Ajoutez une note ou un commentaire...">{{ old('note') }}</textarea>
                                @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Créer la Facture et Réservation
                            </button>
                            <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panneau d'information -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        Processus de Création
                    </h5>
                </div>
                <div class="card-body">
                    <div class="small">
                        <h6 class="fw-semibold mb-2">Étapes :</h6>
                        <ol class="mb-3">
                            <li>Remplissez les informations du client</li>
                            <li>Sélectionnez le trajet</li>
                            <li>Configurez les détails du voyage</li>
                            <li>Définissez le montant et statut</li>
                            <li>Créez la facture</li>
                        </ol>
                        
                        <h6 class="fw-semibold mb-2">Résultat :</h6>
                        <ul class="mb-0">
                            <li>✅ Nouvelle réservation créée</li>
                            <li>✅ Réservation automatiquement confirmée</li>
                            <li>✅ Facture générée</li>
                            <li>✅ Numéro de facture unique</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Aide -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-question-circle me-2 text-warning"></i>
                        Aide
                    </h5>
                </div>
                <div class="card-body">
                    <div class="small">
                        <h6 class="fw-semibold mb-2">Important :</h6>
                        <ul class="mb-0">
                            <li>La réservation sera automatiquement confirmée</li>
                            <li>Une fois créée, la facture ne peut pas être supprimée</li>
                            <li>Le numéro de facture est généré automatiquement</li>
                            <li>Tous les champs marqués (*) sont obligatoires</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tripSelect = document.getElementById('trip_id');
    const amountInput = document.getElementById('amount');

    tripSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const price = selectedOption.dataset.price;
            if (price && price > 0) {
                amountInput.value = price;
            }
        }
    });
});
</script>
@endsection
