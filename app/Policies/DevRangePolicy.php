<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\DevRange;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class DevRangePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, DevRange $devRange): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $this->check($user);
    }

    public function update(User $user, DevRange $devRange): bool
    {
        return $this->check($user);
    }

    public function delete(User $user, DevRange $devRange): bool
    {
        return $this->check($user);
    }

    public function restore(User $user, DevRange $devRange): bool
    {
        return $this->check($user);
    }

    public function forceDelete(User $user, DevRange $devRange): bool
    {
        return $this->check($user);
    }

    private function check(User $user): bool
    {
        return $user->admin;
    }
}
