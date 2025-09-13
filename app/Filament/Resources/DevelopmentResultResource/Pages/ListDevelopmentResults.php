<?php

declare(strict_types=1);

namespace App\Filament\Resources\DevelopmentResultResource\Pages;

use App\Filament\Resources\DevelopmentResultResource;
use Filament\Resources\Pages\ListRecords;

final class ListDevelopmentResults extends ListRecords
{
    protected static string $resource = DevelopmentResultResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
