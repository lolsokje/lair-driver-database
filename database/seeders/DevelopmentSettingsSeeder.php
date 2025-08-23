<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AgeRange;
use Illuminate\Database\Seeder;

final class DevelopmentSettingsSeeder extends Seeder
{
    public function run(): void
    {
        /** @var array<array{min_age: int, max_age: int}> $ageRanges */
        $ageRanges = json_decode(file_get_contents(resource_path('json/age_ranges.json')), true);
        /** @var array<array{min_age: int, max_age: int, min_rating: int, max_rating: int, min_dev: int, max_dev: int}> $devRanges */
        $devRanges = json_decode(file_get_contents(resource_path('json/dev_ranges.json')), true);

        foreach ($ageRanges as $ageRange) {
            AgeRange::create([
                'min_age' => $ageRange['min_age'],
                'max_age' => $ageRange['max_age'],
            ]);
        }

        foreach ($devRanges as $devRange) {
            $ageRange = AgeRange::query()
                ->where('min_age', $devRange['min_age'])
                ->where('max_age', $devRange['max_age'])
                ->first();

            $ageRange->devRanges()->create([
                'min_rating' => $devRange['min_rating'],
                'max_rating' => $devRange['max_rating'],
                'min_dev' => $devRange['min_dev'],
                'max_dev' => $devRange['max_dev'],
            ]);
        }
    }
}
