<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $driver = User::where('email', 'driver@test.com')->first();

        if ($driver) {
            Car::firstOrCreate(
                ['driver_id' => $driver->id],
                [
                    'brand' => 'Opel',
                    'model' => 'Astra G', // Классика для дорог Таджикистана
                    'license_plate' => '0101 TT 02',
                    'year' => '2025',
                    
                ]
            );
        }
    }
}