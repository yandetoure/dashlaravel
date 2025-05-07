<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;

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

        // Passer les données à la vue
        return view('dashboard.client', compact(
            'user',
            'totalReservations',
            'yearReservations',
            'monthReservations',
            'todayReservations'
        ));
    }
}
