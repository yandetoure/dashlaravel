<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Mail\AccountCreatedMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Création d'un compte par un admin ou super admin
     */
    public function createAccountPage()
    {
        // Vérifie si l'utilisateur a le rôle requis
        // if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('superadmin')) {
        //     abort(403, 'Accès interdit. Vous devez être administrateur pour créer un compte.');
        // }
    
        return view('admins.add-user');  // Vérifie que la vue existe
    }
    

    public function createAccount(Request $request)
    {
        // Vérifier si l'utilisateur authentifié est admin ou super admin
        // if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('super-admin')) {
        //     abort(403, 'Accès interdit. Vous devez être administrateur pour créer un compte.');
        // }

        // Validation des données du formulaire
        $request->validate([
            'role' => ['required', 'in:admin,client,chauffeur,garagiste,agent,super-admin'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['required', 'regex:/^[0-9]{9}$/', 'unique:users,phone_number'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);
          // Gestion de l'image
          $profilePhotoPath = $request->hasFile('profile_photo')
          ? $request->file('profile_photo')->store('profile_photos', 'public')
          : null;

        // Mot de passe par défaut
        $password = 'password123';

        // Création de l'utilisateur
        $user = User::create([
            'first_name' => $request->first_name ?? null,
            'last_name' => $request->last_name ?? null,
            'name' => $request->name ?? null,
            'address' => $request->address ?? null,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => Hash::make($password),
            'profile_photo' => $profilePhotoPath,
        ]);

        // Attribution du rôle
        $user->assignRole($request->role);

        // Envoi d'un e-mail contenant le mot de passe
        Mail::to($user->email)->send(new AccountCreatedMail($user, $password));

        // Message flash et redirection vers la même page avec succès
        return back()->with('success', 'L\'utilisateur a été créé avec succès et un mot de passe lui a été envoyé.');
    }

        // Afficher les clients
        public function lisclient()
        {
            // Récupérer tous les utilisateurs ayant le rôle "client"
            $clients = User::role('client')->paginate(10); // Pagination de 10 clients par page
    
            // Retourner la vue avec les clients
            return view('clients.index', compact('clients'));
        }

        public function listdriver()
        {
            // Récupérer tous les utilisateurs ayant le rôle "chauffeur"
            $drivers = User::role('chauffeur')->paginate(10); // Pagination de 10 chauffeur par page
    
            // Retourner la vue avec les clients
            return view('drivers.index', compact('drivers'));
        }

        public function listagent()
        {
            // Récupérer tous les utilisateurs ayant le rôle "agent"
            $agents = User::role('agent')->paginate(10); // Pagination de 10 agent par page
    
            // Retourner la vue avec les clients
            return view('agents.index', compact('agents'));
        }

        public function listadmin()
        {
            // Récupérer tous les utilisateurs ayant le rôle "agent"
            $admins = User::role('admin')->paginate(10); // Pagination de 10 agent par page
    
            // Retourner la vue avec les clients
            return view('admins.index', compact('admins'));
        }

        public function listsuperadmin()
        {
            // Récupérer tous les utilisateurs ayant le rôle "agent"
            $superadmins = User::role('super-admin')->paginate(10); // Pagination de 10 agent par page
    
            // Retourner la vue avec les clients
            return view('superadmins.index', compact('superadmins'));
        }
        

        public function createDayOff()
        {
            // Vérifie si l'utilisateur a le rôle requis
            if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('super-admin')) {
                abort(403, 'Accès interdit. Vous devez être administrateur pour créer un compte.');
            }
        
            return view('admins.assign-day-off');  // Vérifie que la vue existe
        }
        
        public function assignRandomDayOff(Request $request)
        {
            // Jours disponibles
            $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        
            // Récupérer les chauffeurs sans jour de repos assigné ou avec un jour de repos non assigné récemment
            $chauffeurs = User::role('chauffeur')
                              ->whereNull('day_off')
                              ->orWhereNull('day_off_assigned_at')
                              ->get();
        
            foreach ($chauffeurs as $chauffeur) {
                // Récupérer les jours de repos déjà assignés à d'autres chauffeurs (ou ceux assignés récemment)
                $occupiedDays = User::role('chauffeur')
                                    ->whereNotNull('day_off') // Jours de repos assignés
                                    ->where('day_off_assigned_at', '>=', Carbon::now()->subDay()) // Jours assignés dans les dernières 24h
                                    ->pluck('day_off')
                                    ->toArray();
        
                // Trouver les jours non occupés
                $availableDays = array_diff($days, $occupiedDays);
        
                // Si des jours sont disponibles
                if (!empty($availableDays)) {
                    // Choisir un jour au hasard
                    $randomDay = $availableDays[array_rand($availableDays)];
        
                    // Mettre à jour le jour de repos du chauffeur
                    $chauffeur->update([
                        'day_off' => $randomDay,
                        'day_off_assigned_at' => Carbon::now(), // Date de l'assignation
                    ]);
                }
            }
        
            return redirect()->route('drivers.index')->with('success', 'Jours de repos assignés avec succès.');
        }
        

    public function createAgent()
{
    return view('agents.register_agent');
}

public function createDriver()
{
    return view('drivers.register_driver');
}

public function createAdmin()
{
    return view('admins.register_admin');
}

public function createClient()
{
    return view('clients.register_client');
}


public function storeAgent(Request $request)
{
    return $this->registerUser($request, 'agent');
}

public function storeDriver(Request $request)
{
    return $this->registerUser($request, 'driver');
}

public function storeAdmin(Request $request)
{
    return $this->registerUser($request, 'admin');
}

public function storeClient(Request $request)
{
    return $this->registerUser($request, 'client');
}

/**
 * Fonction générique pour créer un utilisateur selon son rôle.
 */
private function registerUser(Request $request, string $role)
{
    $request->validate([
        'first_name' => ['required', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'address' => ['required', 'string', 'max:255'],
        'phone_number' => ['required', 'regex:/^[0-9]{9}$/', 'unique:users,phone_number'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        'profile_photo' => ['nullable', 'image', 'max:2048'],
    ]);

    $profilePhotoPath = $request->hasFile('profile_photo')
        ? $request->file('profile_photo')->store('profile_photos', 'public')
        : null;

    $password = 'password123';

    $user = User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'address' => $request->address,
        'phone_number' => $request->phone_number,
        'email' => $request->email,
        'password' => Hash::make($password),
        'profile_photo' => $profilePhotoPath,
    ]);

    $user->assignRole($role);

    // Envoi d'un e-mail contenant le mot de passe
    Mail::to($user->email)->send(new AccountCreatedMail($user, $password));

    return back()->with('success', 'L\'utilisateur a été créé avec succès et un mot de passe lui a été envoyé.');
}

// Affiche le formulaire d'édition
public function edit(User $user)
{
    return view('profile.edit', compact('user'));
}

public function update(Request $request)
{
    $user = $request->user();

    $request->validate([
        'first_name' => ['required', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'address' => ['nullable', 'string', 'max:255'],
        'phone_number' => ['required', 'regex:/^[0-9]{9}$/', 'unique:users,phone_number,' . $user->id],
        'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'profile_photo' => ['nullable', 'image', 'max:2048'],
    ]);

    $data = $request->only(['first_name', 'last_name', 'address', 'phone_number', 'email']);

    // Gestion de la photo de profil
    if ($request->hasFile('profile_photo')) {
        $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
    }

    $user->update($data);

    return back()->with('status', 'Profil mis à jour avec succès.');
}


public function getUserStatistics()
{
    // Exemple pour obtenir les statistiques par rôle
    $roles = ['admin', 'client', 'chauffeur', 'agent']; // Liste des rôles
    $statistics = [];

    foreach ($roles as $role) {
        $statistics[$role] = User::role($role)->count();  // Supposons que tu utilises Spatie pour gérer les rôles
    }

    return $statistics;
}


public function showAdminDashboard()
{
    $statistics = $this->getUserStatistics();

    // dd($statistics); // Décommenter uniquement pour debug

    return view('dashboards.admin', compact('statistics'));
}

/**
 * Affiche tous les utilisateurs avec possibilité de filtrer par rôle
 * 
 * @param Request $request
 * @return \Illuminate\View\View
 */
public function listAllUsers(Request $request)
{
    // Récupérer le filtre de rôle s'il existe
    $roleFilter = $request->query('role');
    
    // Liste de tous les rôles disponibles
    $roles = Role::all()->pluck('name');
    
    // Requête de base
    $query = User::query();
    
    // Appliquer le filtre de rôle si spécifié
    if ($roleFilter && $roleFilter !== 'all') {
        $query->role($roleFilter);
    }
    
    // Récupérer les utilisateurs avec pagination
    $users = $query->paginate(10);
    
    // Retourner la vue avec les utilisateurs et les rôles
    return view('superadmins.index', compact('users', 'roles', 'roleFilter'));
}

/**
 * Supprimer un utilisateur par son ID.
 */
public function destroy($id)
{
    $user = User::findOrFail($id);

    // Optionnel : empêcher la suppression de soi-même ou d'un super-admin
    // if (auth()->id() === $user->id) {
    //     return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
    // }

    $user->delete();

    return back()->with('success', 'Utilisateur supprimé avec succès.');
}

}
