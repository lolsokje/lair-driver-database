<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;

final class DriverSeeder extends Seeder
{
    public function run(): void
    {
        /** @var array<array{first_name: string, last_name: string, dob: string, rating: int} $drivers */
        $drivers = json_decode(file_get_contents(resource_path('/json/drivers.json')), true);

        foreach ($drivers as $driver) {
            Driver::create([
                'given_name' => $driver['first_name'],
                'family_name' => $driver['last_name'],
                'date_of_birth' => $driver['dob'],
                'rating' => $driver['rating'],
            ]);
        }
    }
}
