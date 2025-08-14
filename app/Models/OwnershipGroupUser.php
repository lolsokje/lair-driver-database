<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

final class OwnershipGroupUser extends Pivot
{
    /**
     * @return BelongsTo<OwnershipGroup, $this>
     */
    public function ownershipGroup(): BelongsTo
    {
        return $this->belongsTo(OwnershipGroup::class);
    }

    protected static function booted(): void
    {
        self::created(function (OwnershipGroupUser $user) {
            /** @var OwnershipGroup $group */
            $group = $user->ownershipGroup;

            $group->updateName();
        });

        self::deleted(function (OwnershipGroupUser $user) {
            /** @var OwnershipGroup $group */
            $group = $user->ownershipGroup;

            $group->updateName();
        });
    }
}
