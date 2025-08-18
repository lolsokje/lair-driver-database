<?php

declare(strict_types=1);

namespace App\Filament\Resources\DriverResource\Pages;

use App\Filament\Resources\DriverResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateDriver extends CreateRecord
{
    protected static string $resource = DriverResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
