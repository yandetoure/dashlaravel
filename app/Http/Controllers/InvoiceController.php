<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;


class InvoiceController extends Controller
{
    /**
     * Affiche la liste des factures
     */

     public function index(Request $request)
{
    $user = Auth::user();
    $query = Invoice::with(['reservation.client', 'reservation.trip']);

    // Apply filters based on the request
    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }
    if ($request->has('date_from') && $request->date_from != '') {
        $query->whereDate('invoice_date', '>=', $request->date_from);
    }
    if ($request->has('date_to') && $request->date_to != '') {
        $query->whereDate('invoice_date', '<=', $request->date_to);
    }
    if ($request->has('invoice_number') && $request->invoice_number != '') {
        $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
    }
    if ($request->has('client_name') && $request->client_name != '') {
        $query->whereHas('reservation.client', function ($q) use ($request) {
            $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $request->client_name . '%');
        });
    }

    // Only show a client's invoices
    if ($user->hasRole('client')) {
        $query->whereHas('reservation', function ($q) use ($user) {
            $q->where('client_id', $user->id);
        });
    }

    // Only show a chauffeur's invoices
    if ($user->hasRole('chauffeur')) {
        $query->whereHas('reservation.carDriver', function ($q) use ($user) {
            $q->where('chauffeur_id', $user->id);
        });
    }

    // Get invoices with pagination
    $invoices = $query->orderBy('invoice_date', 'desc')->paginate(10);

    // Calculate total, paid, and unpaid amounts
    $statsQuery = Invoice::query();

    if ($user->hasRole('client')) {
        $statsQuery->whereHas('reservation', function ($q) use ($user) {
            $q->where('client_id', $user->id);
        });
    }

    if ($user->hasRole('chauffeur')) {
        $statsQuery->whereHas('reservation.carDriver', function ($q) use ($user) {
            $q->where('chauffeur_id', $user->id);
        });
    }

    $stats = [
        'total' => $statsQuery->count(),
        'paid' => $statsQuery->where('status', 'payée')->count(),
        'pending' => $statsQuery->where('status', 'en_attente')->count(),
        'overdue' => $statsQuery->where('status', 'offert')->count(),
        'total_amount' => $statsQuery->sum('amount'),
        'paid_amount' => $statsQuery->where('status', 'payée')->sum('amount'),
        'unpaid_amount' => $statsQuery->where('status', 'en_attente')->sum('amount'),
    ];

    return view('invoices.index', compact('invoices', 'stats'));
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
     * Télécharger la facture en PDF
     */
    public function downloadPdf(Invoice $invoice)
    {
        $user = Auth::user();

        // Vérifier les permissions
        // if ($user->hasRole('client') && $invoice->reservation->client_id != $user->id) {
        //     abort(403, 'Vous n\'êtes pas autorisé à télécharger cette facture.');
        // }

        $invoice->load(['reservation.client', 'reservation.trip', 'reservation.chauffeur']);

        $pdf = \PDF::loadView('invoices.pdf', compact('invoice'));

        return $pdf->download('facture-' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Marquer une facture comme payée
     */
    public function markAsPaid(Invoice $invoice)
    {
        // Vérifier que l'utilisateur a les droits pour modifier les factures
        // if (!Auth::user()->hasPermissionTo('manage invoices')) {
        //     abort(403, 'Vous n\'êtes pas autorisé à effectuer cette action.');
        // }

        $invoice->update([
            'status' => 'payée'
        ]);

        return redirect()->back()->with('success', 'La facture a été marquée comme payée.');
    }
}
