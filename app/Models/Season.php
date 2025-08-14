<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SeasonStatus;
use Illuminate\Database\Eloquent\Model;
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
}
