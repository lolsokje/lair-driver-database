<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\DevelopmentResultStatus;
use App\Models\DevelopmentResult;
use App\Models\DevelopmentRound;
use App\Models\Driver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Attributes\WithoutRelations;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

final class ConfirmDriverDevelopmentJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        #[WithoutRelations]
        private readonly DevelopmentRound $developmentRound,
    ) {}

    public function handle(): void
    {
        $this->developmentRound->load([
            'season' => [
                'drivers',
            ],
            'developmentResults' => [
                'driver',
            ],
        ]);

        /** @var Collection<int, Driver> $driversInSeason */
        $driversInSeason = $this->developmentRound->season->drivers->collect();

        /** @var DevelopmentResult $result */
        foreach ($this->developmentRound->developmentResults as $result) {
            if ($result->status === DevelopmentResultStatus::APPLIED) {
                continue;
            }

            $driver = $result->driver;
            /** @var ?Driver $seasonDriver */
            $seasonDriver = $driversInSeason->where('id', $driver->id)->first();

            if (! $seasonDriver) {
                $result->markFailed();

                continue;
            }

            $newRating = $result->old_rating + $result->development;

            $driver->update([
                'rating' => $newRating,
            ]);

            $seasonDriver->update([
                'rating' => $newRating,
            ]);

            $result->markApplied();
        }

        $this->developmentRound->markConfirmed();
    }
}
