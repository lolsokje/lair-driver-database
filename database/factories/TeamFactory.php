<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\OwnershipGroup;
use App\Models\Season;
use App\Models\Series;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Team> */
final class TeamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ownership_group_id' => OwnershipGroup::factory(),
            'series_id' => Series::factory(),
            'season_id' => Season::factory(),
            'short_name' => $this->faker->company(),
            'full_name' => $this->faker->company(),
            'primary_colour' => $this->faker->safeHexColor(),
        ];
    }
}
