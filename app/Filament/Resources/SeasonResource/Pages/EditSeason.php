<?php

declare(strict_types=1);

namespace App\Filament\Resources\SeasonResource\Pages;

use App\Enums\SeasonStatus;
use App\Filament\Resources\SeasonResource;
use App\Models\Season;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

final class EditSeason extends EditRecord
{
    protected static string $resource = SeasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $status = SeasonStatus::from((int) $data['status']);

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
