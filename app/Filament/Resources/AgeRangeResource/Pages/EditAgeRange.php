<?php

declare(strict_types=1);

namespace App\Filament\Resources\AgeRangeResource\Pages;

use App\Filament\Resources\AgeRangeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditAgeRange extends EditRecord
{
    protected static string $resource = AgeRangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
