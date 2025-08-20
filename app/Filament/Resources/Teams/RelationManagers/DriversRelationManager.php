<?php

declare(strict_types=1);

namespace App\Filament\Resources\Teams\RelationManagers;

use App\Models\Driver;
use App\Models\Season;
use App\Models\Team;
use App\Tables\Columns\SeriesBadge;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

final class DriversRelationManager extends RelationManager
{
    protected static string $relationship = 'drivers';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('number')
                    ->integer()
                    ->required(),

                TextInput::make('driver_sheet_id')
                    ->label('Driver ID'),

                Checkbox::make('reserve')
                    ->label('Reserve driver?'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query
                    ->with([
                        'teams',
                        'season',
                    ]);
            })
            ->defaultSort(function (Builder $query) {
                return $query
                    ->orderBy('series_id')
                    ->orderBy('given_name');
            })
            ->recordTitleAttribute('given_name')
            ->columns([
                TextColumn::make('driver_sheet_id')
                    ->label('ID')
                    ->alignCenter()
                    ->copyable()
                    ->width('1%'),

                TextColumn::make('name')
                    ->searchable(['given_name', 'family_name'])
                    ->sortable()
                    ->copyable()
                    ->state(fn (Driver $driver) => $driver->fullName()),

                SeriesBadge::make('series.name')
                    ->sortable()
                    ->width('1%'),

                TextColumn::make('season.year')
                    ->state(function (Driver $record) {
                        /** @var Season $season */
                        $season = $this->getOwnerRecord();

                        /** @var Season $driverSeason */
                        $driverSeason = $record->season->where('year', $season->year)->first();

                        return $driverSeason->year;
                    })
                    ->width('1%')
                    ->alignCenter()
                    ->sortable()
                    ->hidden(fn () => $this->isOnTeamsPage()),

                TextColumn::make('rating')
                    ->width('1%')
                    ->copyable()
                    ->alignCenter(),

                TextColumn::make('number')
                    ->width('1%')
                    ->alignCenter()
                    ->copyable()
                    ->sortable(),

                IconColumn::make('reserve')
                    ->boolean()
                    ->width('1%')
                    ->alignCenter(),
            ])
            ->filters([
                SelectFilter::make('series')
                    ->relationship('series', 'name'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->hidden(fn () => ! $this->isOnTeamsPage())
                    ->recordSelectOptionsQuery(function (Builder $query) {
                        /** @var Team $team */
                        $team = $this->getOwnerRecord();

                        // Get drivers not currently used by other teams in other seasons
                        return $query
                            ->orderBy('given_name')
                            ->whereNotIn('drivers.id', function (QueryBuilder $query) use ($team) {
                                return $query->select('driver_id')
                                    ->from('driver_team')
                                    ->where('season_id', $team->season_id);
                            });
                    })
                    ->preloadRecordSelect()
                    ->recordTitle(fn (Driver $record) => $record->fullName())
                    ->schema(fn (AttachAction $action) => [
                        $action->getRecordSelect()
                            ->searchable(['given_name', 'family_name'])
                            ->required(),

                        Grid::make()
                            ->schema([
                                TextInput::make('number')
                                    ->integer()
                                    ->required(),

                                TextInput::make('driver_sheet_id')
                                    ->label('Driver ID'),
                            ]),

                        Checkbox::make('reserve')
                            ->label('Reserve driver?'),
                    ])
                    ->using(function (array $data, DriversRelationManager $livewire) {
                        /** @var Team $team */
                        $team = $livewire->getOwnerRecord();
                        /** @var Driver $driver */
                        $driver = Driver::find($data['recordId']);

                        unset($data['recordId']);
                        $data['rating'] = $driver->rating;
                        $data['season_id'] = $team->season_id;
                        $data['series_id'] = $team->series_id;

                        $team->drivers()->attach($driver->id, $data);
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalHeading(fn (Driver $record) => "Edit {$record->fullName()}")
                    ->hidden(fn () => ! $this->isOnTeamsPage()),
                DetachAction::make()
                    ->hidden(fn () => ! $this->isOnTeamsPage()),
            ]);
    }

    public function isOnTeamsPage(): bool
    {
        return $this->getOwnerRecord() instanceof Team;
    }
}
