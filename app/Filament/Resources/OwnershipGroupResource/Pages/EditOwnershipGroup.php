<?php

declare(strict_types=1);

namespace App\Filament\Resources\OwnershipGroupResource\Pages;

use App\Filament\Resources\OwnershipGroupResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditOwnershipGroup extends EditRecord
{
    protected static string $resource = OwnershipGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
