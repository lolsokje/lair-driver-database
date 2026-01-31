<?php

declare(strict_types=1);

namespace App\Filament\Schemas;

use App\Models\OwnershipGroup;
use App\Models\Series;
use App\Models\Team;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Database\Eloquent\Builder;

final readonly class TeamSchema
{
    public static function get(
        ?int $seasonId = null,
        ?int $seriesId = null,
    ): array {
        $columnCount = 3;

        if ($seasonId !== null && $seriesId !== null) {
            $columnCount = 1;
        }

        if ($seasonId !== null && $seriesId === null) {
            $columnCount = 2;
        }

        return [
            Grid::make($columnCount)
                ->schema([
                    Select::make('season_id')
                        ->relationship(
                            name: 'season',
                            titleAttribute: 'year',
                            modifyQueryUsing: fn (Builder $query) => $query->orderBy('year', 'DESC')
                        )
                        ->hidden(fn () => $seasonId !== null)
                        ->default(fn () => $seasonId)
                        ->live()
                        ->required(),

                    Select::make('ownership_group_id')
                        ->relationship('ownershipGroup', 'name')
                        ->options(function () {
                            return OwnershipGroup::query()
                                ->with('users')
                                ->orderBy('name')
                                ->get()
                                ->mapWithKeys(fn (OwnershipGroup $group) => [$group->id => $group->name]);
                        })
                        ->live()
                        ->required(),

                    Select::make('series_id')
                        ->relationship('series', 'name')
                        ->options(function (Get $get) {
                            return Series::query()
                                ->whereNotIn(
                                    'id',
                                    Team::query()
                                        ->where('season_id', $get('season_id'))
                                        ->where('ownership_group_id', $get('ownership_group_id'))
                                        ->pluck('series_id')
                                )
                                ->get()
                                ->mapWithKeys(fn (Series $series) => [$series->id => $series->name]);
                        })
                        ->hidden(fn () => $seriesId !== null)
                        ->default(fn () => $seriesId)
                        ->helperText('Any missing series means this group already has a team in that series for that season')
                        ->required(),
                ])
                ->hidden(fn (?Team $record) => $record !== null),

            Grid::make([
                'sm' => 1,
                'md' => 12,
            ])
                ->schema([
                    TextInput::make('full_name')
                        ->columnSpan([
                            'sm' => 1,
                            'md' => 6,
                        ])
                        ->required(),

                    TextInput::make('short_name')
                        ->columnSpan([
                            'sm' => 1,
                            'md' => 4,
                        ])
                        ->required(),

                    TextInput::make('team_id')
                        ->columnSpan([
                            'sm' => 1,
                            'md' => 2,
                        ])
                        ->label('Team ID'),
                ]),

            ColorPicker::make('primary_colour')
                ->required(),

            ColorPicker::make('secondary_colour'),
        ];
    }
}
