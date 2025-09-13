<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exceptions\InvalidStateException;
use App\Models\DevelopmentRound;

final readonly class RejectDriverDevelopment
{
    public static function handle(DevelopmentRound $developmentRound): void
    {
        if (! $developmentRound->canBeRejected()) {
            throw new InvalidStateException("Development round with status [{$developmentRound->status->getLabel()}] can not be rolled back");
        }

        $developmentRound->markRejected();
    }
}
