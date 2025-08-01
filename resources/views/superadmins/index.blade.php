@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    <style>
        .table-spacing td, .table-spacing th {
            padding-left: 15px;
            padding-right: 15px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <h1 class="text-center flex-grow-1">📋 Liste des Utilisateurs</h1>

                <!-- Message d'information -->
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Nouvelles fonctionnalités :</strong> Vous pouvez maintenant voir les détails et modifier les informations des utilisateurs en cliquant sur les boutons d'action.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <!-- Filtre par rôle -->
                <div class="filter-section">
                    <form method="get" action="{{ route('superadmins.index') }}" class="d-flex align-items-center">
                        <label for="roleFilter" class="me-2 text-sm">Filtrer par rôle:</label>
                        <select name="role" id="roleFilter" class="form-select me-2 text-sm" onchange="this.form.submit()">
                            <option value="all" {{ $roleFilter == 'all' || !$roleFilter ? 'selected' : '' }}>Tous les rôles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ $roleFilter == $role ? 'selected' : '' }}>
                                    {{ ucfirst($role) }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped shadow-sm rounded table-spacing text-xs">
                    <thead class="table-dark text-center">
                        <tr>
                            <th class="text-nowrap py-2">Prénom</th>
                            <th class="text-nowrap py-2">Nom</th>
                            <th class="text-nowrap py-2">Email</th>
                            <th class="text-nowrap py-2">Rôle</th>
                            <th class="text-nowrap py-2">Adresse</th>
                            <th class="text-nowrap py-2">Téléphone</th>
                            <th class="text-nowrap py-2">Points</th>
                            <th class="text-nowrap py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        @forelse($users as $user)
                            <tr>
                                <td class="px-2 py-1">{{ $user->first_name }}</td>
                                <td class="px-2 py-1">{{ $user->last_name }}</td>
                                <td class="px-2 py-1">{{ $user->email }}</td>
                                <td class="px-2 py-1">
                                    @foreach($user->getRoleNames() as $role)
                                        <span class="badge bg-primary text-xs">{{ ucfirst($role) }}</span>
                                    @endforeach
                                </td>
                                <td class="px-2 py-1">{{ $user->address }}</td>
                                <td class="px-2 py-1">{{ $user->phone_number }}</td>
                                <td class="px-2 py-1">{{ $user->points }}</td>
                                <td class="px-2 py-1">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info me-1 p-1" title="Voir les détails">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning me-1 p-1" title="Modifier">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <form method="POST" action="{{ route('users.destroy', $user->id) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger p-1" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')" title="Supprimer">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Aucun utilisateur trouvé</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $users->withQueryString()->links() }}
            </div>

            <!-- Guide d'utilisation -->
            <div class="mt-4">
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-question-circle me-2"></i>Guide d'utilisation
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h6 class="text-primary">
                                    <i class="fas fa-eye me-1"></i>Voir les détails
                                </h6>
                                <p class="small text-muted">Cliquez sur l'icône <i class="fas fa-eye text-info"></i> pour voir toutes les informations d'un utilisateur, y compris sa photo de profil et son historique.</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-warning">
                                    <i class="fas fa-edit me-1"></i>Modifier
                                </h6>
                                <p class="small text-muted">Cliquez sur l'icône <i class="fas fa-edit text-warning"></i> pour modifier les informations d'un utilisateur, changer son rôle ou sa photo de profil.</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-danger">
                                    <i class="fas fa-trash me-1"></i>Supprimer
                                </h6>
                                <p class="small text-muted">Cliquez sur l'icône <i class="fas fa-trash text-danger"></i> pour supprimer un utilisateur (action irréversible).</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
@endsection
