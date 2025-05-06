<?php declare(strict_types=1); 

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'first_name' => ['required', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'address' => ['required', 'string', 'max:255'],
        'phone_number' => ['required', 'regex:/^[0-9]{9}$/', 'unique:users,phone_number'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'profile_photo' => ['nullable', 'image', 'max:2048'],
    ]);
      // Gestion de l'image
      $profilePhotoPath = $request->hasFile('profile_photo')
      ? $request->file('profile_photo')->store('profile_photos', 'public')
      : null;

    $user = User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'address' => $request->address,
        'phone_number' => $request->phone_number,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'profile_photo' => $profilePhotoPath,
    ]);

    // Attribution automatique du rôle "client"
    $user->assignRole('client');

    event(new Registered($user));

    Auth::login($user);

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
}
