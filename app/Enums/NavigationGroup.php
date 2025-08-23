<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum NavigationGroup implements HasLabel
{
    case TEAMS_AND_DRIVERS;
    case SETTINGS;
    case OTHERS;
    case DEVELOPMENT;

    public function getLabel(): string
    {
        return match ($this) {
            self::TEAMS_AND_DRIVERS => 'Teams and Drivers',
            self::SETTINGS => 'Settings',
            self::OTHERS => 'Others',
            self::DEVELOPMENT => 'Development',
        };
    }
}
