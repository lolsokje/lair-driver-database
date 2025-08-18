<?php

declare(strict_types=1);

namespace App\Filament\Resources\TeamResource\RelationManagers;

use App\Models\Driver;
use App\Models\Season;
use App\Models\Team;
use App\Tables\Columns\SeriesBadge;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

final class DriversRelationManager extends RelationManager
{
    protected static string $relationship = 'drivers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->integer()
                    ->required(),

                Forms\Components\TextInput::make('driver_sheet_id')
                    ->label('Driver ID'),

                Forms\Components\Checkbox::make('reserve')
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
            ->recordTitleAttribute('given_name')
            ->columns([
                Tables\Columns\TextColumn::make('driver_sheet_id')
                    ->label('ID')
                    ->alignCenter()
                    ->copyable()
                    ->width('1%'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(['given_name', 'family_name'])
                    ->sortable()
                    ->copyable()
                    ->state(fn (Driver $driver) => $driver->fullName()),

                SeriesBadge::make('series.name')
                    ->width('1%'),

                Tables\Columns\TextColumn::make('season.year')
                    ->state(function (Driver $record) {
                        /** @var Season $season */
                        $season = $this->getOwnerRecord();

                        /** @var Season $driverSeason */
                        $driverSeason = $record->season->where('year', $season->year)->first();

                        return $driverSeason->year;
                    })
                    ->width('1%')
                    ->alignCenter()
                    ->hidden(fn () => $this->isOnTeamsPage()),

                Tables\Columns\TextColumn::make('rating')
                    ->width('1%')
                    ->copyable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('number')
                    ->width('1%')
                    ->alignCenter()
                    ->copyable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('reserve')
                    ->boolean()
                    ->width('1%')
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
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
                    ->form(fn (Tables\Actions\AttachAction $action) => [
                        $action->getRecordSelect()
                            ->searchable(['given_name', 'family_name'])
                            ->required(),

                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('number')
                                    ->integer()
                                    ->required(),

                                Forms\Components\TextInput::make('driver_sheet_id')
                                    ->label('Driver ID'),
                            ]),

                        Forms\Components\Checkbox::make('reserve')
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
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading(fn (Driver $record) => "Edit {$record->fullName()}")
                    ->hidden(fn () => ! $this->isOnTeamsPage()),
                Tables\Actions\DetachAction::make()
                    ->hidden(fn () => ! $this->isOnTeamsPage()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function isOnTeamsPage(): bool
    {
        return $this->getOwnerRecord() instanceof Team;
    }
}
