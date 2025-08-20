<?php

declare(strict_types=1);

namespace App\Filament\Resources\Drivers\RelationManagers;

use App\Models\Team;
use App\Tables\Columns\SeriesBadge;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class TeamsRelationManager extends RelationManager
{
    protected static string $relationship = 'teams';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('full_name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('full_name')
            ->columns([
                SeriesBadge::make('series.name')
                    ->width('1%'),

                TextColumn::make('season.year')
                    ->alignCenter()
                    ->width('1%'),

                TextColumn::make('full_name')
                    ->label('Team')
                    ->description(fn (Team $team) => $team->short_name)
                    ->searchable(['short_name', 'full_name']),
            ])
            ->filters([
                SelectFilter::make('series')
                    ->relationship('series', 'name'),

                SelectFilter::make('season')
                    ->relationship('season', 'year'),
            ]);
    }
}
