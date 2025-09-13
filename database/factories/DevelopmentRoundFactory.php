<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DevelopmentRoundStatus;
use App\Models\DevelopmentRound;
use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<DevelopmentRound> */
final class DevelopmentRoundFactory extends Factory
{
    public function definition(): array
    {
        return [
            'season_id' => Season::factory(),
            'status' => DevelopmentRoundStatus::PENDING_CONFIRMATION,
        ];
    }

    public function confirmed(): self
    {
        return $this->state(function () {
            return [
                'status' => DevelopmentRoundStatus::CONFIRMED,
            ];
        });
    }
}
