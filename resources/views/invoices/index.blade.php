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
<div class="container-fluid py-4">
    <!-- En-tête principal avec navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h4 mb-1 text-white">
                                <i class="fas fa-file-invoice me-2"></i>
                                Gestion des Factures
                            </h1>
                            <p class="text-white-50 mb-0">Suivez et gérez toutes vos factures de transport</p>
                        </div>
                        <div>
                            @if(auth()->user()->hasAnyRole(['admin', 'agent', 'super-admin']))
                                <a href="{{ route('invoices.create') }}" class="btn btn-light">
                                    <i class="fas fa-plus me-1"></i> Nouvelle Facture
                                </a>
                            @endif
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
                            <li class="breadcrumb-item active" aria-current="page">
                                <i class="fas fa-file-invoice me-1"></i> Factures
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

    <!-- Cartes statistiques modernes -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-file-invoice text-white fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="card-title text-muted mb-1">Total Factures</h6>
                        <h3 class="mb-0 text-dark">{{ $stats['total'] }}</h3>
                        <small class="text-success fw-bold">{{ number_format((float) $stats['total_amount'], 0) }} XOF</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-check-circle text-white fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="card-title text-muted mb-1">Payées</h6>
                        <h3 class="mb-0 text-dark">{{ $stats['paid'] }}</h3>
                        <small class="text-success fw-bold">{{ number_format((float) $stats['paid_amount'], 0) }} XOF</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-clock text-white fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="card-title text-muted mb-1">En Attente</h6>
                        <h3 class="mb-0 text-dark">{{ $stats['pending'] }}</h3>
                        <small class="text-warning fw-bold">{{ number_format((float) $stats['pending_amount'], 0) }} XOF</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-gift text-white fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="card-title text-muted mb-1">Gratuites</h6>
                        <h3 class="mb-0 text-dark">{{ $stats['free'] }}</h3>
                        <small class="text-info fw-bold">{{ number_format((float) $stats['free_amount'], 0) }} XOF</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des factures moderne -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2 text-primary"></i>
                    Liste des Factures
                </h5>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-dark">{{ $invoices->total() }} facture(s)</span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
                    <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 py-3 px-4">
                                <i class="fas fa-user me-1 text-muted"></i>
                                Client
                            </th>
                            <th class="border-0 py-3 px-4">
                                <i class="fas fa-calendar me-1 text-muted"></i>
                                Date
                            </th>
                            <th class="border-0 py-3 px-4">
                                <i class="fas fa-money-bill me-1 text-muted"></i>
                                Montant
                            </th>
                            <th class="border-0 py-3 px-4">
                                <i class="fas fa-tag me-1 text-muted"></i>
                                Statut
                            </th>
                            <th class="border-0 py-3 px-4">
                                <i class="fas fa-cogs me-1 text-muted"></i>
                                Actions
                            </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                            <tr class="border-bottom">
                              
                                <td class="py-3 px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-info"></i>
                                        </div>
                                        <div>
                                            @if($invoice->reservation->client)
                                                <div class="fw-semibold text-dark">
                                                {{ $invoice->reservation->client->first_name }} {{ $invoice->reservation->client->last_name }}
                                                </div>
                                                <small class="text-muted">{{ $invoice->reservation->client->email }}</small>
                                            @else
                                                <div class="fw-semibold text-dark">
                                                    {{ $invoice->reservation->first_name }} {{ $invoice->reservation->last_name }}
                                                </div>
                                                <small class="text-muted">Prospect</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-dark fw-semibold">
                                        {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}
                                    </div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('H:i') }}
                                    </small>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="fw-bold text-success fs-5">
                                        {{ number_format((float) $invoice->amount, 0) }} XOF
                                    </div>
                                    @if($invoice->payment_method)
                                        <small class="text-muted">
                                            <i class="fas fa-credit-card me-1"></i>
                                            {{ $invoice->payment_method }}
                                        </small>
                                            @endif
                                        </td>
                                <td class="py-3 px-4">
                                             @if($invoice->status == 'payé')
                                        <span class="badge bg-success bg-gradient px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>Payée
                                        </span>
                                        @elseif($invoice->status == 'en_attente')
                                        <span class="badge bg-warning bg-gradient px-3 py-2">
                                            <i class="fas fa-clock me-1"></i>En attente
                                        </span>
                                        @elseif($invoice->status == 'offert')
                                        <span class="badge bg-info bg-gradient px-3 py-2">
                                            <i class="fas fa-gift me-1"></i>Gratuit
                                        </span>
                                        @else
                                        <span class="badge bg-secondary bg-gradient px-3 py-2">
                                            {{ $invoice->status }}
                                        </span>
                                        @endif
                                        </td>
                                <td class="py-3 px-4">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('invoices.show', $invoice->id) }}" 
                                           class="btn btn-outline-primary btn-sm" 
                                           data-bs-toggle="tooltip" title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <a href="{{ route('invoices.download', $invoice->id) }}" 
                                           class="btn btn-outline-secondary btn-sm"
                                           data-bs-toggle="tooltip" title="Télécharger PDF">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        
                                        @if($invoice->status == 'en_attente' && $invoice->reservation)
                                            <a href="{{ route('reservations.pay.direct', $invoice->reservation->id) }}" 
                                               class="btn btn-success btn-sm"
                                               data-bs-toggle="tooltip" title="Payer avec NabooPay">
                                                <i class="fas fa-credit-card"></i>
                                            </a>
                                            
                                            <a href="{{ route('invoices.qrcode', $invoice->id) }}" 
                                               class="btn btn-outline-info btn-sm"
                                               data-bs-toggle="tooltip" title="Générer QR Code">
                                                <i class="fas fa-qrcode"></i>
                                            </a>
                                            
                                            <a href="{{ route('invoices.whatsapp', $invoice->id) }}" 
                                               class="btn btn-outline-success btn-sm"
                                               data-bs-toggle="tooltip" title="Envoyer par WhatsApp" target="_blank">
                                                <i class="fab fa-whatsapp"></i>
                                            </a>
                                        @endif
                                        
                                        @if(auth()->user()->hasAnyRole(['admin', 'agent', 'super-admin']))
                                            @if($invoice->status == 'payé')
                                                <span class="btn btn-success btn-sm disabled" 
                                                      data-bs-toggle="tooltip" title="Facture payée">
                                                    <i class="fas fa-check-circle"></i>
                                                </span>
                                            @else
                                                <form action="{{ route('invoices.markAsPaid', $invoice->id) }}" method="POST" class="d-inline" id="markPaidForm{{ $invoice->id }}">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-outline-success btn-sm"
                                                            data-bs-toggle="tooltip" 
                                                            title="Marquer comme payée manuellement"
                                                            onclick="return markAsPaid({{ $invoice->id }}, '{{ $invoice->invoice_number }}')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-file-invoice fa-3x mb-3"></i>
                                        <h5>Aucune facture trouvée</h5>
                                        <p>Il n'y a pas de factures correspondant à vos critères de recherche.</p>
                                    </div>
                                </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
            </div>
                    </div>

        @if($invoices->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-center">
                        {{ $invoices->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<script>
// Initialiser les tooltips Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Fonction pour marquer une facture comme payée
function markAsPaid(invoiceId, invoiceNumber) {
    if (confirm('Êtes-vous sûr de vouloir marquer la facture ' + invoiceNumber + ' comme payée ?\n\nCette action ne peut pas être annulée.')) {
        // Afficher un indicateur de chargement
        const button = document.querySelector('#markPaidForm' + invoiceId + ' button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;
        
        // Soumettre le formulaire
        document.getElementById('markPaidForm' + invoiceId).submit();
        
        return true;
    }
    return false;
}
</script>
@endsection
