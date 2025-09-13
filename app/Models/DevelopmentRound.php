<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DevelopmentRoundStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class DevelopmentRound extends Model
{
    protected $casts = [
        'status' => DevelopmentRoundStatus::class,
    ];

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function developmentResults(): HasMany
    {
        return $this->hasMany(DevelopmentResult::class);
    }

    public function markConfirmed(): self
    {
        if ($this->canBeConfirmed()) {
            $this->update([
                'status' => DevelopmentRoundStatus::CONFIRMED,
            ]);
        }

        return $this;
    }

    public function markRejected(): self
    {
        if ($this->canBeRejected()) {
            $this->update([
                'status' => DevelopmentRoundStatus::REJECTED,
            ]);
        }

        return $this;
    }

    public function markRolledBack(): self
    {
        if ($this->canBeRolledBack()) {
            $this->update([
                'status' => DevelopmentRoundStatus::PENDING_CONFIRMATION,
            ]);
        }

        return $this;
    }

    public function canBeConfirmed(): bool
    {
        return $this->status === DevelopmentRoundStatus::PENDING_CONFIRMATION || $this->status === DevelopmentRoundStatus::REJECTED;
    }

    public function canBeRejected(): bool
    {
        return $this->status === DevelopmentRoundStatus::PENDING_CONFIRMATION;
    }

    public function canBeRolledBack(): bool
    {
        return $this->status === DevelopmentRoundStatus::CONFIRMED;
    }
}
