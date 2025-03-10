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
            'user_type' => ['required', 'in:client,entreprise'],
            'first_name' => ['required_if:user_type,client', 'string', 'max:255'],
            'last_name' => ['required_if:user_type,client', 'string', 'max:255'],
            'name' => ['required_if:user_type,entreprise', 'string', 'max:255'],
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
    
        // Déterminer le rôle
        $role = $request->user_type === 'entreprise' ? 'entreprise' : 'client';
    
        // Création de l'utilisateur
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->user_type === 'entreprise' ? $request->name : null,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_photo' => $profilePhotoPath,
        ]);
     
        // Assigner un rôle avec Spatie
        $user->assignRole($role);
    
        event(new Registered($user));
    
        Auth::login($user);
    
        return redirect()->route('dashboard');
    }
}
