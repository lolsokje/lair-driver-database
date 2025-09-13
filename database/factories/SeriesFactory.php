<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Series;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Series> */
final class SeriesFactory extends Factory
{
    public function definition(): array
    {
        return [
            'simmed_by' => User::factory(),
            'name' => $this->faker->name(),
            'background_colour' => $this->faker->safeHexColor(),
            'text_colour' => $this->faker->safeHexColor(),
        ];
    }
}
