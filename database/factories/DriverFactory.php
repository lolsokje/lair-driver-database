<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Driver> */
final class DriverFactory extends Factory
{
    public function definition(): array
    {
        return [
            'given_name' => $this->faker->firstName(),
            'family_name' => $this->faker->lastName(),
            'date_of_birth' => $this->faker->dateTimeBetween('-40 years', '-18 years'),
            'rating' => $this->faker->numberBetween(30, 60),
            'retired' => false,
        ];
    }
}
