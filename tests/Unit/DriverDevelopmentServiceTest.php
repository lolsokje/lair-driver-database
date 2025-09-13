<?php

declare(strict_types=1);

use App\Models\AgeRange;
use App\Services\DriverDevelopmentService;
use Database\Seeders\DevelopmentSettingsSeeder;
use Illuminate\Support\ItemNotFoundException;

use function Pest\Laravel\artisan;

beforeEach(fn () => artisan('db:seed', ['--class' => DevelopmentSettingsSeeder::class]));

test('returns the correct age range', function (
    int $age,
    int $expectedMinAge,
    int $expectedMaxAge,
) {
    $service = app()->get(DriverDevelopmentService::class);

    $ageRange = $service->getAgeRangeForAge($age);

    $this->assertEquals($expectedMinAge, $ageRange->min_age);
    $this->assertEquals($expectedMaxAge, $ageRange->max_age);
})->with([
    [0, 0, 17],
    [10, 0, 17],
    [17, 0, 17],
    [18, 18, 24],
    [21, 18, 24],
    [24, 18, 24],
    [25, 25, 29],
    [27, 25, 29],
    [29, 25, 29],
    [30, 30, 39],
    [35, 30, 39],
    [39, 30, 39],
    [40, 40, 99],
    [50, 40, 99],
    [99, 40, 99],
]);

test('throws an exception when no range can be found', function () {
    $service = app()->get(DriverDevelopmentService::class);

    $this->expectException(ItemNotFoundException::class);

    $service->getAgeRangeForAge(100);
});

test('returns the correct dev range', function (
    int $rating,
    int $expectedMinRating,
    int $expectedMaxRating,
) {
    $service = app()->get(DriverDevelopmentService::class);

    $ageRange = AgeRange::first();

    $devRange = $service->getDevelopmentRangeForRating($ageRange, $rating);

    $this->assertEquals($expectedMinRating, $devRange->min_rating);
    $this->assertEquals($expectedMaxRating, $devRange->max_rating);
})->with([
    [0, 0, 1],
    [1, 0, 1],
    [30, 30, 39],
    [35, 30, 39],
    [39, 30, 39],
    [40, 40, 49],
    [45, 40, 49],
    [49, 40, 49],
    [50, 50, 99],
    [55, 50, 99],
    [59, 50, 99],
]);

test('throws an exception when no dev range can be found', function () {
    $service = app()->get(DriverDevelopmentService::class);

    $this->expectException(ItemNotFoundException::class);

    $service->getDevelopmentRangeForRating(AgeRange::first(), 100);
});

test('returns a valid development number', function () {
    $service = app()->get(DriverDevelopmentService::class);

    $min = -2;
    $max = 2;
    $development = $service->getDevelopment($min, $max);

    $this->assertGreaterThanOrEqual($min, $development);
    $this->assertLessThanOrEqual($max, $development);
});
