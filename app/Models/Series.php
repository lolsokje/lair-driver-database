<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\SeriesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Series extends Model
{
    /** @use HasFactory<SeriesFactory> */
    use HasFactory;

    public function simmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'simmed_by');
    }
}
