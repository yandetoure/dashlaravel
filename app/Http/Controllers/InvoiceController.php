<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class InvoiceController extends Controller
{
    /**
     * Affiche la liste des factures
     */

     public function index(Request $request)
    {
        $user = Auth::user();
        
        // Construire la requête de base avec les relations optimisées
        $query = Invoice::with([
            'reservation.client', 
            'reservation.trip',
            'reservation.carDriver.chauffeur'
        ]);

        // Appliquer les filtres
        $this->applyFilters($query, $request);
        
        // Appliquer les restrictions par rôle
        $this->applyRoleRestrictions($query, $user);
        
        // Appliquer les mêmes restrictions pour les statistiques
        $statsQuery = Invoice::query();
        $this->applyRoleRestrictions($statsQuery, $user);

        // Obtenir les factures avec pagination
        $invoices = $query->orderBy('invoice_date', 'desc')->paginate(15);

        // Calculer les statistiques optimisées
        $stats = $this->calculateStats($statsQuery);

        return view('invoices.index', compact('invoices', 'stats'));
    }

    /**
     * Appliquer les filtres de recherche
     */
    private function applyFilters($query, Request $request): void
    {
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }
        
        if ($request->filled('invoice_number')) {
            $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
        }
        
        if ($request->filled('client_name')) {
            $query->where(function($q) use ($request) {
                // Rechercher dans les clients enregistrés
                $q->whereHas('reservation.client', function ($subQ) use ($request) {
                    $subQ->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $request->client_name . '%')
                         ->orWhere('email', 'like', '%' . $request->client_name . '%');
                })
                // Ou dans les prospects
                ->orWhereHas('reservation', function ($subQ) use ($request) {
                    $subQ->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $request->client_name . '%')
                         ->orWhere('email', 'like', '%' . $request->client_name . '%');
                });
            });
        }
    }

    /**
     * Appliquer les restrictions par rôle
     */
    private function applyRoleRestrictions($query, $user): void
    {
        if ($user->hasRole('client')) {
            $query->whereHas('reservation', function ($q) use ($user) {
                $q->where('client_id', $user->id);
            });
        }

        if ($user->hasRole('chauffeur')) {
            $query->whereHas('reservation.carDriver', function ($q) use ($user) {
                $q->where('chauffeur_id', $user->id);
            });
        }
    }

    /**
     * Calculer les statistiques optimisées
     */
    private function calculateStats($query): array
    {
        $baseQuery = clone $query;
        
        return [
            'total' => (int) $baseQuery->count(),
            'paid' => (int) (clone $baseQuery)->where('status', 'payé')->count(),
            'pending' => (int) (clone $baseQuery)->where('status', 'en_attente')->count(),
            'free' => (int) (clone $baseQuery)->where('status', 'offert')->count(),
            'total_amount' => (float) $baseQuery->sum('amount'),
            'paid_amount' => (float) (clone $baseQuery)->where('status', 'payé')->sum('amount'),
            'pending_amount' => (float) (clone $baseQuery)->where('status', 'en_attente')->sum('amount'),
            'free_amount' => (float) (clone $baseQuery)->where('status', 'offert')->sum('amount'),
        ];
    }

    // public function index(Request $request)
    // {
    //     $user = Auth::user();
    //     $query = Invoice::with(['reservation.client', 'reservation.trip']);

    //     // Filtrage par statut de facture
    //     if ($request->has('status') && $request->status != '') {
    //         $query->where('status', $request->status);
    //     }

    //     // Filtrage par date
    //     if ($request->has('date_from') && $request->date_from != '') {
    //         $query->whereDate('invoice_date', '>=', $request->date_from);
    //     }

    //     if ($request->has('date_to') && $request->date_to != '') {
    //         $query->whereDate('invoice_date', '<=', $request->date_to);
    //     }

    //     // Si c'est un client, ne montrer que ses factures
    //     if ($user->hasRole('client')) {
    //         $query->whereHas('reservation', function ($q) use ($user) {
    //             $q->where('client_id', $user->id);
    //         });
    //     }

    //     // Si c'est un chauffeur, ne montrer que les factures où il est assigné
    //     if ($user->hasRole('chauffeur')) {
    //         $query->whereHas('reservation.carDriver', function ($q) use ($user) {
    //             $q->where('chauffeur_id', $user->id);
    //         });
    //     }

    //     // Si recherche par numéro de facture
    //     if ($request->has('invoice_number') && $request->invoice_number != '') {
    //         $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
    //     }

    //     // Si recherche par nom de client
    //     if ($request->has('client_name') && $request->client_name != '') {
    //         $query->whereHas('reservation.client', function ($q) use ($request) {
    //             $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $request->client_name . '%');
    //         });
    //     }

    //     $invoices = $query->orderBy('invoice_date', 'desc')->paginate(10);

    //     // Obtenir les statistiques pour le tableau de bord
    //     $stats = [
    //         'total' => Invoice::count(),
    //         'paid' => Invoice::where('status', 'Payée')->count(),
    //         'pending' => Invoice::where('status', 'En attente')->count(),
    //         'overdue' => Invoice::where('status', 'En retard')->count(),
    //     ];

    //     return view('invoices.index', compact('invoices', 'stats'));
    // }


    /**
     * Affiche les détails d'une facture spécifique
     */
    public function show($id)
    {
        $invoice = Invoice::with(['reservation.client', 'reservation.trip', 'reservation.carDriver.chauffeur'])->findOrFail($id);
        if (!$invoice->reservation) {
            // Gérer le cas où la réservation n'existe pas
            abort(404, 'Réservation non trouvée pour cette facture.');
        }
        return view('invoices.show', compact('invoice'));
    }


    /**
     * Afficher le formulaire de création de facture
     */
    public function create()
    {
        if (!Auth::user()->hasAnyRole(['admin', 'agent', 'super-admin'])) {
            abort(403, 'Vous n\'êtes pas autorisé à créer des factures.');
        }

        // Récupérer les trajets disponibles pour créer une nouvelle réservation
        $trips = \App\Models\Trip::orderBy('created_at', 'desc')->get();

        return view('invoices.create', compact('trips'));
    }

    /**
     * Créer une nouvelle facture
     */
    public function store(Request $request)
    {
        if (!Auth::user()->hasAnyRole(['admin', 'agent', 'super-admin'])) {
            abort(403, 'Vous n\'êtes pas autorisé à créer des factures.');
        }

        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'nb_personnes' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:en_attente,payé,offert',
            'note' => 'nullable|string|max:500',
            'date' => 'required|date',
            'heure_ramassage' => 'required',
            'adresse_ramassage' => 'required|string|max:255',
            'numero_vol' => 'required|string|max:50',
            'nb_valises' => 'required|integer|min:0'
        ]);

        // Créer une nouvelle réservation
        $reservation = Reservation::create([
            'trip_id' => $request->trip_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'nb_personnes' => $request->nb_personnes,
            'tarif' => $request->amount,
            'status' => 'Confirmée', // Réservation automatiquement confirmée
            'date' => $request->date,
            'heure_ramassage' => $request->heure_ramassage,
            'adresse_ramassage' => $request->adresse_ramassage,
            'numero_vol' => $request->numero_vol,
            'nb_valises' => $request->nb_valises,
            'note' => $request->note
        ]);

        // Créer la facture liée à cette réservation
        $invoice = Invoice::create([
            'reservation_id' => $reservation->id,
            'amount' => $request->amount,
            'status' => $request->status,
            'invoice_number' => 'INV-' . strtoupper(uniqid()),
            'invoice_date' => now(),
            'note' => $request->note
        ]);

        // Log de la création
        \Log::info('Facture et réservation créées manuellement', [
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'reservation_id' => $reservation->id,
            'amount' => $invoice->amount,
            'client_name' => $reservation->first_name . ' ' . $reservation->last_name,
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Facture et réservation créées avec succès.');
    }

    /**
     * Télécharger la facture en PDF
     */
    public function downloadPdf(Invoice $invoice)
    {
        $user = Auth::user();

        // Vérifier les permissions
        if ($user->hasRole('client') && $invoice->reservation->client_id != $user->id) {
            abort(403, 'Vous n\'êtes pas autorisé à télécharger cette facture.');
        }

        if ($user->hasRole('chauffeur')) {
            $carDriverIds = $user->car_drivers->pluck('id');
            if (!$carDriverIds->contains($invoice->reservation->cardriver_id)) {
                abort(403, 'Vous n\'êtes pas autorisé à télécharger cette facture.');
            }
        }

        $invoice->load(['reservation.client', 'reservation.trip', 'reservation.carDriver.chauffeur']);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'))
            ->setPaper('A4', 'portrait')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download('facture-' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Marquer une facture comme payée
     */
    public function markAsPaid(Invoice $invoice)
    {
        // Debug: Log de la requête
        \Log::info('markAsPaid appelé', [
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A',
            'request_method' => request()->method(),
            'csrf_token' => request()->header('X-CSRF-TOKEN'),
            'form_token' => request()->input('_token')
        ]);

        // Vérifier que l'utilisateur a les droits pour modifier les factures
        if (!Auth::user()->hasAnyRole(['admin', 'agent', 'super-admin'])) {
            abort(403, 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        // Vérifier que la facture n'est pas déjà payée
        if ($invoice->status === 'payé') {
            return redirect()->back()->with('error', 'Cette facture est déjà marquée comme payée.');
        }

        // Mettre à jour la facture avec la date de paiement
        $invoice->update([
            'status' => 'payé',
            'paid_at' => now(),
            'payment_method' => $invoice->payment_method ?: 'Manuel'
        ]);

        // Log de l'action
        \Log::info('Facture marquée comme payée manuellement', [
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'amount' => $invoice->amount,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name ?? Auth::user()->email
        ]);

        return redirect()->back()->with('success', 'La facture ' . $invoice->invoice_number . ' a été marquée comme payée avec succès.');
    }
}
