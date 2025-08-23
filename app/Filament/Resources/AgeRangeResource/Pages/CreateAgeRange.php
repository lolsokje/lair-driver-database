<?php

declare(strict_types=1);

namespace App\Filament\Resources\AgeRangeResource\Pages;

use App\Filament\Resources\AgeRangeResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateAgeRange extends CreateRecord
{
    protected static string $resource = AgeRangeResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
