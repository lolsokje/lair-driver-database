<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Driver extends Model
{
    protected $casts = [
        'date_of_birth' => 'date',
        'retired' => 'boolean',
    ];

    public function fullName(): string
    {
        return "$this->given_name $this->family_name";
    }

    public function ageForSeason(int $year): int
    {
        $cutoff = CarbonImmutable::createFromFormat('Y-m-d', "{$year}-03-01");

        return (int) floor($this->date_of_birth->diffInYears($cutoff));
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)
            ->withPivot([
                'number',
                'rating',
                'reserve',
            ]);
    }

    public function series(): BelongsToMany
    {
        return $this->belongsToMany(Series::class, 'driver_team');
    }

    public function season(): BelongsToMany
    {
        return $this->belongsToMany(Season::class, 'driver_team');
    }
}
