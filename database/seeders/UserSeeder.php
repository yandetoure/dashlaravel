<?php declare(strict_types=1); 

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        $user1 = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password1234'), 
            'address' => 'Dieuppeul 1',
            'phone_number' => '+221777908197',
        ]);
        $user1->assignRole('super-admin'); 

        $user2 = User::create([
            'first_name' => 'New',
            'last_name' => 'Agent',
            'email' => 'agent@gmail.com',
            'password' => Hash::make('password1234'), 
            'address' => 'Dieuppeul 1',
            'phone_number' => '+221777956197',
        ]);
        $user2->assignRole('agent'); 

        $user2 = User::create([
            'first_name' => 'Client',
            'last_name' => 'Agent',
            'email' => 'client@gmail.com',
            'password' => Hash::make('password1234'), 
            'address' => 'Dieuppeul 1',
            'phone_number' => '+221771908197',
        ]);
        $user2->assignRole('client'); 

        $user2 = User::create([
            'first_name' => 'New',
            'last_name' => 'Chauffeur',
            'email' => 'driver@gmail.com',
            'password' => Hash::make('password1234'), 
            'address' => 'Dieuppeul 1',
            'phone_number' => '+221777355197',
        ]);
        $user2->assignRole('chauffeur'); 


        $user2 = User::create([
            'first_name' => 'Albert',
            'last_name' => 'Chauffeur',
            'email' => 'albet@gmail.com',
            'password' => Hash::make('password1234'), 
            'address' => 'Dieuppeul 1',
            'phone_number' => '+221777075197',
        ]);
        $user2->assignRole('chauffeur'); 


        $user2 = User::create([
            'first_name' => 'New',
            'last_name' => 'Chauffeur',
            'email' => 'drir@gmail.com',
            'password' => Hash::make('password1234'), 
            'address' => 'Dieuppeul 1',
            'phone_number' => '+221777900197',
        ]);
        $user2->assignRole('chauffeur'); 



        $user2 = User::create([
            'first_name' => 'New',
            'last_name' => 'Chauffeur',
            'email' => 'dri@gmail.com',
            'password' => Hash::make('password1234'), 
            'address' => 'Dieuppeul 1',
            'phone_number' => '+221777955197',
        ]);
        $user2->assignRole('chauffeur'); 


        $user2 = User::create([
            'first_name' => 'Cheih',
            'last_name' => 'Chauffeur',
            'email' => 'cheikhr@gmail.com',
            'password' => Hash::make('password1234'), 
            'address' => 'Dieuppeul 1',
            'phone_number' => '+221776955197',
        ]);
        $user2->assignRole('chauffeur'); 
    }   
}
