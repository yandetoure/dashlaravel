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
    // Création d'utilisateurs fictifs
    $user1 = User::create([
        'first_name' => 'Super',
        'last_name' => 'Admin',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('password1234'), 
        'address' => 'Dieuppeul 1',
        'phone_number' => '+221777908197',
    ]);
    $user1->assignRole('super-admin'); 
     
        // Création d'utilisateurs fictifs
        $user2 = User::create([
            'first_name' => 'Yandeh',
            'last_name' => 'Toure',
            'email' => 'yandeh@gmail.com',
            'password' => Hash::make('password1234'), 
            'address' => 'Dieuppeul 1',
            'phone_number' => '+221777908190',
        ]);
        $user2->assignRole('client'); 
         
            // Création d'utilisateurs fictifs
        $user3 = User::create([
            'first_name' => 'Hamady',
            'last_name' => 'Dieng',
            'email' => 'dieng@gmail.com',
            'password' => Hash::make('password1234'), 
            'address' => 'Dieuppeul 1',
            'phone_number' => '+221777908107',
        ]);
        $user3->assignRole('chauffeur'); 
        
        $user4 = User::create([
            'first_name' => 'Bousso',
            'last_name' => 'Dip',
            'email' => 'bousso@gmail.com',
            'password' => Hash::make('password1234'), 
            'address' => 'Dieuppeul 1',
            'phone_number' => '+221777900107',
        ]);
        $user4->assignRole('agent'); 
        
        $user5 = User::create([
            'first_name' => 'Bousso',
            'last_name' => 'Diop',
            'email' => 'diop@gmail.com',
            'password' => Hash::make('password1234'), 
            'address' => 'Dieuppeul 1',
            'phone_number' => '+221777900134',
        ]);
        $user5->assignRole('agent'); 
        
        $user5=  User::create([
            'first_name' => 'Hamdy',
            'last_name' => 'Ka',
            'email' => 'ka@gmail.com',
            'password' => Hash::make('password1234'), 
            'address' => 'Dieuppeul 1',
            'phone_number' => '+221777900111',
        ]);
        $user5->assignRole('garagiste'); 

        $user5=  User::create([
            'name' => 'CMA',
            'email' => 'cpro@gmail.com',
            'password' => Hash::make('password1234'), 
            'address' => 'Dieuppeul 1',
            'phone_number' => '+221338009900',
        ]);
        $user5->assignRole('entreprise'); 
    }   
}
