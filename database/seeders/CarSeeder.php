<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        // Vérifier si des voitures existent déjà
        if (Car::count() === 0) {
            Car::create([
                'marque' => 'Toyota',
                'model' => 'Corolla',
                'year' => 2020,
                'matricule' => 'DK-1234-AB',
            ]);

            Car::create([
                'marque' => 'Honda',
                'model' => 'Civic',
                'year' => 2021,
                'matricule' => 'DK-5678-CD',
            ]);

            Car::create([
                'marque' => 'Nissan',
                'model' => 'Sentra',
                'year' => 2019,
                'matricule' => 'DK-9012-EF',
            ]);

            Car::create([
                'marque' => 'Hyundai',
                'model' => 'Elantra',
                'year' => 2022,
                'matricule' => 'DK-3456-GH',
            ]);
        }
    }
}
