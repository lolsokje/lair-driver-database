<?php

declare(strict_types=1);

namespace App\Filament\Resources\DevRangeResource\Pages;

use App\Filament\Resources\DevRangeResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateDevRange extends CreateRecord
{
    protected static string $resource = DevRangeResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
