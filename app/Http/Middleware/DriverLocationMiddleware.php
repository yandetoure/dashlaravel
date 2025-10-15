<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DriverLocationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Vérifier si l'utilisateur est un chauffeur
        if (Auth::check() && Auth::user()->hasRole('chauffeur')) {
            $user = Auth::user();
            
            // Vérifier si la position doit être mise à jour
            $shouldUpdateLocation = $this->shouldUpdateLocation($user);
            
            if ($shouldUpdateLocation) {
                // Inclure le script de géolocalisation dans la réponse
                $this->injectLocationScript($response);
            }
        }

        return $response;
    }

    /**
     * Détermine si la position doit être mise à jour
     */
    private function shouldUpdateLocation($user): bool
    {
        // Mettre à jour si :
        // 1. Aucune position n'est enregistrée
        // 2. La dernière mise à jour date de plus de 5 minutes
        // 3. L'utilisateur vient de se connecter (session récente)
        
        if (!$user->current_lat || !$user->current_lng) {
            return true;
        }
        
        if (!$user->location_updated_at) {
            return true;
        }
        
        // Si la dernière mise à jour date de plus de 5 minutes
        if ($user->location_updated_at->diffInMinutes(now()) > 5) {
            return true;
        }
        
        return false;
    }

    /**
     * Injecte le script de géolocalisation dans la réponse
     */
    private function injectLocationScript($response)
    {
        $script = '
        <script>
        (function() {
            console.log("🚗 Initialisation de la géolocalisation pour le chauffeur");
            
            function updateDriverLocation(position) {
                fetch("/driver/update-location", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(\'meta[name="csrf-token"]\').getAttribute("content")
                    },
                    body: JSON.stringify({
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log("📍 Position mise à jour:", position.coords.latitude, position.coords.longitude);
                    }
                })
                .catch(error => {
                    console.error("❌ Erreur mise à jour position:", error);
                });
            }
            
            function startLocationTracking() {
                if (navigator.geolocation) {
                    // Récupérer la position immédiatement
                    navigator.geolocation.getCurrentPosition(
                        updateDriverLocation,
                        function(error) {
                            console.error("❌ Erreur géolocalisation:", error);
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        }
                    );
                    
                    // Surveiller les changements de position toutes les 5 secondes
                    navigator.geolocation.watchPosition(
                        updateDriverLocation,
                        function(error) {
                            console.error("❌ Erreur surveillance position:", error);
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 5000 // Mise à jour toutes les 5 secondes
                        }
                    );
                    
                    console.log("✅ Géolocalisation activée - mise à jour toutes les 5 secondes");
                } else {
                    console.error("❌ Géolocalisation non supportée par ce navigateur");
                }
            }
            
            // Démarrer le suivi de position
            if (document.readyState === "loading") {
                document.addEventListener("DOMContentLoaded", startLocationTracking);
            } else {
                startLocationTracking();
            }
        })();
        </script>';

        $content = $response->getContent();
        $content = str_replace('</body>', $script . '</body>', $content);
        $response->setContent($content);
    }
}