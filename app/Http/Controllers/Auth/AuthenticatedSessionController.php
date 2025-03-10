<?php declare(strict_types=1);
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authentifier l'utilisateur
        $request->authenticate();

        // Régénérer la session
        $request->session()->regenerate();

        // Récupérer l'utilisateur authentifié
        $user = Auth::user();

             // Vérifier les rôles de l'utilisateur et rediriger
             if ($user->hasRole('admin')) {
                return redirect()->route('dashboard.admin');
            } elseif ($user->hasRole('client')) {
                return redirect()->route('dashboard.client');
            } elseif ($user->hasRole('entreprise')) {
                return redirect()->route('dashboard.entreprise');
            } elseif ($user->hasRole('chauffeur')) {
                return redirect()->route('dashboard.chauffeur');
            } elseif ($user->hasRole('agent')) {
                return redirect()->route('dashboard.agent');
            } elseif ($user->hasRole('super-admin')) {
                return redirect()->route('dashboard.superadmin');
            } else {
                return redirect()->route('dashboard'); // Redirection par défaut
            }
        }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
