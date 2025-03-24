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
     
    }   
}
