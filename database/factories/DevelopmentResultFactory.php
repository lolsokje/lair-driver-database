<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DevelopmentResultStatus;
use App\Models\DevelopmentResult;
use App\Models\DevelopmentRound;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<DevelopmentResult> */
final class DevelopmentResultFactory extends Factory
{
    public function definition(): array
    {
        return [
            'development_round_id' => DevelopmentRound::factory(),
            'driver_id' => Driver::factory(),
            'old_rating' => $this->faker->numberBetween(30, 60),
            'development' => $this->faker->numberBetween(-3, 3),
            'status' => DevelopmentResultStatus::PENDING,
        ];
    }

    public function applied(): self
    {
        return $this->state(function () {
            return [
                'status' => DevelopmentResultStatus::APPLIED,
            ];
        });
    }
}
