<?php declare(strict_types=1);
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Facture;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
    
        // Réservations
        $todayReservations = Reservation::whereDate('date', now())->where('client_id', $user->id)->count();
        $monthlyReservations = Reservation::whereMonth('date', now()->month)->where('client_id', $user->id)->count();
        $yearlyReservations = Reservation::whereYear('date', now()->year)->where('client_id', $user->id)->count();
    
        // // Factures impayées
        // $unpaidInvoices = Facture::where('client_id', $user->id)->where('status', 'impayé')->get();
        // $totalUnpaid = $unpaidInvoices->sum('montant');
    
        // Points fidélité
        $points = $user->points ?? 0;
        $loyaltyPoints = $user->loyalty_points ?? 0;
    
        // Prochaine réservation
        $nextReservation = Reservation::where('client_id', $user->id)
            ->whereDate('date', '>=', now())
            ->orderBy('date')
            ->first();
    
        // Debugging : Afficher les données
        dd($user, $points, $loyaltyPoints, $todayReservations, $monthlyReservations, $yearlyReservations);
    
        return view('client.dashboard', compact(
            'user',
            'todayReservations',
            'monthlyReservations',
            'yearlyReservations',
            'unpaidInvoices',
            'totalUnpaid',
            'points',
            'loyaltyPoints',
            'nextReservation'
        ));
    }
    
}
