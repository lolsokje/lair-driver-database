<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\DevelopmentResult;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class DevelopmentResultPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, DevelopmentResult $developmentResult): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, DevelopmentResult $developmentResult): bool
    {
        return false;
    }

    public function delete(User $user, DevelopmentResult $developmentResult): bool
    {
        return false;
    }

    public function restore(User $user, DevelopmentResult $developmentResult): bool
    {
        return false;
    }

    public function forceDelete(User $user, DevelopmentResult $developmentResult): bool
    {
        return false;
    }
}
