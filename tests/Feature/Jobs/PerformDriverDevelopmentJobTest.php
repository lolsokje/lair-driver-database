<?php

declare(strict_types=1);

use App\Enums\DevelopmentRoundStatus;
use App\Jobs\PerformDriverDevelopmentJob;
use App\Models\DevelopmentResult;
use App\Models\DevelopmentRound;
use App\Models\Driver;
use App\Models\Season;
use App\Models\Series;
use App\Models\Team;
use App\Services\DriverDevelopmentService;
use Database\Seeders\DevelopmentSettingsSeeder;

use function Pest\Laravel\artisan;

beforeEach(fn () => artisan('db:seed', ['--class' => DevelopmentSettingsSeeder::class]));

test('performs driver development', function () {
    $season = Season::factory()->create([
        'year' => (int) date('Y'),
    ]);
    $series = Series::factory()->create();

    $drivers = Driver::factory(10)->create();

    foreach ($drivers as $driver) {
        $team = Team::factory()
            ->for($season)
            ->for($series)
            ->create();

        $team->drivers()->attach($driver, [
            'season_id' => $season->id,
            'series_id' => $series->id,
            'rating' => 40,
            'number' => fake()->numberBetween(2, 99),
            'reserve' => false,
        ]);
    }

    $developmentRound = DevelopmentRound::factory()
        ->for($season)
        ->create();

    $developmentService = new DriverDevelopmentService();

    $job = new PerformDriverDevelopmentJob($developmentRound);

    $job->handle($developmentService);

    $developmentRound->refresh();

    $this->assertCount(10, $developmentRound->developmentResults);
    $this->assertEquals(DevelopmentRoundStatus::PENDING_CONFIRMATION, $developmentRound->status);

    foreach ($drivers as $driver) {
        $this->assertInstanceOf(DevelopmentResult::class, $developmentRound->developmentResults->where('driver_id', $driver->id)->first());
    }
});

test('does not run development for retired drivers', function () {
    $season = Season::factory()->create([
        'year' => (int) date('Y'),
    ]);
    $series = Series::factory()->create();

    $drivers = Driver::factory(2)
        ->sequence(
            ['retired' => true],
            ['retired' => false],
        )
        ->create();

    foreach ($drivers as $driver) {
        $team = Team::factory()
            ->for($season)
            ->for($series)
            ->create();

        $team->drivers()->attach($driver, [
            'season_id' => $season->id,
            'series_id' => $series->id,
            'rating' => 40,
            'number' => fake()->numberBetween(2, 99),
            'reserve' => false,
        ]);
    }

    $developmentRound = DevelopmentRound::factory()
        ->for($season)
        ->create();

    $developmentService = new DriverDevelopmentService();

    $job = new PerformDriverDevelopmentJob($developmentRound);

    $job->handle($developmentService);

    $developmentRound->refresh();

    $this->assertCount(1, $developmentRound->developmentResults);
    $this->assertEquals(DevelopmentRoundStatus::PENDING_CONFIRMATION, $developmentRound->status);

    foreach ($developmentRound->developmentResults as $result) {
        $this->assertFalse($result->driver->retired);
    }
});
