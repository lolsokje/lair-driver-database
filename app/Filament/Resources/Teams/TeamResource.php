<?php

declare(strict_types=1);

namespace App\Filament\Resources\Teams;

use App\Filament\Resources\Teams\Pages\CreateTeam;
use App\Filament\Resources\Teams\Pages\EditTeam;
use App\Filament\Resources\Teams\Pages\ListTeams;
use App\Filament\Resources\Teams\RelationManagers\DriversRelationManager;
use App\Filament\Schemas\TeamSchema;
use App\Models\Team;
use App\Tables\Columns\SeriesBadge;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $slug = 'teams';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components(
            TeamSchema::get(),
        );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort(function (Builder $query) {
                // Sort by team_id by default, but allow overriding of sort column
                $query->orderBy('team_id');
            })
            ->modifyQueryUsing(function (Builder $query) {
                // However, always sort teams by season first, then by series to keep them grouped
                $query->withAggregate('season', 'year')
                    ->withAggregate('series', 'name')
                    ->with([
                        'ownershipGroup' => [
                            'users',
                        ],
                    ])
                    ->orderBy('season_year')
                    ->orderBy('series_name');
            })
            ->columns([
                TextColumn::make('team_id')
                    ->label('ID')
                    ->sortable()
                    ->copyable()
                    ->alignCenter()
                    ->width('1%'),

                SeriesBadge::make('series.name')
                    ->width('1%'),

                TextColumn::make('season.year')
                    ->width('1%')
                    ->alignCenter(),

                TextColumn::make('full_name')
                    ->label('Name')
                    ->description(fn (Team $record) => $record->short_name)
                    ->sortable()
                    ->copyable()
                    ->searchable(),

                ColumnGroup::make('Colours')
                    ->columns([
                        ColorColumn::make('primary_colour')
                            ->label('')
                            ->width('1%')
                            ->copyable(),

                        ColorColumn::make('secondary_colour')
                            ->label('')
                            ->width('1%')
                            ->copyable(),
                    ]),

                TextColumn::make('ownershipGroup.name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('season')
                    ->native(false)
                    ->relationship('season', 'year', modifyQueryUsing: function (Builder $query) {
                        return $query->orderBy('year');
                    }),

                SelectFilter::make('series')
                    ->native(false)
                    ->relationship('series', 'name'),

                SelectFilter::make('ownershipGroup')
                    ->searchable()
                    ->preload()
                    ->relationship('ownershipGroup', 'name', modifyQueryUsing: function (Builder $query) {
                        return $query->orderBy('name');
                    }),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
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
            'index' => ListTeams::route('/'),
            'create' => CreateTeam::route('/create'),
            'edit' => EditTeam::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['ownershipGroup', 'series']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['ownershipGroup.name', 'series.name', 'full_name', 'short_name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Team $team */
        $team = $record;

        $details = [];

        if ($team->ownershipGroup) {
            $details['Ownership group'] = $team->ownershipGroup->name;
        }

        if ($team->series) {
            $details['Series'] = $team->series->name;
        }

        return $details;
    }

    public static function getRelations(): array
    {
        return [
            DriversRelationManager::class,
        ];
    }
}
