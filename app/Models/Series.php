<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Series extends Model
{
    public function simmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'simmed_by');
    }
}
