<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
//use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{   
     /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        $avatarUrls = [
            'https://res.cloudinary.com/drjjwnstk/image/upload/v1716538444/user-profile/photo-1531746020798-e6953c6e8e04_f7xmch.avif',
            'https://res.cloudinary.com/drjjwnstk/image/upload/v1716538389/user-profile/photo-1534528741775-53994a69daeb_gfp7da.avif',
            'https://res.cloudinary.com/drjjwnstk/image/upload/v1716538377/user-profile/photo-1492447273231-0f8fecec1e3a_jgwvmg.avif',
            'https://res.cloudinary.com/drjjwnstk/image/upload/v1716538016/user-profile/photo-1539571696357-5a69c17a67c6_vraval.avif',
            'https://res.cloudinary.com/drjjwnstk/image/upload/v1716538227/user-profile/photo-1494790108377-be9c29b29330_khikwd.avif',
            'https://res.cloudinary.com/drjjwnstk/image/upload/v1716538318/user-profile/photo-1622031093531-f4e641788763_elfjwt.avif',
            'https://res.cloudinary.com/drjjwnstk/image/upload/v1716537999/user-profile/photo-1530785602389-07594beb8b73_d55vzd.avif',
            'https://res.cloudinary.com/drjjwnstk/image/upload/v1716538369/user-profile/photo-1496361001419-80f0d1be777a_yzeaw9.avif',
        ];

        // Select a random image URL from the array
        $avatarUrl = $this->faker->randomElement($avatarUrls);

        return [
            'name' => $this->faker->userName,
            'email' => $this->faker->unique()->safeEmail(),
            //'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            //'remember_token' => Str::random(10),
            'description' => $this->faker->sentence(),
            'avatar' => $avatarUrl,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
