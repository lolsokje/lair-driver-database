<?php

declare(strict_types=1);

namespace App\Filament\Resources\Series;

use App\Filament\Resources\Series\Pages\CreateSeries;
use App\Filament\Resources\Series\Pages\EditSeries;
use App\Filament\Resources\Series\Pages\ListSeries;
use App\Models\Series;
use App\Models\User;
use App\Tables\Columns\SeriesBadge;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class SeriesResource extends Resource
{
    protected static ?string $model = Series::class;

    protected static ?string $slug = 'series';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calculator';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                Select::make('simmed_by')
                    ->label('Simmed by')
                    ->relationship('simmedBy')
                    ->options(fn () => User::query()
                        ->where('admin', true)
                        ->orderBy('username')
                        ->pluck('username', 'id')
                    )
                    ->required(),

                ColorPicker::make('background_colour')
                    ->required(),

                ColorPicker::make('text_colour')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SeriesBadge::make('name'),

                TextColumn::make('simmedBy.username'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSeries::route('/'),
            'create' => CreateSeries::route('/create'),
            'edit' => EditSeries::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
