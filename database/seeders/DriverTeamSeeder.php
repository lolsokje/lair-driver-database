<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Series;
use App\Models\Team;
use Illuminate\Database\Seeder;

final class DriverTeamSeeder extends Seeder
{
    public function run(): void
    {
        /** @var array<array{team_name: string, series_name: string, given_name: string, family_name: string, driver_sheet_id: string|int|null}> $combinations */
        $combinations = json_decode(file_get_contents(resource_path('json/driver_team.json')), true);

        foreach ($combinations as $combination) {
            $series = Series::query()
                ->where('name', $combination['series_name'])
                ->first();

            $driver = Driver::query()
                ->where('given_name', $combination['given_name'])
                ->where('family_name', $combination['family_name'])
                ->first();

            $team = Team::query()
                ->where('short_name', $combination['team_name'])
                ->where('series_id', $series->id)
                ->first();

            $team->drivers()->attach($driver, [
                'season_id' => $team->season_id,
                'series_id' => $team->series_id,
                'number' => 0,
                'rating' => $driver->rating,
                'driver_sheet_id' => $combination['driver_sheet_id'],
                'reserve' => false,
            ]);
        }
    }
}
