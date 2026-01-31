<?php

declare(strict_types=1);

namespace App\Filament\Resources\OwnershipGroups;

use App\Enums\NavigationGroup;
use App\Filament\Resources\OwnershipGroups\Pages\CreateOwnershipGroup;
use App\Filament\Resources\OwnershipGroups\Pages\EditOwnershipGroup;
use App\Filament\Resources\OwnershipGroups\Pages\ListOwnershipGroups;
use App\Filament\Resources\OwnershipGroups\RelationManagers\UsersRelationManager;
use App\Models\OwnershipGroup;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

final class OwnershipGroupResource extends Resource
{
    protected static ?string $model = OwnershipGroup::class;

    protected static ?string $slug = 'ownership-groups';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::SETTINGS;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->disabled(fn (?OwnershipGroup $group) => $group?->users->count() > 0)
                    ->helperText(fn (?OwnershipGroup $group) => $group?->users->count() > 0 ? 'The name is automatically generated based on attached users' : '')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort(function (Builder $query) {
                return $query->orderBy('name');
            })
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOwnershipGroups::route('/'),
            'create' => CreateOwnershipGroup::route('/create'),
            'edit' => EditOwnershipGroup::route('/{record}/edit'),
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
