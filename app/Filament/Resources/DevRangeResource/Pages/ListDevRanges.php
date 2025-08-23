<?php

declare(strict_types=1);

namespace App\Filament\Resources\DevRangeResource\Pages;

use App\Filament\Resources\DevRangeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListDevRanges extends ListRecords
{
    protected static string $resource = DevRangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
