<?php

declare(strict_types=1);

namespace App\Filament\Resources\DevRangeResource\Pages;

use App\Filament\Resources\DevRangeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditDevRange extends EditRecord
{
    protected static string $resource = DevRangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
