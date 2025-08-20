<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\OwnershipGroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class OwnershipGroupPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->check($user);
    }

    public function view(User $user, OwnershipGroup $ownershipGroup): bool
    {
        return $this->check($user);
    }

    public function create(User $user): bool
    {
        return $this->check($user);
    }

    public function update(User $user, OwnershipGroup $ownershipGroup): bool
    {
        return $this->check($user);
    }

    public function delete(User $user, OwnershipGroup $ownershipGroup): bool
    {
        return $this->check($user);
    }

    public function restore(User $user, OwnershipGroup $ownershipGroup): bool
    {
        return $this->check($user);
    }

    public function forceDelete(User $user, OwnershipGroup $ownershipGroup): bool
    {
        return $this->check($user);
    }

    private function check(User $user): bool
    {
        return $user->admin;
    }
}
