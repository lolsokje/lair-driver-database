<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DevelopmentRoundStatus;
use App\Enums\SeasonStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Season extends Model
{
    protected $casts = [
        'status' => SeasonStatus::class,
    ];

    public function ownershipGroups(): HasMany
    {
        return $this->hasMany(OwnershipGroup::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function drivers(): BelongsToMany
    {
        return $this->belongsToMany(Driver::class, 'driver_team');
    }

    /**
     * @return HasMany<DevelopmentRound, $this>
     */
    public function developmentRounds(): HasMany
    {
        return $this->hasMany(DevelopmentRound::class);
    }

    public function hasDevelopmentRounds(): bool
    {
        return count($this->developmentRounds) > 0;
    }

    public function hasPendingDevelopmentRounds(): bool
    {
        return $this->developmentRounds->where('status', DevelopmentRoundStatus::PENDING_CONFIRMATION)->count() > 0;
    }
}
