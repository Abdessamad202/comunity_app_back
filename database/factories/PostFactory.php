<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function definition()
    {
        $user = User::inRandomOrder()->first();
        $createdAt = $this->faker->dateTimeBetween($user->created_at, 'now');

        return [
            'user_id' => $user->id,
            'content' => $this->faker->sentence(),
            'picture' => null,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
