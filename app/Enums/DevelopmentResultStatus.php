<?php

declare(strict_types=1);

namespace App\Enums;

enum DevelopmentResultStatus: int
{
    case PENDING = 0;
    case APPLIED = 1;
    case FAILED = 2;
}
