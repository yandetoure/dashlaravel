<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DriverGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Mail\AccountCreatedMail;
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
            // Récupérer tous les utilisateurs ayant le rôle "chauffeur" avec pagination
            $drivers = User::role('chauffeur')->paginate(10);

            // Récupérer tous les groupes de chauffeurs actifs
            $driverGroups = DriverGroup::where('is_active', true)->get();
            
            // Calculer la disponibilité pour chaque chauffeur basée sur les groupes
            $today = Carbon::now();
            $availableDriverIds = [];
            $restDriverIds = [];
            
            foreach ($driverGroups as $group) {
                $restDays = $group->getRestDaysForDate($today);
                $availableDays = $group->getAvailableDriversForDate($today);
                
                $restDriverIds = array_merge($restDriverIds, $restDays);
                $availableDriverIds = array_merge($availableDriverIds, $availableDays);
            }
            
            // Ajouter les informations de disponibilité à chaque chauffeur
            foreach ($drivers as $driver) {
                if (in_array($driver->id, $restDriverIds)) {
                    $driver->availability_status = 'rest';
                    $driver->availability_text = 'En repos';
                } elseif (in_array($driver->id, $availableDriverIds)) {
                    $driver->availability_status = 'available';
                    $driver->availability_text = 'Disponible';
                } else {
                    $driver->availability_status = 'unknown';
                    $driver->availability_text = 'Non assigné';
                }
            }

            // Retourner la vue avec les chauffeurs
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
 * Afficher les détails d'un utilisateur
 */
public function showUser(User $user)
{
    return view('users.show', compact('user'));
}

/**
 * Générer un mot de passe temporaire pour un utilisateur
 */
public function generateTempPassword(User $user)
{
    // Vérifier que l'utilisateur actuel est un admin ou superadmin
    $currentUser = Auth::user();
    if (!$currentUser->hasAnyRole(['admin', 'superadmin'])) {
        return response()->json(['success' => false, 'message' => 'Non autorisé'], 403);
    }

    // Générer un mot de passe temporaire sécurisé
    $tempPassword = $this->generateSecurePassword();
    
    // Mettre à jour le mot de passe de l'utilisateur
    $user->password = bcrypt($tempPassword);
    $user->save();

    return response()->json([
        'success' => true,
        'temp_password' => $tempPassword,
        'message' => 'Mot de passe temporaire généré avec succès'
    ]);
}

/**
 * Réinitialiser le mot de passe d'un utilisateur
 */
public function resetPassword(User $user)
{
    // Vérifier que l'utilisateur actuel est un admin ou superadmin
    $currentUser = Auth::user();
    if (!$currentUser->hasAnyRole(['admin', 'superadmin'])) {
        return response()->json(['success' => false, 'message' => 'Non autorisé'], 403);
    }

    try {
        // Générer un token de réinitialisation
        $token = app('auth.password.broker')->createToken($user);
        
        // Envoyer l'email de réinitialisation
        $user->sendPasswordResetNotification($token);
        
        return response()->json([
            'success' => true,
            'message' => 'Email de réinitialisation envoyé avec succès'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Générer un mot de passe sécurisé
 */
private function generateSecurePassword($length = 12)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
    $password = '';
    
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[random_int(0, strlen($characters) - 1)];
    }
    
    return $password;
}

/**
 * Afficher le formulaire de modification d'un utilisateur
 */
public function editUser(User $user)
{
    $roles = Role::all();
    return view('users.edit', compact('user', 'roles'));
}

/**
 * Mettre à jour un utilisateur
 */
public function updateUser(Request $request, User $user)
{
    $request->validate([
        'first_name' => ['required', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'address' => ['nullable', 'string', 'max:255'],
        'phone_number' => ['required', 'regex:/^[0-9]{9}$/', 'unique:users,phone_number,' . $user->id],
        'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'role' => ['required', 'exists:roles,name'],
        'profile_photo' => ['nullable', 'image', 'max:2048'],
    ]);

    $data = $request->only(['first_name', 'last_name', 'address', 'phone_number', 'email']);

    // Gestion de la photo de profil
    if ($request->hasFile('profile_photo')) {
        $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
    }

    $user->update($data);

    // Mise à jour du rôle
    $user->syncRoles([$request->role]);

    return redirect()->route('users.show', $user)->with('success', 'Utilisateur mis à jour avec succès.');
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
