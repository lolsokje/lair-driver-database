<?php

declare(strict_types=1);

namespace App\Filament\Resources\OwnershipGroups\Pages;

use App\Filament\Resources\OwnershipGroups\OwnershipGroupResource;
use App\Models\OwnershipGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\On;

final class EditOwnershipGroup extends EditRecord
{
    protected static string $resource = OwnershipGroupResource::class;

    #[On('refreshOwnershipGroupName')]
    public function refresh(): void
    {
        $this->refreshFormData(['name']);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->before(function () {
                    /** @var OwnershipGroup $group */
                    $group = $this->record;

                    $group->users()->sync([]);
                }),
        ];
    }
}
