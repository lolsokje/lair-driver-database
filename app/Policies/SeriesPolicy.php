<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Series;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class SeriesPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->check($user);
    }

    public function view(User $user, Series $series): bool
    {
        return $this->check($user);
    }

    public function create(User $user): bool
    {
        return $this->check($user);
    }

    public function update(User $user, Series $series): bool
    {
        return $this->check($user);
    }

    public function delete(User $user, Series $series): bool
    {
        return $this->check($user);
    }

    public function restore(User $user, Series $series): bool
    {
        return $this->check($user);
    }

    public function forceDelete(User $user, Series $series): bool
    {
        return $this->check($user);
    }

    private function check(User $user): bool
    {
        return $user->admin;
    }
}
