<?php

declare(strict_types=1);

namespace App\Filament\Resources\DriverResource\RelationManagers;

use App\Models\Team;
use App\Tables\Columns\SeriesBadge;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

final class TeamsRelationManager extends RelationManager
{
    protected static string $relationship = 'teams';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('full_name')
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

                Tables\Columns\TextColumn::make('season.year')
                    ->alignCenter()
                    ->width('1%'),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Team')
                    ->description(fn (Team $team) => $team->short_name)
                    ->searchable(['short_name', 'full_name']),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('series')
                    ->relationship('series', 'name'),

                Tables\Filters\SelectFilter::make('season')
                    ->relationship('season', 'year'),
            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContent);
    }
}
