<?php

declare(strict_types=1);

namespace App\Filament\Resources\DevelopmentRoundResource\Pages;

use App\Enums\SeasonStatus;
use App\Filament\Resources\DevelopmentRoundResource;
use App\Jobs\PerformDriverDevelopmentJob;
use App\Models\Season;
use Exception;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

final class ListDevelopmentRounds extends ListRecords
{
    protected static string $resource = DevelopmentRoundResource::class;

    private readonly Season $season;

    public function __construct()
    {
        $season = Season::query()
            ->where('status', SeasonStatus::ACTIVE)
            ->with('developmentRounds')
            ->first();

        if (! $season) {
            throw new Exception('There currently is no active season');
        }

        $this->season = $season;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Start driver development')
                ->hidden($this->season->hasPendingDevelopmentRounds())
                ->icon(Heroicon::Play)
                ->requiresConfirmation()
                ->modalDescription(function () {
                    if ($this->season->hasDevelopmentRounds()) {
                        return "Are you sure you want to run development again for the {$this->season->year} season? Development has already been run at least once.";
                    }

                    return "Are you sure you want to run development for the {$this->season->year} season?";
                })
                ->modalIcon(Heroicon::ExclamationTriangle)
                ->modalIconColor(fn () => $this->season->hasDevelopmentRounds() ? 'danger' : 'warning')
                ->modalSubmitAction(fn (Action $action) => $action->color($this->season->hasDevelopmentRounds() ? 'danger' : 'primary'))
                ->action(function () {
                    $developmentRound = $this->season->developmentRounds()->create();

                    PerformDriverDevelopmentJob::dispatch(
                        $developmentRound,
                    );

                    Notification::make()
                        ->title('New development round started')
                        ->body('The development round has been created, check back in a few seconds to see the results.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
