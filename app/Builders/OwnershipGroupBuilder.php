<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\OwnershipGroup;
use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin OwnershipGroup
 */
final class OwnershipGroupBuilder extends Builder
{
    public function unusedInSeason(?int $seasonId): self
    {
        if (! $seasonId) {
            return $this;
        }

        return $this->whereNotIn('id', Team::query()->where('season_id', $seasonId)->pluck('ownership_group_id'));
    }
}
