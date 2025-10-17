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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Facture {{ $invoice->invoice_number }}</h1>
                <div>
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    <a href="{{ route('invoices.download', $invoice->id) }}" class="btn btn-primary">
                        <i class="fas fa-download"></i> Télécharger PDF
                    </a>
                    @if(Auth::user()->can('manage invoices') && $invoice->status != 'Payée')
                        <form action="{{ route('invoices.markAsPaid', $invoice->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('Marquer cette facture comme payée?')">
                                <i class="fas fa-check"></i> Marquer comme payée
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row mb-4">
                        <!-- Informations de l'entreprise -->
                        <div class="col-sm-6">
                            {{-- <h6 class="mb-3">De:</h6> --}}
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 120px; width: auto;"class="logo-desktop">
                            <strong>Cpro Service</strong><br>
                            <div>Sacré cœur, Dakar, Sénégal</div>
                            <div>1000 Dakar, Sénégal</div>
                            <div>Email: 221cproservices@gmail.com</div>
                            <div>Téléphone: +221 77 705 67 67 </div>
                        </div>

                        <!-- Informations du client -->
                        <div class="col-sm-6">
                            <h6 class="mb-3">À:</h6>
                            <div>
                                <strong>
                                    @if($invoice->reservation->client)
                                        {{ $invoice->reservation->client->first_name }} {{ $invoice->reservation->client->last_name }}
                                    @else
                                        {{ $invoice->reservation->first_name }} {{ $invoice->reservation->last_name }} (Prospect)
                                    @endif
                                </strong>
                            </div>
                            <div>{{ $invoice->reservation->adresse_rammassage }}</div>
                            <div>Email:
                                @if($invoice->reservation->client)
                                    {{ $invoice->reservation->client->email }}
                                @else
                                    {{ $invoice->reservation->email }}
                                @endif
                            </div>
                            <div>Téléphone:
                                @if($invoice->reservation->client)
                                    {{ $invoice->reservation->client->phone_number }}
                                @else
                                    {{ $invoice->reservation->phone_number }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <div><strong>Numéro de facture:</strong> {{ $invoice->invoice_number }}</div>
                            <div><strong>Date de facturation:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div>
                                <strong>Statut:</strong>
                                @if($invoice->status == 'payé')
                                    <span class="badge bg-success">Payée</span>
                                @elseif($invoice->status == 'en_attente')
                                    <span class="badge bg-warning text-dark">En attente de paiement</span>
                                @elseif($invoice->status == 'offert')
                                    <span class="badge bg-danger">Offert</span>
                                @else
                                    <span class="badge bg-secondary">{{ $invoice->status }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Détails de la réservation -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5>Détails de la réservation</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Service</th>
                                            <th>Date</th>
                                            <th>Détails</th>
                                            <th class="text-end">Montant</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>Transport</strong>
                                                @if($invoice->reservation->trip)
                                                    <p class="mb-0">{{ $invoice->reservation->trip->departure }} -> {{ $invoice->reservation->trip->destination }}</p>
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($invoice->reservation->date)->format('d/m/Y') }}</td>
                                            <td>
                                                <p class="mb-0"><strong>Heure de ramassage:</strong> {{ $invoice->reservation->heure_ramassage }}</p>
                                                <p class="mb-0"><strong>Adresse:</strong> {{ $invoice->reservation->adresse_rammassage }}</p>
                                                <p class="mb-0"><strong>Nombre de personnes:</strong> {{ $invoice->reservation->nb_personnes }}</p>
                                                <p class="mb-0"><strong>Nombre de valises:</strong> {{ $invoice->reservation->nb_valises }}</p>
                                                @if($invoice->reservation->chauffeur)
                                                    <p class="mb-0"><strong>Chauffeur:</strong> {{ $invoice->reservation->chauffeur->first_name }} {{ $invoice->reservation->chauffeur->last_name }}</p>
                                                @endif
                                            </td>
                                            <td class="text-end">{{ number_format($invoice->amount) }} Fcfa</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total</strong></td>
                                            <td class="text-end"><strong>{{ number_format($invoice->amount) }} Fcfa</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Informations de paiement -->
                    {{-- <div class="row">
                        <div class="col-12">
                            <h5>Informations de paiement</h5>
                            <p>Veuillez effectuer votre paiement sur le compte bancaire suivant:</p>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th style="width: 30%">Banque</th>
                                            <td>Banque Exemple</td>
                                        </tr>
                                        <tr>
                                            <th>IBAN</th>
                                            <td>FR76 XXXX XXXX XXXX XXXX XXXX XXX</td>
                                        </tr>
                                        <tr>
                                            <th>BIC</th>
                                            <td>XXXXXXXX</td>
                                        </tr>
                                        <tr>
                                            <th>Référence</th>
                                            <td>{{ $invoice->invoice_number }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> --}}

                    <!-- Notes et conditions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <p><strong>Conditions de paiement:</strong> Paiement à réception de facture.</p>
                            <p><strong>Note:</strong> Merci de votre confiance. Pour toute question concernant cette facture, veuillez nous contacter.</p>
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
