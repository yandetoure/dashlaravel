<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Invoice;

class DashboardClientController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Vérifiez que l'utilisateur est connecté
        if (!$user) {
            return redirect()->route('login');
        }

        // Nombre total de réservations
        $totalReservations = Reservation::where('client_id', $user->id)->count();

        // Nombre de réservations cette année
        $yearReservations = Reservation::where('client_id', $user->id)
            ->whereYear('date', date('Y'))
            ->count();

        // Nombre de réservations ce mois-ci
        $monthReservations = Reservation::where('client_id', $user->id)
            ->whereMonth('date', date('m'))
            ->whereYear('date', date('Y'))
            ->count();

        // Nombre de réservations aujourd'hui
        $todayReservations = Reservation::where('client_id', $user->id)
            ->whereDate('date', date('Y-m-d'))
            ->count();

        // Factures impayées
        $unpaidInvoices = Invoice::whereHas('reservation', function ($q) use ($user) {
            $q->where('client_id', $user->id);
        })->where('status', 'en_attente')->get();
        $unpaidTotal = $unpaidInvoices->sum('amount');

        // Points fidélité et statut
        $points = $user->points ?? 0;
        $loyalty_points = $user->loyalty_points ?? 0;
        if ($points < 100) {
            $status = 'Client Standard';
        } elseif ($points <= 300) {
            $status = 'Client Fidèle';
        } else {
            $status = 'Client VIP';
        }

        // Réservations récentes (4 dernières)
        $recentReservations = Reservation::with(['trip', 'carDriver.chauffeur', 'carDriver.car'])
            ->where('client_id', $user->id)
            ->orderByDesc('date')
            ->orderByDesc('heure_ramassage')
            ->limit(4)
            ->get();

        // Prochaine réservation
        $nextReservation = Reservation::where('client_id', $user->id)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('heure_ramassage')
            ->first();

        // Données pour le graphique (réservations par mois)
        $monthlyData = Reservation::selectRaw('MONTH(date) as month, COUNT(*) as count')
            ->where('client_id', $user->id)
            ->whereYear('date', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Générer un tableau de 12 mois
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyData[$i] ?? 0;
        }

        // Passer les données à la vue
        return view('dashboard.client', compact(
            'user',
            'totalReservations',
            'yearReservations',
            'monthReservations',
            'todayReservations',
            'unpaidInvoices',
            'unpaidTotal',
            'points',
            'loyalty_points',
            'status',
            'recentReservations',
            'nextReservation',
            'chartData'
        ));
    }
}
