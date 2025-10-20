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

                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold text-muted">Gestion du mot de passe</label>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-warning btn-sm" onclick="generateTempPassword({{ $user->id }})">
                                            <i class="fas fa-key me-1"></i>Générer mot de passe temporaire
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm" onclick="resetPassword({{ $user->id }})">
                                            <i class="fas fa-redo me-1"></i>Réinitialiser mot de passe
                                        </button>
                                    </div>
                                    <div id="temp-password-{{ $user->id }}" class="mt-2" style="display: none;">
                                        <div class="alert alert-info">
                                            <strong>Mot de passe temporaire généré :</strong>
                                            <span id="temp-password-value-{{ $user->id }}" class="font-monospace"></span>
                                            <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('temp-password-value-{{ $user->id }}')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generateTempPassword(userId) {
    if (confirm('Êtes-vous sûr de vouloir générer un nouveau mot de passe temporaire pour cet utilisateur ?')) {
        fetch(`/admin/users/${userId}/generate-temp-password`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`temp-password-value-${userId}`).textContent = data.temp_password;
                document.getElementById(`temp-password-${userId}`).style.display = 'block';
                
                // Afficher une notification de succès
                showNotification('Mot de passe temporaire généré avec succès !', 'success');
            } else {
                showNotification('Erreur lors de la génération du mot de passe temporaire', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erreur lors de la génération du mot de passe temporaire', 'error');
        });
    }
}

function resetPassword(userId) {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser le mot de passe de cet utilisateur ? Un email sera envoyé à l\'utilisateur.')) {
        fetch(`/admin/users/${userId}/reset-password`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Email de réinitialisation envoyé à l\'utilisateur', 'success');
            } else {
                showNotification('Erreur lors de l\'envoi de l\'email de réinitialisation', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erreur lors de l\'envoi de l\'email de réinitialisation', 'error');
        });
    }
}

function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    const text = element.textContent;
    
    navigator.clipboard.writeText(text).then(function() {
        showNotification('Mot de passe copié dans le presse-papiers !', 'success');
    }, function(err) {
        console.error('Could not copy text: ', err);
        showNotification('Erreur lors de la copie', 'error');
    });
}

function showNotification(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}
</script>
@endsection
