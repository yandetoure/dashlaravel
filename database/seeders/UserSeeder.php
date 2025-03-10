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
        'first_name' => 'David',
        'last_name' => 'Touré',
        'email' => 'david@gmail.com',
        'password' => Hash::make('password'), 
        'address' => 'Sacré Cœur',
        'phone_number' => '+221774344454',
    ]);
    $user1->assignRole('admin'); 

    $user2 = User::create([
        'first_name' => 'Ndeye Yandé',
        'last_name' => 'Touré',
        'email' => 'toure@gmail.com',
        'password' => Hash::make('password'), 
        'address' => 'Dieuppeul 1',
        'phone_number' => '+221772319878',
    ]);
    $user2->assignRole('super-admin'); 
     

    $user3 = User::create([
        'first_name' => 'Mame Diarra',
        'last_name' => 'Touré',
        'email' => 'bousso@gmail.com',
        'password' => Hash::make('password'), 
        'address' => 'Dieuppeul 1',
        'phone_number' => '+221777498683',
    ]);
    $user3->assignRole('agent'); 

    $user4 = User::create([
        'first_name' => 'Hamady',
        'last_name' => 'Ndiaye',
        'email' => 'hamady@gmail.com',
        'password' => Hash::make('password'), 
        'address' => 'Pikine',
        'phone_number' => '+221774341159',
    ]);
    $user4->assignRole('chauffeur'); 

    $user5 = User::create([
        'name' => 'gpt',
        'email' => 'gpt@gmail.com',
        'password' => Hash::make('password'), 
        'address' => 'Almadies 143',
        'phone_number' => '+221338980009',
    ]);
    $user5->assignRole('entreprise'); 

    $user6 = User::create([
        'first_name' => 'Joseph',
        'last_name' => 'Kety',
        'email' => 'kety@gmail.com',
        'password' => Hash::make('password'), 
        'address' => 'Keur Massar',
        'phone_number' => '+221771009090',
    ]);
    $user6->assignRole('client'); 
    }  
}
