<?php declare(strict_types=1); 

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class AssignDaysOff extends Command
{
    protected $signature = 'days:assign';
    protected $description = 'Assigne un jour de repos aux chauffeurs chaque lundi.';

    public function handle()
    {
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        // Récupérer tous les chauffeurs
        $chauffeurs = User::role('chauffeur')->get();

        foreach ($chauffeurs as $chauffeur) {
            // Récupérer le jour de repos de la semaine précédente
            $previousDayOff = $chauffeur->day_off;

            // Trouver les jours disponibles (différents de celui de la semaine passée)
            $availableDays = array_diff($days, [$previousDayOff]);

            // Choisir un jour au hasard parmi les jours disponibles
            if (!empty($availableDays)) {
                $randomDay = $availableDays[array_rand($availableDays)];

                // Mettre à jour le jour de repos du chauffeur
                $chauffeur->update([
                    'day_off' => $randomDay,
                    'day_off_assigned_at' => Carbon::now(),
                ]);
            }
        }

        $this->info('Jours de repos assignés avec succès.');
    }
}
