<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\DevelopmentResultStatus;
use App\Models\DevelopmentResult;
use App\Models\DevelopmentRound;
use App\Models\Season;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Attributes\WithoutRelations;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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

        /** @var Season $season */
        $season = $this->developmentRound->season;

        /** @var DevelopmentResult $result */
        foreach ($this->developmentRound->developmentResults as $result) {
            if ($result->status === DevelopmentResultStatus::APPLIED) {
                continue;
            }

            $driver = $result->driver;

            $newRating = $result->old_rating + $result->development;

            $driver->update([
                'rating' => $newRating,
            ]);

            $season->drivers()->updateExistingPivot($driver->id, [
                'rating' => $newRating,
            ]);

            $result->markApplied();
        }

        $this->developmentRound->markConfirmed();
    }
}
