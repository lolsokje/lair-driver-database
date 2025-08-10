<?php

declare(strict_types=1);

namespace App\Filament\Resources\OwnershipGroupResource\Pages;

use App\Filament\Resources\OwnershipGroupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListOwnershipGroups extends ListRecords
{
    protected static string $resource = OwnershipGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
