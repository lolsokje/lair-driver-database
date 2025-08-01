<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SeasonStatus: int implements HasColor, HasLabel
{
    case PENDING = 0;
    case PREPARATION = 1;
    case ACTIVE = 2;
    case COMPLETED = 3;

    public function getLabel(): string
    {
        return ucfirst(mb_strtolower($this->name));
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::PREPARATION => 'info',
            self::ACTIVE => 'success',
            self::COMPLETED => 'danger',
        };
    }
}
