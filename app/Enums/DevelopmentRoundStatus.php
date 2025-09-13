<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum DevelopmentRoundStatus: int implements HasColor, HasLabel
{
    case STARTED = 0;
    case PENDING_CONFIRMATION = 1;
    case CONFIRMED = 2;
    case REJECTED = 3;
    case FAILED = 4;

    public function getLabel(): string
    {
        return match ($this) {
            self::STARTED => 'started',
            self::PENDING_CONFIRMATION => 'confirmation pending',
            self::CONFIRMED => 'confirmed',
            self::REJECTED => 'rejected',
            self::FAILED => 'failed',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::STARTED => 'gray',
            self::PENDING_CONFIRMATION => 'warning',
            self::CONFIRMED => 'success',
            self::REJECTED, self::FAILED => 'danger',
        };
    }
}
