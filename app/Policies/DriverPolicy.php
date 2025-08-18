<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class DriverPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Driver $driver): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $this->check($user);
    }

    public function update(User $user, Driver $driver): bool
    {
        return $this->check($user);
    }

    public function delete(User $user, Driver $driver): bool
    {
        return $this->check($user);
    }

    public function restore(User $user, Driver $driver): bool
    {
        return $this->check($user);
    }

    public function forceDelete(User $user, Driver $driver): bool
    {
        return $this->check($user);
    }

    private function check(User $user): bool
    {
        return $user->admin;
    }
}
