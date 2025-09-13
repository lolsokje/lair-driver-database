<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users;

use App\Enums\NavigationGroup;
use App\Filament\Resources\Drivers\Pages\ListDrivers;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use STS\FilamentImpersonate\Actions\Impersonate;
use UnitEnum;

final class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'users';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::OTHERS;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('username')
                    ->disabled(fn (?User $record) => $record !== null)
                    ->helperText(fn (?User $record) => $record !== null ? 'The username is updated whenever the user logs in' : '')
                    ->required(),

                TextInput::make('discord_id')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label('')
                    ->width('1%')
                    ->circular(),

                TextColumn::make('username'),

                TextColumn::make('last_login_at')
                    ->state(fn (User $record) => $record->last_login_at?->format('F jS, Y \a\t H:i'))
                    ->alignEnd()
                    ->width('1%'),

                IconColumn::make('admin')
                    ->alignCenter()
                    ->width('1%'),
            ])
            ->recordActions([
                Impersonate::make()
                    ->label('')
                    ->redirectTo(ListDrivers::getUrl()),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
