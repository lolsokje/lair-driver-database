<?php

declare(strict_types=1);

namespace App\Filament\Resources\SeasonResource\RelationManagers;

use App\Filament\Resources\TeamResource;
use App\Filament\Schemas\TeamSchema;
use App\Models\Season;
use App\Models\Team;
use App\Tables\Columns\SeriesBadge;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class TeamsRelationManager extends RelationManager
{
    protected static string $relationship = 'teams';

    public function form(Form $form): Form
    {
        /** @var Season|null $season */
        $season = $this->getOwnerRecord();
        /** @var Team|null $team */
        $team = $form->getRecord();

        return $form->schema(
            TeamSchema::get(
                seasonId: $season?->id,
                seriesId: $team?->series_id,
            ),
        );
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('full_name')
            ->columns([
                SeriesBadge::make('series.name')
                    ->width('1%'),

                Tables\Columns\TextColumn::make('full_name')
                    ->description(fn (Team $record) => $record->short_name)
                    ->searchable(),

                Tables\Columns\TextColumn::make('ownershipGroup.users.username')
                    ->label('Owners'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('series')
                    ->relationship('series', 'name')
                    ->native(false),

                Tables\Filters\SelectFilter::make('ownershipGroup')
                    ->relationship('ownershipGroup', 'name', modifyQueryUsing: fn (Builder $query) => $query->orderBy('name'))
                    ->native(false),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn (Team $record) => TeamResource::getUrl('edit', [$record])),
            ]);
    }
}
