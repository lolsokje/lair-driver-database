<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Team extends Model
{
    /** @use HasFactory<TeamFactory> */
    use HasFactory;

    public function ownershipGroup(): BelongsTo
    {
        return $this->belongsTo(OwnershipGroup::class);
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    public function drivers(): BelongsToMany
    {
        return $this->belongsToMany(Driver::class)
            ->withPivot([
                'number',
                'rating',
                'driver_sheet_id',
                'reserve',
            ]);
    }
}
