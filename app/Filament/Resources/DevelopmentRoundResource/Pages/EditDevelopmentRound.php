<?php

declare(strict_types=1);

namespace App\Filament\Resources\DevelopmentRoundResource\Pages;

use App\Actions\ConfirmDriverDevelopment;
use App\Actions\RejectDriverDevelopment;
use App\Actions\RollBackDriverDevelopment;
use App\Enums\DevelopmentRoundStatus;
use App\Filament\Resources\DevelopmentRoundResource;
use App\Models\DevelopmentRound;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

final class EditDevelopmentRound extends EditRecord
{
    protected static string $resource = DevelopmentRoundResource::class;

    protected static ?string $title = 'Development results';

    protected function getHeaderActions(): array
    {
        /** @var DevelopmentRound $record */
        $record = $this->getRecord();

        return [
            Action::make('confirm')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () use ($record) {
                    ConfirmDriverDevelopment::handle($record);

                    Notification::make()
                        ->title('Development round confirmed')
                        ->body('Development results will be applied in the background')
                        ->success()
                        ->send();

                    redirect(static::getUrl(['record' => $record]));
                })
                ->hidden(! $record->canBeConfirmed()),

            Action::make('reject')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () use ($record) {
                    RejectDriverDevelopment::handle($record);

                    Notification::make()
                        ->title('Development round rejected')
                        ->success()
                        ->send();

                    redirect(static::getUrl(['record' => $record]));
                })
                ->hidden($record->status !== DevelopmentRoundStatus::PENDING_CONFIRMATION),

            Action::make('rollback')
                ->label('Roll back')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () use ($record) {
                    RollBackDriverDevelopment::handle($record);

                    Notification::make()
                        ->title('Development rolled back')
                        ->body('Confirmed development will be rolled back to old ratings')
                        ->success()
                        ->send();

                    redirect(static::getUrl(['record' => $record]));
                })
                ->hidden(! $record->canBeRolledBack()),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->hidden();
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->hidden();
    }
}
