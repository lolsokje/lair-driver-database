<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\DevelopmentResultResource\Pages;
use App\Models\DevelopmentResult;
use App\Tables\Columns\SeriesBadge;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class DevelopmentResultResource extends Resource
{
    protected static ?string $model = DevelopmentResult::class;

    protected static ?string $slug = 'development-results';

    protected static bool $shouldRegisterNavigation = false;

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withAggregate('driver', 'given_name')
                ->withAggregate('series', 'name')
                ->withAggregate('team', 'full_name')
                ->orderByRaw('ISNULL(series_name), series_name ASC, team_full_name, driver_given_name')
                ->with([
                    'developmentRound' => [
                        'season',
                    ],
                    'driver',
                    'series',
                    'team',
                ])
            )
            ->columns([
                TextColumn::make('Driver')
                    ->grow()
                    ->state(fn (DevelopmentResult $record) => $record->driver->fullName()),

                SeriesBadge::make('series')
                    ->width('1%'),

                TextColumn::make('team.full_name')
                    ->width('1%'),

                TextColumn::make('age')
                    ->width('1%')
                    ->alignCenter()
                    ->state(fn (DevelopmentResult $record) => $record->driver->ageForSeason($record->developmentRound->season->year)),

                TextColumn::make('old_rating')
                    ->width('1%')
                    ->alignCenter(),

                TextColumn::make('development')
                    ->width('1%')
                    ->alignCenter(),

                TextColumn::make('new_rating')
                    ->state(fn (DevelopmentResult $result) => $result->old_rating + $result->development)
                    ->weight(FontWeight::Bold)
                    ->color(fn (DevelopmentResult $result) => $result->color())
                    ->icon(fn (DevelopmentResult $result) => $result->icon())
                    ->iconColor(fn (DevelopmentResult $result) => $result->color())
                    ->width('1%')
                    ->alignCenter(),
            ])
            ->filters([
                SelectFilter::make('series')
                    ->relationship(
                        name: 'series',
                        titleAttribute: 'name',
                        hasEmptyOption: true
                    )
                    ->multiple(),

                SelectFilter::make('owners')
                    ->relationship(
                        name: 'team.ownershipGroup',
                        titleAttribute: 'name',
                    ),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDevelopmentResults::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
