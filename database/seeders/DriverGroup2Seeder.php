<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\DriverGroup;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DriverGroup2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer le rôle chauffeur s'il n'existe pas
        $driverRole = Role::firstOrCreate(['name' => 'chauffeur']);

        $drivers = [
            [
                'first_name' => 'Ibrahim',
                'last_name' => 'Cissé',
                'email' => 'ibrahim.cisse@cpro.test',
                'phone' => '775678901',
            ],
            [
                'first_name' => 'Mariama',
                'last_name' => 'Camara',
                'email' => 'mariama.camara@cpro.test',
                'phone' => '776789012',
            ],
            [
                'first_name' => 'Abdou',
                'last_name' => 'Diop',
                'email' => 'abdou.diop@cpro.test',
                'phone' => '777890123',
            ],
            [
                'first_name' => 'Aminata',
                'last_name' => 'Fall',
                'email' => 'aminata.fall@cpro.test',
                'phone' => '778901234',
            ],
        ];

        $createdDrivers = [];

        foreach ($drivers as $driverData) {
            $user = User::create([
                'first_name' => $driverData['first_name'],
                'last_name' => $driverData['last_name'],
                'email' => $driverData['email'],
                'phone_number' => $driverData['phone'],
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]);

            // Assigner le rôle chauffeur
            $user->assignRole($driverRole);
            $createdDrivers[] = $user;

            $this->command->info("Chauffeur créé : {$driverData['first_name']} {$driverData['last_name']}");
        }

        // Créer un deuxième groupe avec les 4 nouveaux chauffeurs
        if (count($createdDrivers) >= 4) {
            $group = DriverGroup::create([
                'group_name' => 'Groupe Test B',
                'driver_1_id' => $createdDrivers[0]->id,
                'driver_2_id' => $createdDrivers[1]->id,
                'driver_3_id' => $createdDrivers[2]->id,
                'driver_4_id' => $createdDrivers[3]->id,
                'current_rotation_day' => 2, // Commencer à un jour différent pour tester
                'is_active' => true,
            ]);

            $this->command->info("Groupe créé : {$group->group_name}");
        }

        $this->command->info('4 nouveaux chauffeurs et 1 nouveau groupe de test ont été créés avec succès !');
        $this->command->info('Mot de passe par défaut pour tous les chauffeurs : password123');
    }
} 