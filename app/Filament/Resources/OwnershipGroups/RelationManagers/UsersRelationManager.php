<?php

declare(strict_types=1);

namespace App\Filament\Resources\OwnershipGroups\RelationManagers;

use App\Models\OwnershipGroup;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Livewire\Component;

final class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('username')
            ->defaultSort('username')
            ->columns([
                TextColumn::make('username'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->after(fn (Component $livewire) => $livewire->dispatch('refreshOwnershipGroupName'))
                    ->multiple()
                    ->recordSelectOptionsQuery(function (Builder $query) {
                        /** @var OwnershipGroup $group */
                        $group = $this->getOwnerRecord();

                        // Ensures users used in other ownership groups this season aren't shown
                        return $query
                            ->whereNotIn('users.id', function (QueryBuilder $userQuery) {
                                return $userQuery->select('user_id')
                                    ->from('ownership_group_user');
                            });
                    })
                    ->preloadRecordSelect(),
            ])
            ->recordActions([
                DetachAction::make()
                    ->after(fn (Component $livewire) => $livewire->dispatch('refreshOwnershipGroupName')),
            ]);
    }
}
