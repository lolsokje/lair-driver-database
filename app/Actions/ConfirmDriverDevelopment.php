<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exceptions\InvalidStateException;
use App\Jobs\ConfirmDriverDevelopmentJob;
use App\Models\DevelopmentRound;

final readonly class ConfirmDriverDevelopment
{
    public static function handle(DevelopmentRound $developmentRound): void
    {
        if (! $developmentRound->canBeConfirmed()) {
            throw new InvalidStateException("Development round with state [{$developmentRound->status->getLabel()}] can not be confirmed");
        }

        $developmentRound->markConfirmed();

        ConfirmDriverDevelopmentJob::dispatch($developmentRound);
    }
}
