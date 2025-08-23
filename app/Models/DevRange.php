<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class DevRange extends Model
{
    public function ageRange(): BelongsTo
    {
        return $this->belongsTo(AgeRange::class);
    }
}
