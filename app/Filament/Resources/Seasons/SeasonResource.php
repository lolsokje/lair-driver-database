<?php

declare(strict_types=1);

namespace App\Filament\Resources\Seasons;

use App\Enums\SeasonStatus;
use App\Filament\Resources\Seasons\Pages\CreateSeason;
use App\Filament\Resources\Seasons\Pages\EditSeason;
use App\Filament\Resources\Seasons\Pages\ListSeasons;
use App\Filament\Resources\Seasons\RelationManagers\OwnershipGroupsRelationManager;
use App\Filament\Resources\Seasons\RelationManagers\TeamsRelationManager;
use App\Filament\Resources\Teams\RelationManagers\DriversRelationManager;
use App\Models\Season;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class SeasonResource extends Resource
{
    protected static ?string $model = Season::class;

    protected static ?string $slug = 'seasons';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('year')
                    ->required()
                    ->integer(),

                Select::make('status')
                    ->required()
                    ->options(SeasonStatus::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('year'),

                TextColumn::make('status')
                    ->badge(),
            ])
            ->filters([
                SelectFilter::make('Status')
                    ->options(SeasonStatus::class),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TeamsRelationManager::class,
            DriversRelationManager::class,
            OwnershipGroupsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSeasons::route('/'),
            'create' => CreateSeason::route('/create'),
            'edit' => EditSeason::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
