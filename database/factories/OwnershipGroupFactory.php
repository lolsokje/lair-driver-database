<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;

final class OwnershipGroupFactory extends Factory
{
    public function definition(): array
    {
        return [
            'season_id' => Season::factory(),
            'name' => $this->faker->name(),
        ];
    }
}
