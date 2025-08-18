<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, User $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->admin;
    }

    public function update(User $user, User $model): bool
    {
        return $user->admin;
    }

    public function delete(User $user, User $model): bool
    {
        if (! $user->admin) {
            return false;
        }

        if ($model->admin) {
            return false;
        }

        return $user->ownershipGroups()->count() === 0;
    }

    public function restore(User $user, User $model): bool
    {
        return $user->admin;
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $this->delete($user, $model);
    }
}
