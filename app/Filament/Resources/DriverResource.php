<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\DriverResource\Pages;
use App\Filament\Resources\DriverResource\RelationManagers\TeamsRelationManager;
use App\Models\Driver;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class DriverResource extends Resource
{
    protected static ?string $model = Driver::class;

    protected static ?string $slug = 'drivers';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('given_name')
                    ->required(),

                TextInput::make('family_name')
                    ->required(),

                DatePicker::make('date_of_birth'),

                TextInput::make('rating')
                    ->required()
                    ->integer(),

                Checkbox::make('retired')
                    ->hiddenOn(Pages\CreateDriver::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query
                    ->with([
                        'teams',
                    ]);
            })
            ->defaultSort('given_name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(['given_name', 'family_name'])
                    ->sortable(['given_name'])
                    ->state(fn (Driver $driver) => $driver->fullName())
                    ->copyable(),

                TextColumn::make('date_of_birth')
                    ->sortable()
                    ->date('F jS, Y'),

                TextColumn::make('rating')
                    ->sortable()
                    ->copyable()
                    ->width('1%')
                    ->alignCenter(),

                IconColumn::make('retired')
                    ->width('1%')
                    ->alignCenter(),
            ])
            ->filters([
                Filter::make('rating')
                    ->form([
                        TextInput::make('min_rating')
                            ->integer(),

                        TextInput::make('max_rating')
                            ->integer(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['min_rating'],
                                fn (Builder $query, int $rating) => $query->where('rating', '>=', $rating),
                            )
                            ->when(
                                $data['max_rating'],
                                fn (Builder $query, int $rating) => $query->where('rating', '<=', $rating),
                            );
                    }),

                Filter::make('date_of_birth')
                    ->form([
                        DatePicker::make('born_after'),
                        DatePicker::make('born_before'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['born_after'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_of_birth', '>=', $date),
                            )
                            ->when(
                                $data['born_before'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_of_birth', '<=', $date),
                            );
                    }),

                TernaryFilter::make('retired'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn (Driver $driver) => count($driver->teams) > 0),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDrivers::route('/'),
            'create' => Pages\CreateDriver::route('/create'),
            'edit' => Pages\EditDriver::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }

    public static function getRelations(): array
    {
        return [
            TeamsRelationManager::class,
        ];
    }
}
