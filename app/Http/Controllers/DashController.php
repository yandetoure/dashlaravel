<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Car;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Maintenance;
use App\Models\Trip;
use App\Models\Actu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashController extends Controller
{
    // Dashboard pour le Super Admin
    public function superadminIndex()
    {
        if (!auth()->user()->hasRole('super-admin')) {
            abort(403);
        }

        // Statistiques générales
        $stats = [
            'total_reservations' => Reservation::count(),
            'total_users' => User::count(),
            'total_cars' => Car::count(),
            'total_revenue' => (float) (Invoice::where('status', 'payé')->sum('amount') ?? 0),
            'pending_reservations' => Reservation::where('status', 'En_attente')->count(),
            'confirmed_reservations' => Reservation::where('status', 'Confirmée')->count(),
            'cancelled_reservations' => Reservation::where('status', 'Annulée')->count(),
            'unpaid_invoices' => Invoice::where('status', 'en_attente')->count(),
            'total_clients' => User::role('client')->count(),
            'total_drivers' => User::role('chauffeur')->count(),
            'total_agents' => User::role('agent')->count(),
            'total_admins' => User::role('admin')->count(),
        ];

        // Réservations récentes
        $recent_reservations = Reservation::with(['client', 'trip', 'carDriver.chauffeur'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Revenus mensuels (12 derniers mois)
        $monthly_revenue = Invoice::selectRaw('MONTH(invoice_date) as month, YEAR(invoice_date) as year, SUM(amount) as total')
            ->where('status', 'payé')
            ->where('invoice_date', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Réservations par mois (12 derniers mois)
        $monthly_reservations = Reservation::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Top chauffeurs (par nombre de réservations)
        $top_drivers = User::role('chauffeur')
            ->withCount(['car_drivers as reservations_count' => function($query) {
                $query->join('reservations', 'car_drivers.id', '=', 'reservations.cardriver_id')
                      ->where('reservations.status', 'Confirmée');
            }])
            ->orderBy('reservations_count', 'desc')
            ->limit(5)
            ->get();

        // Véhicules en maintenance
        $cars_in_maintenance = Maintenance::with('car')
            ->where('jour', '>=', Carbon::today())
            ->where('statut', '!=', 'Terminé')
            ->get();

        return view('dashboards.superadmin', compact(
            'stats', 
            'recent_reservations', 
            'monthly_revenue', 
            'monthly_reservations',
            'top_drivers',
            'cars_in_maintenance'
        ));
    }

    // Dashboard pour l'Admin
    public function adminIndex()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        // Statistiques pour admin (similaires au super admin mais avec moins de détails)
        $stats = [
            'total_reservations' => Reservation::count(),
            'total_cars' => Car::count(),
            'total_revenue' => (float) (Invoice::where('status', 'payé')->sum('amount') ?? 0),
            'pending_reservations' => Reservation::where('status', 'En_attente')->count(),
            'confirmed_reservations' => Reservation::where('status', 'Confirmée')->count(),
            'cancelled_reservations' => Reservation::where('status', 'Annulée')->count(),
            'total_clients' => User::role('client')->count(),
            'total_drivers' => User::role('chauffeur')->count(),
        ];

        // Réservations récentes
        $recent_reservations = Reservation::with(['client', 'trip', 'carDriver.chauffeur'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Réservations aujourd'hui
        $today_reservations = Reservation::with(['client', 'trip', 'carDriver.chauffeur'])
            ->whereDate('date', Carbon::today())
            ->get();

        // Revenus mensuels
        $monthly_revenue = Invoice::selectRaw('MONTH(invoice_date) as month, SUM(amount) as total')
            ->where('status', 'payé')
            ->whereYear('invoice_date', Carbon::now()->year)
            ->groupBy('month')
            ->get();

        return view('dashboards.admin', compact(
            'stats', 
            'recent_reservations', 
            'today_reservations',
            'monthly_revenue'
        ));
    }

    // Dashboard pour le Client
    public function clientIndex()
    {
        if (!auth()->user()->hasRole('client')) {
            abort(403);
        }

        $user = auth()->user();

        // Statistiques du client
        $stats = [
            'total_reservations' => Reservation::where('client_id', $user->id)->count(),
            'confirmed_reservations' => Reservation::where('client_id', $user->id)->where('status', 'Confirmée')->count(),
            'pending_reservations' => Reservation::where('client_id', $user->id)->where('status', 'En_attente')->count(),
            'cancelled_reservations' => Reservation::where('client_id', $user->id)->where('status', 'Annulée')->count(),
            'total_spent' => (float) (Invoice::whereHas('reservation', function($q) use ($user) {
                $q->where('client_id', $user->id);
            })->where('status', 'payé')->sum('amount') ?? 0),
            'unpaid_amount' => (float) (Invoice::whereHas('reservation', function($q) use ($user) {
                $q->where('client_id', $user->id);
            })->where('status', 'en_attente')->sum('amount') ?? 0),
            'loyalty_points' => $user->loyalty_points ?? 0,
            'points' => $user->points ?? 0,
        ];

        // Statut de fidélité
        $loyalty_status = 'Standard';
        if ($stats['points'] >= 300) {
            $loyalty_status = 'VIP';
        } elseif ($stats['points'] >= 100) {
            $loyalty_status = 'Fidèle';
        }

        // Réservations récentes
        $recent_reservations = Reservation::with(['trip', 'carDriver.chauffeur'])
            ->where('client_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Prochaine réservation
        $next_reservation = Reservation::with(['trip', 'carDriver.chauffeur'])
            ->where('client_id', $user->id)
            ->where('date', '>=', Carbon::today())
            ->where('status', 'Confirmée')
            ->orderBy('date')
            ->orderBy('heure_ramassage')
            ->first();

        // Prochaines réservations (toutes les réservations futures confirmées)
        $upcoming_reservations = Reservation::with(['trip', 'carDriver.chauffeur'])
            ->where('client_id', $user->id)
            ->where('date', '>=', Carbon::today())
            ->where('status', 'Confirmée')
            ->orderBy('date')
            ->orderBy('heure_ramassage')
            ->limit(6)
            ->get();

        // Factures impayées
        $unpaid_invoices = Invoice::with('reservation')
            ->whereHas('reservation', function($q) use ($user) {
                $q->where('client_id', $user->id);
            })
            ->where('status', 'en_attente')
            ->get();

        // Réservations par mois (année en cours)
        $monthly_reservations = Reservation::selectRaw('MONTH(date) as month, COUNT(*) as total')
            ->where('client_id', $user->id)
            ->whereYear('date', Carbon::now()->year)
            ->groupBy('month')
            ->get();

        return view('dashboards.client', compact(
            'stats',
            'loyalty_status',
            'recent_reservations',
            'next_reservation',
            'upcoming_reservations',
            'unpaid_invoices',
            'monthly_reservations'
        ));
    }

    // Dashboard pour le Chauffeur
        public function chauffeurIndex()
    {
        if (!auth()->user()->hasRole('chauffeur')) {
            abort(403);
        }

        $user = auth()->user();

        // Récupérer les car_drivers du chauffeur
        $carDrivers = $user->car_drivers;

        // Statistiques du chauffeur
        $stats = [
            'total_reservations' => Reservation::whereIn('cardriver_id', $carDrivers->pluck('id'))->count(),
            'completed_reservations' => Reservation::whereIn('cardriver_id', $carDrivers->pluck('id'))
                ->where('status', 'Confirmée')
                ->where('date', '<', Carbon::today())
                ->count(),
            'upcoming_reservations' => Reservation::whereIn('cardriver_id', $carDrivers->pluck('id'))
                ->where('status', 'Confirmée')
                ->where('date', '>=', Carbon::today())
                ->count(),
            'today_reservations' => Reservation::whereIn('cardriver_id', $carDrivers->pluck('id'))
                ->whereDate('date', Carbon::today())
                ->where('status', 'Confirmée')
                ->count(),
            'total_earnings' => (float) (Invoice::whereHas('reservation', function($q) use ($carDrivers) {
                $q->whereIn('cardriver_id', $carDrivers->pluck('id'));
            })->where('status', 'payé')->sum('amount') ?? 0) * 0.1, // 10% de commission
            'points' => $user->points ?? 0,
        ];

        // Réservations d'aujourd'hui
        $today_reservations = Reservation::with(['client', 'trip'])
            ->whereIn('cardriver_id', $carDrivers->pluck('id'))
            ->whereDate('date', Carbon::today())
            ->where('status', 'Confirmée')
            ->orderBy('heure_ramassage')
            ->get();

        // Prochaines réservations
        $upcoming_reservations = Reservation::with(['client', 'trip'])
            ->whereIn('cardriver_id', $carDrivers->pluck('id'))
            ->where('date', '>', Carbon::today())
            ->where('status', 'Confirmée')
            ->orderBy('date')
            ->orderBy('heure_ramassage')
            ->limit(5)
            ->get();

        // Voiture assignée
        $assigned_car = $carDrivers->first()?->car;

        // Maintenances programmées
        $upcoming_maintenances = [];
        if ($assigned_car) {
            $upcoming_maintenances = Maintenance::where('car_id', $assigned_car->id)
                ->where('jour', '>=', Carbon::today())
                ->where('statut', '!=', 'Terminé')
                ->orderBy('jour')
                ->get();
        }

        return view('dashboards.driver', compact(
            'stats',
            'today_reservations',
            'upcoming_reservations',
            'assigned_car',
            'upcoming_maintenances'
        ));
    }

    // Dashboard pour l'Agent
    public function agentIndex()
    {
        if (!auth()->user()->hasRole('agent')) {
            abort(403);
        }

        $user = auth()->user();

        // Statistiques de l'agent
        $stats = [
            'total_reservations_handled' => Reservation::where('id_agent', $user->id)->count(),
            'pending_reservations' => Reservation::where('status', 'En_attente')->count(),
            'confirmed_today' => Reservation::where('id_agent', $user->id)
                ->where('status', 'Confirmée')
                ->whereDate('updated_at', Carbon::today())
                ->count(),
            'total_clients' => User::role('client')->count(),
            'points' => $user->points ?? 0,
        ];

        // Réservations en attente (à traiter)
        $pending_reservations = Reservation::with(['client', 'trip'])
            ->where('status', 'En_attente')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Réservations traitées récemment par l'agent
        $recent_handled = Reservation::with(['client', 'trip'])
            ->where('id_agent', $user->id)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Chauffeurs disponibles
        $available_drivers = User::role('chauffeur')
            ->whereDoesntHave('car_drivers.reservations', function($q) {
                $q->whereDate('date', Carbon::today())
                  ->where('status', 'Confirmée');
            })
            ->limit(5)
            ->get();

        return view('dashboards.agent', compact(
            'stats',
            'pending_reservations',
            'recent_handled',
            'available_drivers'
        ));
    }

    // Dashboard pour l'Entreprise
    public function entrepriseIndex()
    {
        if (!auth()->user()->hasRole('entreprise')) {
            abort(403);
        }

        $user = auth()->user();

        // Statistiques de l'entreprise
        $stats = [
            'total_reservations' => Reservation::where('entreprise_id', $user->id)->count(),
            'confirmed_reservations' => Reservation::where('entreprise_id', $user->id)->where('status', 'Confirmée')->count(),
            'pending_reservations' => Reservation::where('entreprise_id', $user->id)->where('status', 'En_attente')->count(),
            'total_spent' => (float) (Invoice::whereHas('reservation', function($q) use ($user) {
                $q->where('entreprise_id', $user->id);
            })->where('status', 'payé')->sum('amount') ?? 0),
            'unpaid_amount' => (float) (Invoice::whereHas('reservation', function($q) use ($user) {
                $q->where('entreprise_id', $user->id);
            })->where('status', 'en_attente')->sum('amount') ?? 0),
        ];

        // Réservations récentes de l'entreprise
        $recent_reservations = Reservation::with(['trip', 'carDriver.chauffeur'])
            ->where('entreprise_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Factures impayées
        $unpaid_invoices = Invoice::with('reservation')
            ->whereHas('reservation', function($q) use ($user) {
                $q->where('entreprise_id', $user->id);
            })
            ->where('status', 'en_attente')
            ->get();

        return view('dashboards.entreprise', compact(
            'stats',
            'recent_reservations',
            'unpaid_invoices'
        ));
    }
}
