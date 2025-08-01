<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\SeasonStatus;
use App\Models\Season;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class SeasonPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Season $season): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->admin;
    }

    public function update(User $user, Season $season): bool
    {
        return $user->admin;
    }

    public function delete(User $user, Season $season): bool
    {
        return $user->admin && $season->status === SeasonStatus::PENDING;
    }

    public function restore(User $user, Season $season): bool
    {
        return $user->admin;
    }

    public function forceDelete(User $user, Season $season): bool
    {
        return $this->delete($user, $season);
    }
}
