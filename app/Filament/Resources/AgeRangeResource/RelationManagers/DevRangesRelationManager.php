<?php

declare(strict_types=1);

namespace App\Filament\Resources\AgeRangeResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class DevRangesRelationManager extends RelationManager
{
    protected static string $relationship = 'devRanges';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort(fn (Builder $query) => $query->orderBy('min_rating'))
            ->columns([
                TextColumn::make('min_rating'),

                TextColumn::make('max_rating'),

                TextColumn::make('min_dev'),

                TextColumn::make('max_dev'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('min_rating')
                    ->integer()
                    ->minValue(0)
                    ->required(),

                TextInput::make('max_rating')
                    ->integer()
                    ->minValue(0)
                    ->required(),

                TextInput::make('min_dev')
                    ->integer()
                    ->required(),

                TextInput::make('max_dev')
                    ->integer()
                    ->required(),
            ]);
    }
}
