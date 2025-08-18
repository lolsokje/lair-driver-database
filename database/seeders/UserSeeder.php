<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\OwnershipGroup;
use App\Models\Season;
use App\Models\User;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    public function run(): void
    {
        /** @var Season $season */
        $season = Season::create([
            'year' => config('app.start_year'),
        ]);

        /** @var array<array{discord_id: int, username: string}> $users */
        $users = json_decode(file_get_contents(resource_path('json/users.json')), true);
        /** @var array<array{name: string}> $groups */
        $groups = json_decode(file_get_contents(resource_path('json/ownership_groups.json')), true);

        foreach ($users as $user) {
            User::firstOrCreate(
                ['discord_id' => $user['discord_id']],
                [
                    'username' => $user['username'],
                    'admin' => in_array($user['discord_id'], config('services.discord.admin_ids')),
                ],
            );
        }

        foreach ($groups as $group) {
            $name = $group['name'];

            $users = explode(' / ', $name);

            /** @var OwnershipGroup $group */
            $group = OwnershipGroup::create([
                'season_id' => $season->id,
                'name' => $name,
            ]);

            foreach ($users as $user) {
                $user = User::where('username', $user)->first();

                if (! $user) {
                    continue;
                }

                $group->users()->attach($user);
            }
        }
    }
}
