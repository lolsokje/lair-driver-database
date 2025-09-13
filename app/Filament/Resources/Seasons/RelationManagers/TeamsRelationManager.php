<?php

declare(strict_types=1);

namespace App\Filament\Resources\Seasons\RelationManagers;

use App\Filament\Resources\Teams\TeamResource;
use App\Filament\Schemas\TeamSchema;
use App\Models\Season;
use App\Models\Team;
use App\Tables\Columns\SeriesBadge;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class TeamsRelationManager extends RelationManager
{
    protected static string $relationship = 'teams';

    public function form(Schema $schema): Schema
    {
        /** @var Season|null $season */
        $season = $this->getOwnerRecord();
        /** @var Team|null $team */
        $team = $schema->getRecord();

        return $schema->components(
            TeamSchema::get(
                seasonId: $season?->id,
                seriesId: $team?->series_id,
            ),
        );
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->with([
                    'series',
                ]))
            ->recordTitleAttribute('full_name')
            ->columns([
                SeriesBadge::make('series')
                    ->width('1%'),

                TextColumn::make('full_name')
                    ->description(fn (Team $record) => $record->short_name)
                    ->searchable(),

                TextColumn::make('ownershipGroup.users.username')
                    ->label('Owners'),
            ])
            ->filters([
                SelectFilter::make('series')
                    ->relationship('series', 'name')
                    ->native(false),

                SelectFilter::make('ownershipGroup')
                    ->relationship('ownershipGroup', 'name', modifyQueryUsing: fn (Builder $query) => $query->orderBy('name'))
                    ->native(false),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make()
                    ->url(fn (Team $record) => TeamResource::getUrl('edit', [$record])),
            ]);
    }
}
