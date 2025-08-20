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
        return $this->check($user);
    }

    public function view(User $user, User $model): bool
    {
        return $this->check($user);
    }

    public function create(User $user): bool
    {
        return $this->check($user);
    }

    public function update(User $user, User $model): bool
    {
        return $this->check($user);
    }

    public function delete(User $user, User $model): bool
    {
        if (! $this->check($user)) {
            return false;
        }

        if ($this->check($model)) {
            return false;
        }

        return $user->ownershipGroups()->count() === 0;
    }

    public function restore(User $user, User $model): bool
    {
        return $this->check($user);
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $this->delete($user, $model);
    }

    private function check(User $user): bool
    {
        return $user->admin;
    }
}
