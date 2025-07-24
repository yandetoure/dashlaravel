<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\DriverGroup;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DriverSeeder extends Seeder
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
                'first_name' => 'Mamadou',
                'last_name' => 'Diallo',
                'email' => 'mamadou.diallo@cpro.test',
                'phone' => '771234567',
            ],
            [
                'first_name' => 'Fatou',
                'last_name' => 'Sow',
                'email' => 'fatou.sow@cpro.test',
                'phone' => '772345678',
            ],
            [
                'first_name' => 'Ousmane',
                'last_name' => 'Ndiaye',
                'email' => 'ousmane.ndiaye@cpro.test',
                'phone' => '773456789',
            ],
            [
                'first_name' => 'Aissatou',
                'last_name' => 'Ba',
                'email' => 'aissatou.ba@cpro.test',
                'phone' => '774567890',
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

        // Créer un groupe de test avec les 4 chauffeurs
        if (count($createdDrivers) >= 4) {
            $group = DriverGroup::create([
                'group_name' => 'Groupe Test A',
                'driver_1_id' => $createdDrivers[0]->id,
                'driver_2_id' => $createdDrivers[1]->id,
                'driver_3_id' => $createdDrivers[2]->id,
                'driver_4_id' => $createdDrivers[3]->id,
                'current_rotation_day' => 0,
                'is_active' => true,
            ]);

            $this->command->info("Groupe créé : {$group->group_name}");
        }

        $this->command->info('4 chauffeurs et 1 groupe de test ont été créés avec succès !');
        $this->command->info('Mot de passe par défaut pour tous les chauffeurs : password123');
    }
}
