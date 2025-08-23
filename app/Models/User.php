<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use DutchCodingCompany\FilamentSocialite\Models\Contracts\FilamentSocialiteUser;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;

final class User extends Authenticatable implements FilamentSocialiteUser, FilamentUser, HasAvatar, HasName
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use Notifiable;

    public static function findForProvider(string $provider, SocialiteUserContract $oauthUser): ?self
    {
        $user = self::query()->where('discord_id', $oauthUser->getId())->first();

        if (! $user) {
            return null;
        }

        if ($user->username !== $oauthUser->getNickname()) {
            $user->username = $oauthUser->getNickname();
        }

        if ($user->avatar !== $oauthUser->getAvatar()) {
            $user->avatar = $oauthUser->getAvatar();
        }

        $user->save();

        return $user;
    }

    public static function createForProvider(string $provider, SocialiteUserContract $oauthUser, AuthenticatableContract $user): FilamentSocialiteUser
    {
        return self::findForProvider($provider, $oauthUser);
    }

    public function getAuthPassword(): string
    {
        return '';
    }

    public function getRememberToken(): string
    {
        return '';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getFilamentName(): string
    {
        return $this->username;
    }

    public function getFilamentAvatarUrl(): string
    {
        return $this->avatar;
    }

    public function getUser(): AuthenticatableContract
    {
        return $this;
    }

    public function ownershipGroups(): BelongsToMany
    {
        return $this->belongsToMany(OwnershipGroup::class);
    }

    protected function casts(): array
    {
        return [
            'admin' => 'boolean',
        ];
    }
}
