<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exceptions\InvalidStateException;
use App\Jobs\RollBackDriverDevelopmentJob;
use App\Models\DevelopmentRound;

final readonly class RollBackDriverDevelopment
{
    public static function handle(DevelopmentRound $developmentRound): void
    {
        if (! $developmentRound->canBeRolledBack()) {
            throw new InvalidStateException("Development round with status [{$developmentRound->status->getLabel()}] can not be rolled back");
        }

        $developmentRound->markRolledBack();

        RollBackDriverDevelopmentJob::dispatch($developmentRound);
    }
}
