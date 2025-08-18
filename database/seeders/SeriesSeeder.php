<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Series;
use App\Models\User;
use Illuminate\Database\Seeder;

final class SeriesSeeder extends Seeder
{
    public function run(): void
    {
        /** @var array<array{name: string, background_colour: string, text_colour: string, simmed_by: int}> $series */
        $series = json_decode(file_get_contents(resource_path('json/series.json')), true);

        foreach ($series as $serie) {
            Series::create([
                'name' => $serie['name'],
                'background_colour' => $serie['background_colour'],
                'text_colour' => $serie['text_colour'],
                'simmed_by' => User::where('discord_id', $serie['simmed_by'])->first()->id,
            ]);
        }
    }
}
