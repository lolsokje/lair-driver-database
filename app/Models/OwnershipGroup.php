<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\OwnershipGroupBuilder;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UseEloquentBuilder(OwnershipGroupBuilder::class)]
final class OwnershipGroup extends Model
{
    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(OwnershipGroupUser::class);
    }

    public function updateName(): void
    {
        $name = $this->users
            ->sortBy('username')
            ->map(fn (User $user) => $user->username)
            ->join(', ');

        $this->update([
            'name' => $name,
        ]);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }
}
