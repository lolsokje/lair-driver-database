<?php

declare(strict_types=1);

use App\Enums\DevelopmentResultStatus;
use App\Jobs\RollBackDriverDevelopmentJob;
use App\Models\DevelopmentResult;
use App\Models\DevelopmentRound;
use App\Models\Driver;
use App\Models\Season;
use App\Models\Series;
use App\Models\Team;

test('rolls back driver development', function () {
    $series = Series::factory()->create();
    $season = Season::factory()->create();
    $driver = Driver::factory()->create([
        'rating' => 42,
    ]);
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
        ->applied()
        ->for($driver)
        ->for($developmentRound)
        ->create([
            'old_rating' => 40,
            'development' => 2,
        ]);

    $job = new RollBackDriverDevelopmentJob($developmentRound);

    $job->handle();

    $developmentResult->refresh();
    $driver->refresh();

    /** @var Driver $seasonDriver */
    $seasonDriver = $team->drivers()->first();
    $this->assertEquals($driver->rating, 40);
    $this->assertEquals($seasonDriver->rating, 40);

    $this->assertEquals(DevelopmentResultStatus::PENDING, $developmentResult->status);
});
