<?php

declare(strict_types=1);

namespace App\Filament\Resources\OwnershipGroups\Pages;

use App\Filament\Resources\OwnershipGroups\OwnershipGroupResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateOwnershipGroup extends CreateRecord
{
    protected static string $resource = OwnershipGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
