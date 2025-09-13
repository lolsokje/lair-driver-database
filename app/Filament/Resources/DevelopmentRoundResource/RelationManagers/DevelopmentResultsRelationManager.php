<?php

declare(strict_types=1);

namespace App\Filament\Resources\DevelopmentRoundResource\RelationManagers;

use App\Filament\Resources\DevelopmentResultResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

final class DevelopmentResultsRelationManager extends RelationManager
{
    protected static string $relationship = 'developmentResults';

    protected static ?string $relatedResource = DevelopmentResultResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
