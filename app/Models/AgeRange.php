<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class AgeRange extends Model
{
    /**
     * @return HasMany<DevRange, $this>
     */
    public function devRanges(): HasMany
    {
        return $this->hasMany(DevRange::class);
    }
}
