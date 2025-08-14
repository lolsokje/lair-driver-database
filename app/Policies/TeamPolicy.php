<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class TeamPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Team $team): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->admin;
    }

    public function update(User $user, Team $team): bool
    {
        if ($this->check($user)) {
            return true;
        }

        $ownershipGroup = $team->ownershipGroup;

        dd($ownershipGroup);
    }

    public function delete(User $user, Team $team): bool
    {
        return $this->check($user);
    }

    public function restore(User $user, Team $team): bool
    {
        return $this->check($user);
    }

    public function forceDelete(User $user, Team $team): bool
    {
        return $this->check($user);
    }

    private function check(User $user): bool
    {
        return $user->admin;
    }
}
