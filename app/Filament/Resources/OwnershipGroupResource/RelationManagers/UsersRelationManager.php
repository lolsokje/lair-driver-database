<?php

declare(strict_types=1);

namespace App\Filament\Resources\OwnershipGroupResource\RelationManagers;

use App\Models\OwnershipGroup;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Livewire\Component;

final class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('username')
            ->defaultSort('username')
            ->columns([
                Tables\Columns\TextColumn::make('username'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->after(fn (Component $livewire) => $livewire->dispatch('refreshOwnershipGroupName'))
                    ->multiple()
                    ->recordSelectOptionsQuery(function (Builder $query) {
                        /** @var OwnershipGroup $group */
                        $group = $this->getOwnerRecord();

                        // Ensures users used in other ownership groups this season aren't shown
                        return $query
                            ->whereNotIn('users.id', function (QueryBuilder $userQuery) use ($group) {
                                return $userQuery->select('user_id')
                                    ->from('ownership_group_user')
                                    ->whereIn('ownership_group_id', function (QueryBuilder $seasonQuery) use ($group) {
                                        $seasonQuery->select('id')
                                            ->from('ownership_groups')
                                            ->where('season_id', $group->season_id);
                                    });
                            });
                    })
                    ->preloadRecordSelect(),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->after(fn (Component $livewire) => $livewire->dispatch('refreshOwnershipGroupName')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
