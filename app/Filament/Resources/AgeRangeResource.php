<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\NavigationGroup;
use App\Filament\Resources\AgeRangeResource\Pages;
use App\Filament\Resources\AgeRangeResource\RelationManagers\DevRangesRelationManager;
use App\Models\AgeRange;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

final class AgeRangeResource extends Resource
{
    protected static ?string $model = AgeRange::class;

    protected static ?string $slug = 'age-ranges';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AdjustmentsHorizontal;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::DEVELOPMENT;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('min_age')
                    ->required()
                    ->minValue(0)
                    ->integer(),

                TextInput::make('max_age')
                    ->required()
                    ->minValue(0)
                    ->integer(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort(fn (Builder $query) => $query->orderBy('min_age'))
            ->columns([
                TextColumn::make('min_age')
                    ->sortable(),

                TextColumn::make('max_age')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgeRanges::route('/'),
            'create' => Pages\CreateAgeRange::route('/create'),
            'edit' => Pages\EditAgeRange::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }

    public static function getRelations(): array
    {
        return [
            DevRangesRelationManager::class,
        ];
    }
}
