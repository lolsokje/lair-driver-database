<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'username' => $this->faker->userName(),
            'discord_id' => $this->faker->lexify(str_repeat('?', 18)),
            'avatar' => $this->faker->url(),
        ];
    }
}
