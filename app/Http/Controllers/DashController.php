<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;

class DashController extends Controller
{
    // Dashboard pour l'Admin
    public function adminIndex()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403); // Accès interdit si l'utilisateur n'est pas un admin
        }

        $reservationsCount = Reservation::count(); // Nombre total des réservations
        $carsCount = Car::count(); // Nombre total de voitures
        $usersCount = User::count(); // Nombre total d'utilisateurs

        return view('dashboards.admin', compact('reservationsCount', 'carsCount', 'usersCount'));
    }

    // Dashboard pour le Client
    public function clientIndex()
    {
        if (!auth()->user()->hasRole('client')) {
            abort(403); // Accès interdit si l'utilisateur n'est pas un client
        }

        $reservationsCount = Reservation::where('client_id', auth()->id())->count(); // Réservations du client connecté
        return view('dashboards.client', compact('reservationsCount'));
    }


    // Dashboard pour le Chauffeur
        public function chauffeurIndex()
    {
        // Vérifie que l'utilisateur connecté est bien chauffeur
        if (!auth()->user()->hasRole('chauffeur')) {
            abort(403); // Accès interdit
        }

        // Compte les réservations associées au chauffeur connecté via Cardriver
        $reservationsCount = Reservation::whereHas('carDriver', function ($query) {
            $query->where('chauffeur_id', auth()->id());
        })->count();

        return view('dashboards.driver', compact('reservationsCount'));
    }



    // Dashboard pour l'Entreprise
    public function entrepriseIndex()
    {
        if (!auth()->user()->hasRole('entreprise')) {
            abort(403); // Accès interdit si l'utilisateur n'est pas une entreprise
        }

        $reservationsCount = Reservation::where('entreprise_id', auth()->id())->count(); // Réservations de l'entreprise
        return view('dashboards.entreprise', compact('reservationsCount'));
    }

    // Dashboard pour l'Agent
    public function agentIndex()
    {
        if (!auth()->user()->hasRole('agent')) {
            abort(403); // Accès interdit si l'utilisateur n'est pas un agent
        }

        $reservationsCount = Reservation::where('agent_id', auth()->id())->count(); // Réservations attribuées à l'agent connecté
        return view('dashboards.agent', compact('reservationsCount'));
    }

    // Dashboard pour le Superadmin
    public function superadminIndex()
    {
        if (!auth()->user()->hasRole('super-admin')) {
            abort(403); // Accès interdit si l'utilisateur n'est pas un superadmin
        }

        $reservationsCount = Reservation::count(); // Nombre total des réservations
        $usersCount = User::count(); // Nombre total d'utilisateurs
        $carsCount = Car::count(); // Nombre total de voitures

        return view('dashboards.superadmin', compact('reservationsCount', 'usersCount', 'carsCount'));
    }
}
