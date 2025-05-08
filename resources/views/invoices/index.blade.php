<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations</title>
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
        .reservation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .status-badge {
            top: -10px;
            right: -10px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-4">Gestion des factures</h1>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <!-- Cartes statistiques -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total des factures</h5>
                            <h2 class="card-text">{{ $stats['total'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Factures payées</h5>
                            <h2 class="card-text">{{ $stats['paid'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h5 class="card-title">En attente</h5>
                            <h2 class="card-text">{{ $stats['pending'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h5 class="card-title">En retard</h5>
                            <h2 class="card-text">{{ $stats['overdue'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Cartes statistiques -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total des factures</h5>
                <h2 class="card-text mb-2">{{ number_format($stats['total_amount'], 2) }} Fcfa</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Factures payées</h5>
                <h4 class="card-text mb-2">{{ number_format($stats['paid_amount'], 2) }} Fcfa</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">En attente</h5>
                <h2 class="card-text mb-2">{{ number_format($stats['total_amount'] - $stats['paid_amount'], 2) }} Fcfa</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h5 class="card-title">En retard</h5>
                <h2 class="card-text mb-2">{{ number_format($stats['unpaid_amount'], 2) }}</h2>
            </div>
        </div>
    </div>
</div>
            <!-- Filtres -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Filtres</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('invoices.index') }}" method="GET" class="row">
                        <div class="col-md-2 mb-3">
                            <label for="invoice_number">N° de facture</label>
                            <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="{{ request('invoice_number') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="client_name">Nom du client</label>
                            <input type="text" class="form-control" id="client_name" name="client_name" value="{{ request('client_name') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="status">Statut</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Tous</option>
                                <option value="En attente" {{ request('status') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                <option value="Payée" {{ request('status') == 'payée' ? 'selected' : '' }}>Payée</option>
                                {{-- <option value="En retard" {{ request('status') == 'en_attente' ? 'selected' : '' }}>En retard</option> --}}
                                <option value="Annulée" {{ request('status') == 'offert' ? 'selected' : '' }}>Gratuit</option>
                            </select>
                        </div>
                        {{-- <div class="col-md-2 mb-3">
                            <label for="date_from">Date début</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="date_to">Date fin</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        </div> --}}
                        <div class="col-md-2 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">Filtrer</button>
                            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Réinitialiser</a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Tableau des factures -->
            <div class="card">
                <div class="card-header">
                    <h5>Liste des factures</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>N° Facture</th>
                                    <th>Client</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->invoice_number }}</td>
                                        <td>{{ $invoice->reservation->client->first_name }} {{ $invoice->reservation->client->last_name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
                                        <td>{{ number_format((float) $invoice->amount) }} Fcfa</td>
                                        <td>
                                             @if($invoice->status == 'payée')
                                        <span class="badge bg-success">Payée</span>
                                        @elseif($invoice->status == 'en_attente')
                                            <span class="badge bg-warning text-dark">En attente</span>
                                        @elseif($invoice->status == 'en_attente')
                                            <span class="badge bg-danger">Gratuit</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $invoice->status }}</span>
                                        @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Voir
                                                </a>
                                                <a href="{{ route('invoices.download', $invoice->id) }}" class="btn btn-sm btn-secondary">
                                                    <i class="fas fa-download"></i> PDF
                                                </a>
                                                @if($invoice->status != 'Payée')
                                                    <form action="{{ route('invoices.markAsPaid', $invoice->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Marquer cette facture comme payée?')">
                                                            <i class="fas fa-check"></i> Marquer payée
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Aucune facture trouvée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $invoices->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
@endsection