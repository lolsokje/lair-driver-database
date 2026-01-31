<?php

declare(strict_types=1);

namespace App\Filament\Resources\Drivers;

use App\Enums\NavigationGroup;
use App\Enums\SeasonStatus;
use App\Filament\Resources\Drivers\Pages\CreateDriver;
use App\Filament\Resources\Drivers\Pages\EditDriver;
use App\Filament\Resources\Drivers\Pages\ListDrivers;
use App\Filament\Resources\Drivers\RelationManagers\TeamsRelationManager;
use App\Models\Driver;
use App\Models\Season;
use App\Tables\Columns\SeriesBadge;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

final class DriverResource extends Resource
{
    protected static ?string $model = Driver::class;

    protected static ?string $slug = 'drivers';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::TEAMS_AND_DRIVERS;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('given_name')
                    ->required(),

                TextInput::make('family_name')
                    ->required(),

                DatePicker::make('date_of_birth'),

                TextInput::make('rating')
                    ->required()
                    ->integer(),

                Checkbox::make('retired')
                    ->hiddenOn(CreateDriver::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        $minDriverRating = Driver::query()->min('rating');
        $maxDriverRating = Driver::query()->max('rating');

        $latestSeason = Season::query()
            ->where('status', SeasonStatus::ACTIVE)
            ->first();

        if (! $latestSeason) {
            $latestSeason = Season::query()
                ->orderBy('year', 'DESC')
                ->first();
        }

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

                TextColumn::make('team')
                    ->label('Current team')
                    ->state(fn (Driver $record) => $record->teamForSeason($latestSeason)?->short_name),

                SeriesBadge::make('series')
                    ->state(function (Driver $record) use ($latestSeason) {
                        $team = $record->teamForSeason($latestSeason);

                        return $team?->series;
                    }),

                TextColumn::make('date_of_birth')
                    ->sortable()
                    ->date('F jS, Y'),

                TextColumn::make('rating')
                    ->sortable()
                    ->copyable()
                    ->width('1%')
                    ->alignCenter(),

                TextColumn::make('age')
                    ->state(fn (Driver $record) => $record->ageForSeason($latestSeason->year))
                    ->width('1%')
                    ->alignCenter(),

                IconColumn::make('retired')
                    ->width('1%')
                    ->alignCenter(),
            ])
            ->filters([
                Filter::make('date_of_birth')
                    ->schema([
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

                Filter::make('rating')
                    ->schema([
                        Slider::make('rating')
                            ->range(
                                minValue: $minDriverRating,
                                maxValue: $maxDriverRating,
                            )
                            ->step(1)
                            ->decimalPlaces(0)
                            ->default([$minDriverRating, $maxDriverRating])
                            ->tooltips()
                            ->pips(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        [$minRating, $maxRating] = $data['rating'];

                        return $query
                            ->when(
                                $minRating,
                                fn (Builder $query, int $rating) => $query->where('rating', '>=', $rating),
                            )
                            ->when(
                                $maxRating,
                                fn (Builder $query, int $rating) => $query->where('rating', '<=', $rating),
                            );
                    }),

                SelectFilter::make('series')
                    ->relationship('series', 'name', hasEmptyOption: true)
                    ->emptyRelationshipOptionLabel('Free agent'),

                TernaryFilter::make('retired'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn (Driver $driver) => count($driver->teams) > 0),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDrivers::route('/'),
            'create' => CreateDriver::route('/create'),
            'edit' => EditDriver::route('/{record}/edit'),
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
