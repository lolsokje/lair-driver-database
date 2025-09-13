<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\DevelopmentRoundStatus;
use App\Enums\SeasonStatus;
use App\Models\DevelopmentRound;
use App\Models\Driver;
use App\Models\Season;
use App\Models\Series;
use App\Models\Team;
use App\Services\DriverDevelopmentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

final class PerformDriverDevelopmentJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private readonly Season $season;

    /** @var Collection<int, Driver> */
    private readonly Collection $drivers;

    public function __construct(
        private readonly DevelopmentRound $developmentRound,
    ) {
        $this->season = Season::query()
            ->where('status', SeasonStatus::ACTIVE)
            ->first();

        $this->drivers = Driver::query()
            ->where('retired', false)
            ->with([
                'teams' => [
                    'series',
                ],
            ])
            ->get()
            ->collect();
    }

    public function handle(
        DriverDevelopmentService $driverDevelopmentService,
    ): void {
        try {
            DB::transaction(function () use ($driverDevelopmentService) {
                $this->drivers->each(function (Driver $driver) use ($driverDevelopmentService) {
                    $age = $driver->ageForSeason($this->season->year);

                    /** @var Team|null $team */
                    $team = $driver->teams->where('season_id', $this->season->id)->first();
                    /** @var Series|null $series */
                    $series = $team?->series;

                    $ageRange = $driverDevelopmentService->getAgeRangeForAge($age);

                    $devRange = $driverDevelopmentService->getDevelopmentRangeForRating(
                        range: $ageRange,
                        rating: $driver->rating,
                    );

                    $development = $driverDevelopmentService->getDevelopment(
                        min: $devRange->min_dev,
                        max: $devRange->max_dev,
                    );

                    $this->developmentRound->developmentResults()->create([
                        'driver_id' => $driver->id,
                        'series_id' => $series?->id,
                        'team_id' => $team?->id,
                        'old_rating' => $driver->rating,
                        'development' => $development,
                    ]);
                });
            });

            $this->developmentRound->update([
                'status' => DevelopmentRoundStatus::PENDING_CONFIRMATION,
            ]);
        } catch (Throwable $e) {
            $this->developmentRound->update([
                'status' => DevelopmentRoundStatus::FAILED,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
