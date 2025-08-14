<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\OwnershipGroupResource\Pages;
use App\Filament\Resources\OwnershipGroupResource\RelationManagers\UsersRelationManager;
use App\Models\OwnershipGroup;
use App\Models\Season;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class OwnershipGroupResource extends Resource
{
    protected static ?string $model = OwnershipGroup::class;

    protected static ?string $slug = 'ownership-groups';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('season_id')
                    ->label('Season')
                    ->options(Season::query()->orderByDesc('year')->pluck('year', 'id')),

                TextInput::make('name')
                    ->disabled(fn (OwnershipGroup $group) => $group->users->count() > 0)
                    ->helperText(fn (OwnershipGroup $group) => $group->users->count() > 0 ? 'The name is automatically generated based on attached users' : '')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('season'))
            ->defaultSort(function (Builder $query) {
                return $query
                    ->withAggregate('season', 'year')
                    ->orderBy('name')
                    ->orderBy('season_year', 'DESC');
            })
            ->columns([
                TextColumn::make('season_id')
                    ->label('Season')
                    ->sortable()
                    ->getStateUsing(function (OwnershipGroup $group) {
                        return $group->season->year;
                    }),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('season_id')
                    ->label('Season')
                    ->relationship('season', 'Year'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOwnershipGroups::route('/'),
            'create' => Pages\CreateOwnershipGroup::route('/create'),
            'edit' => Pages\EditOwnershipGroup::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class,
        ];
    }
}
