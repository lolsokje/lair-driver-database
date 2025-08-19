<?php

declare(strict_types=1);

namespace App\Filament\Resources\Series\Pages;

use App\Filament\Resources\Series\SeriesResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateSeries extends CreateRecord
{
    protected static string $resource = SeriesResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
