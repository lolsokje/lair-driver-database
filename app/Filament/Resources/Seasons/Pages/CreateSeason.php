<?php

declare(strict_types=1);

namespace App\Filament\Resources\Seasons\Pages;

use App\Enums\SeasonStatus;
use App\Filament\Resources\Seasons\SeasonResource;
use App\Models\Season;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

final class CreateSeason extends CreateRecord
{
    protected static string $resource = SeasonResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function handleRecordCreation(array $data): Model
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

        return parent::handleRecordCreation($data);
    }
}
