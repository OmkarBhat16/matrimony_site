<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProfile>
 */
class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'date_of_birth' => fake()->date(),
            'gender' => fake()->randomElement(['male', 'female']),
            'address' => fake()->address(),
            'phone_number' => fake()->unique()->phoneNumber(),
            'bio' => fake()->paragraph(),
            'profile_picture' => 'https://www.loremfaces.net/96/id/' . fake()->numberBetween(1, 5) . '.jpg',
            'marital_status' => fake()->randomElement(['single', 'married', 'divorced', 'widowed']),
            'education' => fake()->word(),
            'occupation' => fake()->jobTitle(),
            'annual_income' => fake()->randomFloat(2, 10000, 1000000),
            'religion' => fake()->word(),
            'caste' => fake()->word(),
            'mother_tongue' => fake()->languageCode(),
            'state' => fake()->state(),
            'city' => fake()->city(),
            'height_cm' => fake()->numberBetween(150, 200),
            'weight_kg' => fake()->randomFloat(2, 40, 120),
            'dietary_preferences' => fake()->randomElement(['vegetarian', 'non-vegetarian', 'vegan']),
            'smoking_habits' => fake()->randomElement(['non-smoker', 'occasional', 'regular']),
            'drinking_habits' => fake()->randomElement(['non-drinker', 'occasional', 'regular']),
            'hobbies_interests' => fake()->sentence(),
        ];
    }
}
