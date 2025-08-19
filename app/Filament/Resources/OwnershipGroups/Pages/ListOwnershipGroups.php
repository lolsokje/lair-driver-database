<?php

declare(strict_types=1);

namespace App\Filament\Resources\OwnershipGroups\Pages;

use App\Filament\Resources\OwnershipGroups\OwnershipGroupResource;
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
