<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\AgeRange;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class AgeRangePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->check($user);
    }

    public function view(User $user, AgeRange $ageRange): bool
    {
        return $this->check($user);
    }

    public function create(User $user): bool
    {
        return $this->check($user);
    }

    public function update(User $user, AgeRange $ageRange): bool
    {
        return $this->check($user);
    }

    public function delete(User $user, AgeRange $ageRange): bool
    {
        return $this->check($user);
    }

    public function restore(User $user, AgeRange $ageRange): bool
    {
        return $this->check($user);
    }

    public function forceDelete(User $user, AgeRange $ageRange): bool
    {
        return $this->check($user);
    }

    private function check(User $user): bool
    {
        return $user->admin;
    }
}
