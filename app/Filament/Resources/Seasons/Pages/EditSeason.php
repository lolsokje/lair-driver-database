<?php

declare(strict_types=1);

namespace App\Filament\Resources\Seasons\Pages;

use App\Actions\Seasons\CopySeason;
use App\Enums\SeasonStatus;
use App\Filament\Resources\Seasons\SeasonResource;
use App\Models\Season;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

final class EditSeason extends EditRecord
{
    protected static string $resource = SeasonResource::class;

    protected function getHeaderActions(): array
    {
        /** @var Season $currentSeason */
        $currentSeason = $this->record;
        /** @var Season|null $previousSeason */
        $previousSeason = Season::query()->where('year', $currentSeason->year - 1)->first();

        return [
            Action::make('Copy previous season')
                ->hidden(function () use ($currentSeason, $previousSeason) {
                    if ($currentSeason->status !== SeasonStatus::PENDING && $currentSeason->status !== SeasonStatus::PREPARATION) {
                        return true;
                    }

                    if (! $previousSeason) {
                        return true;
                    }

                    return false;
                })
                ->requiresConfirmation()
                ->modalHeading('Copy previous season')
                ->modalDescription(
                    "Are you sure you want to copy the {$previousSeason->year} season to {$currentSeason->year}? This will reset any teams and drivers already configured for {$currentSeason->year} and replace them with those of the {$previousSeason->year} season"
                )
                ->action(function (Component $livewire) use ($previousSeason, $currentSeason) {
                    CopySeason::handle(
                        oldSeason: $previousSeason,
                        newSeason: $currentSeason,
                    );

                    $livewire->redirect(EditSeason::getUrl(['record' => $currentSeason]));
                })
                ->icon(Heroicon::DocumentDuplicate),

            DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /** @var SeasonStatus $status */
        $status = $data['status'];

        if ($status === SeasonStatus::ACTIVE) {
            Season::query()
                ->where('status', SeasonStatus::ACTIVE)
                ->update([
                    'status' => SeasonStatus::COMPLETED,
                ]);
        }

        return parent::handleRecordUpdate($record, $data);
    }
}
