<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\DevRangeResource\Pages;
use App\Models\DevRange;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

final class DevRangeResource extends Resource
{
    protected static ?string $model = DevRange::class;

    protected static ?string $slug = 'dev-ranges';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AdjustmentsVertical;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return $table;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDevRanges::route('/'),
            'create' => Pages\CreateDevRange::route('/create'),
            'edit' => Pages\EditDevRange::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
