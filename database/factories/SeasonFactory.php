<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\SeasonStatus;
use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Season> */
final class SeasonFactory extends Factory
{
    protected $model = Season::class;

    public function definition(): array
    {
        return [
            'year' => $this->faker->year(2000, (int) date('Y')),
            'status' => SeasonStatus::ACTIVE,
        ];
    }
}
