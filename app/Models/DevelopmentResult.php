<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DevelopmentResultStatus;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class DevelopmentResult extends Model
{
    protected $casts = [
        'status' => DevelopmentResultStatus::class,
    ];

    /**
     * @return BelongsTo<DevelopmentRound, $this>
     */
    public function developmentRound(): BelongsTo
    {
        return $this->belongsTo(DevelopmentRound::class);
    }

    /**
     * @return BelongsTo<Driver, $this>
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function color(): string
    {
        if ($this->ratingIncreased()) {
            return 'success';
        }

        if ($this->ratingDecreased()) {
            return 'danger';
        }

        return 'info';
    }

    public function icon(): Heroicon
    {
        if ($this->ratingIncreased()) {
            return Heroicon::ArrowUp;
        }

        if ($this->ratingDecreased()) {
            return Heroicon::ArrowDown;
        }

        return Heroicon::Equals;
    }

    private function ratingIncreased(): bool
    {
        return $this->development > 0;
    }

    private function ratingDecreased(): bool
    {
        return $this->development < 0;
    }
}
