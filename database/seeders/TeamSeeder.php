<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\OwnershipGroup;
use App\Models\Season;
use App\Models\Series;
use App\Models\Team;
use Illuminate\Database\Seeder;

final class TeamSeeder extends Seeder
{
    public function run(): void
    {
        /** @var array<array{
         *     series_name: string,
         *     short_name: string,
         *     full_name: string,
         *     owners: string,
         *     team_id: null|string|int
         *     }> $teams
         */
        $teams = json_decode(file_get_contents(resource_path('json/teams.json')), true);

        foreach ($teams as $team) {
            $series = Series::where('name', $team['series_name'])->first();
            $users = explode(' / ', $team['owners']);
            sort($users);
            $name = implode(', ', $users);
            $ownershipGroup = OwnershipGroup::where('name', $name)->first();

            Team::create([
                'season_id' => Season::first()->id,
                'series_id' => $series->id,
                'ownership_group_id' => $ownershipGroup->id,
                'short_name' => $team['short_name'],
                'full_name' => $team['full_name'],
                'team_id' => $team['team_id'],
                'primary_colour' => '#000000',
                'secondary_colour' => '#FFFFFF',
            ]);
        }
    }
}
