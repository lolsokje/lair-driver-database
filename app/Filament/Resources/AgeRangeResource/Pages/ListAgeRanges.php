<?php

declare(strict_types=1);

namespace App\Filament\Resources\AgeRangeResource\Pages;

use App\Filament\Resources\AgeRangeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListAgeRanges extends ListRecords
{
    protected static string $resource = AgeRangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
