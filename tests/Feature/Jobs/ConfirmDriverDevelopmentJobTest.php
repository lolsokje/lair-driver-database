<?php

declare(strict_types=1);

use App\Enums\DevelopmentResultStatus;
use App\Jobs\ConfirmDriverDevelopmentJob;
use App\Models\DevelopmentResult;
use App\Models\DevelopmentRound;
use App\Models\Driver;
use App\Models\Season;
use App\Models\Series;
use App\Models\Team;

test('applies driver development correctly', function () {
    $series = Series::factory()->create();
    $season = Season::factory()->create();
    $driver = Driver::factory()->create();
    $team = Team::factory()
        ->for($series)
        ->create();

    $team->drivers()->attach($driver, [
        'season_id' => $season->id,
        'series_id' => $series->id,
        'number' => 2,
        'rating' => $driver->rating,
        'reserve' => false,
    ]);

    $developmentRound = DevelopmentRound::factory()
        ->for($season)
        ->create();

    $developmentResult = DevelopmentResult::factory()
        ->for($driver)
        ->for($developmentRound)
        ->create([
            'old_rating' => $driver->rating,
        ]);

    $job = new ConfirmDriverDevelopmentJob($developmentRound);

    $job->handle();

    $developmentResult->refresh();
    $driver->refresh();

    /** @var Driver $seasonDriver */
    $seasonDriver = $team->drivers()->first();
    $this->assertEquals($driver->rating, $developmentResult->old_rating + $developmentResult->development);
    $this->assertEquals($seasonDriver->rating, $developmentResult->old_rating + $developmentResult->development);

    $this->assertEquals(DevelopmentResultStatus::APPLIED, $developmentResult->status);
});
