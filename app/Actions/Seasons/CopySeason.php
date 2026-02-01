<?php

declare(strict_types=1);

namespace App\Actions\Seasons;

use App\Models\Driver;
use App\Models\Season;
use App\Models\Team;
use DB;
use Illuminate\Support\Collection;

final class CopySeason
{
    private static Season $oldSeason;

    private static Season $newSeason;

    private static array $teams = [];

    public static function handle(
        Season $oldSeason,
        Season $newSeason,
    ): void {
        self::$oldSeason = $oldSeason;
        self::$newSeason = $newSeason;

        self::resetSeason();

        self::copyTeams();

        self::copyDrivers();
    }

    private static function resetSeason(): void
    {
        self::$newSeason->drivers()->detach();
        self::$newSeason->teams()->delete();
    }

    private static function copyTeams(): void
    {
        /** @var Collection<int, Team> $oldTeams */
        $oldTeams = self::$oldSeason->teams;

        foreach ($oldTeams as $oldTeam) {
            /** @var Team $newTeam */
            $newTeam = $oldTeam->replicate();

            $newTeam->season_id = self::$newSeason->id;

            $newTeam->save();

            self::$teams[$oldTeam->id] = $newTeam->id;
        }
    }

    private static function copyDrivers(): void
    {
        $oldDrivers = DB::query()->select('*')->from('driver_team')->where('season_id', self::$oldSeason->id)->get();

        foreach ($oldDrivers as $oldDriver) {
            /** @var Driver $driver */
            $driver = Driver::findOrFail($oldDriver->driver_id);
            $newTeamId = self::$teams[$oldDriver->team_id];

            $driver->teams()->attach($newTeamId, [
                'season_id' => self::$newSeason->id,
                'series_id' => $oldDriver->series_id,
                'rating' => $oldDriver->rating,
                'driver_sheet_id' => $oldDriver->driver_sheet_id,
                'reserve' => $oldDriver->reserve,
            ]);
        }
    }
}
