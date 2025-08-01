@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>Détails de l'Utilisateur
                    </h5>
                    <div class="btn-group" role="group">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-light btn-sm" title="Modifier">
                            <i class="fas fa-edit me-1"></i>Modifier
                        </a>
                        <a href="{{ route('superadmins.index') }}" class="btn btn-outline-light btn-sm" title="Retour à la liste">
                            <i class="fas fa-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                     alt="Photo de profil"
                                     class="img-fluid rounded-circle mb-3"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width: 150px; height: 150px;">
                                    <i class="fas fa-user fa-3x text-white"></i>
                                </div>
                            @endif

                            <div class="mb-2">
                                @foreach($user->getRoleNames() as $role)
                                    <span class="badge bg-primary fs-6">{{ ucfirst($role) }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Prénom</label>
                                    <p class="form-control-plaintext">{{ $user->first_name ?? 'Non renseigné' }}</p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Nom</label>
                                    <p class="form-control-plaintext">{{ $user->last_name ?? 'Non renseigné' }}</p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Email</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-envelope me-1 text-primary"></i>
                                        {{ $user->email }}
                                    </p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Téléphone</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-phone me-1 text-primary"></i>
                                        {{ $user->phone_number }}
                                    </p>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold text-muted">Adresse</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-map-marker-alt me-1 text-primary"></i>
                                        {{ $user->address ?? 'Non renseignée' }}
                                    </p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Points</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-star me-1 text-warning"></i>
                                        {{ $user->points ?? 0 }}
                                    </p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Date de création</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-calendar me-1 text-primary"></i>
                                        {{ $user->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Dernière modification</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-clock me-1 text-primary"></i>
                                        {{ $user->updated_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Statut</label>
                                    <p class="form-control-plaintext">
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Vérifié
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-exclamation-triangle me-1"></i>Non vérifié
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
