<?php declare(strict_types=1); 

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginAPIController extends Controller
{
    /**
     * Connexion d'un utilisateur.
     */
    public function login(Request $request)
    {
        try {
            // Validation des données
            $validator = validator($request->all(), [
                'email' => 'required|email|string',
                'password' => 'required|string|min:8',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
    
            $credentials = $request->only('email', 'password');
            // $token = auth()->attempt($credentials);
    
            // if (!$token) {
            //     return response()->json(['message' => 'Information de connexion incorrectes'], 401);
            // }


            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password,  $user->password)) {
                throw ValidationEception::withMessages(
                    [
                        'email' => ['Le mot de passe est incorrect'],
                    ]
                    );
            }

            $access_token = $user->createToken($user->id)->plainTextToken;

            

            // Récupération de l'utilisateur authentifié
            //$user = User::where('email', $request->email)->first();

            // Récupérer les rôles de l'utilisateur
            $roles = $user->getRoleNames(); // Méthode fournie par Spatie
            

            return response()->json([

                "access_token" => $access_token,
                "token_type" => "bearer",
                "user" => $user,   
                "user_id" => $user->id,
                "role" => $roles,
                "expires_in" => env("JWT_TTL") * 60 . ' seconds'
                
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la connexion',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
