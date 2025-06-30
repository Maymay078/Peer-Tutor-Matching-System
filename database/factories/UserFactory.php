<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'full_name' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            // Generate phone number starting with "01" and 10 or 11 digits
            'phone_number' => $this->faker->numerify('01########') . $this->faker->optional()->numerify('#'),
            'role' => 'student',
            'profile_image' => 'https://api.dicebear.com/7.x/micah/svg?seed=' . urlencode($this->faker->name()) . '&backgroundColor=e0f2fe,c7d2fe,fae8ff&radius=50',
            'date_of_birth' => $this->faker->dateTimeBetween('1990-01-01', '2006-12-31')->format('Y-m-d'),
            'remember_token' => Str::random(10),
        ];
    }
}
