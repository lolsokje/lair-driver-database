<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AgeRange;
use App\Models\DevRange;
use Illuminate\Support\Collection;

final readonly class DriverDevelopmentService
{
    /** @var Collection<int, AgeRange> */
    private Collection $ageRanges;

    public function __construct()
    {
        $this->ageRanges = AgeRange::query()
            ->with([
                'devRanges',
            ])
            ->orderBy('min_age')
            ->get()
            ->collect();
    }

    public function getAgeRangeForAge(int $age): AgeRange
    {
        return $this->ageRanges->firstOrFail(function (AgeRange $range) use ($age) {
            return $range->min_age <= $age && $range->max_age >= $age;
        });
    }

    public function getDevelopmentRangeForRating(AgeRange $range, int $rating): DevRange
    {
        return $range->devRanges->firstOrFail(function (DevRange $devRange) use ($rating) {
            return $devRange->min_rating <= $rating && $devRange->max_rating >= $rating;
        });
    }

    public function getDevelopment(int $min, int $max): int
    {
        return random_int($min, $max);
    }
}
