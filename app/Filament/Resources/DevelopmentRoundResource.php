<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\NavigationGroup;
use App\Filament\Resources\DevelopmentResultResource\Pages\ListDevelopmentResults;
use App\Filament\Resources\DevelopmentRoundResource\Pages;
use App\Filament\Resources\DevelopmentRoundResource\RelationManagers\DevelopmentResultsRelationManager;
use App\Models\DevelopmentRound;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

final class DevelopmentRoundResource extends Resource
{
    protected static ?string $model = DevelopmentRound::class;

    protected static ?string $slug = 'development-rounds';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowsUpDown;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::DEVELOPMENT;

    protected static ?string $label = 'Development';

    protected static ?string $pluralLabel = 'Development';

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort(fn (Builder $query) => $query
                ->withAggregate('season', 'year')
                ->orderBy('season_year'),
            )
            ->columns([
                TextColumn::make('season.year')
                    ->label('Season'),

                TextColumn::make('status')
                    ->badge(),
            ]);
        // ->recordActions([
        //     ViewAction::make(),
        // ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDevelopmentRounds::route('/'),
            'edit' => Pages\EditDevelopmentRound::route('/{record}/edit'),
            // 'view' => ListDevelopmentResults::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            DevelopmentResultsRelationManager::class,
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
